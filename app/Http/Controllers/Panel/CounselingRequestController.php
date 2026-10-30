<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\CounselingRequestHistory;
use App\Models\CounselingSchedule;
use App\Models\CounselingService;
use App\Models\Counselor;
use App\Models\GuidanceField;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CounselingRequestController extends Controller
{
    /**
     * Monitoring & Daftar Pengajuan Agenda Konseling
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $classroomId = $request->input('classroom_id');
        $counselorId = $request->input('counselor_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('q');

        $query = CounselingRequest::with([
            'student.classrooms',
            'guidanceField',
            'serviceType',
            'assignedCounselor',
            'schedule',
        ]);

        // Role check: If counselor, default can filter or see assigned/binaan
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($classroomId) {
            $query->whereHas('student.classrooms', function ($q) use ($classroomId) {
                $q->where('classrooms.id', $classroomId);
            });
        }

        if ($counselorId) {
            $query->where('assigned_counselor_id', $counselorId);
        }

        $source = $request->input('source');
        if ($source && in_array($source, ['pribadi', 'ims'])) {
            $query->where('source', $source);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        // Actual Counters
        $counters = [
            'all' => CounselingRequest::count(),
            'pending' => CounselingRequest::where('status', 'pending')->count(),
            'needs_information' => CounselingRequest::where('status', 'needs_information')->count(),
            'approved' => CounselingRequest::where('status', 'approved')->count(),
            'scheduled' => CounselingRequest::whereIn('status', ['scheduled', 'awaiting_student_confirmation', 'reschedule_requested'])->count(),
            'completed' => CounselingRequest::where('status', 'completed')->count(),
            'rejected' => CounselingRequest::where('status', 'rejected')->count(),
            'cancelled' => CounselingRequest::where('status', 'cancelled')->count(),
        ];

        $classrooms = Classroom::orderBy('name')->get();
        $counselors = Counselor::with('user')->get();

        return view('panel.konseling.index', compact(
            'requests',
            'counters',
            'classrooms',
            'counselors',
            'status',
            'classroomId',
            'counselorId',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Detail Pengajuan, Catatan Murid & Timeline Riwayat
     */
    public function show(CounselingRequest $konseling): View
    {
        if (!$konseling->read_at) {
            $konseling->update(['read_at' => now()]);
        }

        $konseling->load([
            'student.classrooms',
            'guidanceField',
            'serviceType',
            'assignedCounselor.user',
            'schedule.counselor.user',
            'histories.actor',
        ]);

        $counselors = Counselor::with('user')->get();
        $serviceTypes = ServiceType::layanan()->get();
        $guidanceFields = GuidanceField::all();

        return view('panel.konseling.show', compact(
            'konseling',
            'counselors',
            'serviceTypes',
            'guidanceFields'
        ));
    }

    /**
     * Persetujuan / Penolakan / Permintaan Info Tambahan
     */
    public function updateStatus(Request $request, CounselingRequest $konseling): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected,needs_information,cancelled'],
            'notes' => ['required', 'string', 'max:1000'],
            'assigned_counselor_id' => ['nullable', 'exists:counselors,id'],
        ], [
            'notes.required' => 'Catatan respon resmi wajib diisi untuk rekam riwayat konseling.',
        ]);

        DB::transaction(function () use ($request, $konseling) {
            $oldStatus = $konseling->status;
            $newStatus = $request->input('status');

            $updateData = [
                'status' => $newStatus,
                'status_notes' => $request->input('notes'),
            ];

            if ($request->filled('assigned_counselor_id')) {
                $updateData['assigned_counselor_id'] = $request->input('assigned_counselor_id');
            }

            $konseling->update($updateData);

            // Record History
            CounselingRequestHistory::create([
                'counseling_request_id' => $konseling->id,
                'user_id' => Auth::id(),
                'status_from' => $oldStatus,
                'status_to' => $newStatus,
                'notes' => $request->input('notes'),
            ]);
        });

        return back()->with('success', 'Status pengajuan konseling berhasil diperbarui dan tersimpan dalam riwayat.');
    }

    /**
     * Penetapan / Penyetujuan Rencana Jadwal Konseling oleh Guru BK / Admin
     */
    public function schedule(Request $request, CounselingRequest $konseling): RedirectResponse
    {
        $method = $request->input('counseling_method', $konseling->counseling_method ?? 'tatap_muka');

        $request->validate([
            'counselor_id' => ['required', 'exists:counselors,id'],
            'scheduled_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'counseling_method' => ['nullable', 'in:tatap_muka,online_meet'],
            'location' => [$method === 'tatap_muka' ? 'required' : 'nullable', 'string', 'max:255'],
            'meeting_link' => [$method === 'online_meet' ? 'required' : 'nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $konseling, $method) {
            $location = $method === 'tatap_muka' ? $request->input('location', 'Ruang BK') : 'Daring (Google Meet)';
            $meetingLink = $method === 'online_meet' ? $request->input('meeting_link') : null;

            $schedule = $konseling->schedule()->updateOrCreate(
                ['counseling_request_id' => $konseling->id],
                [
                    'counselor_id' => $request->input('counselor_id'),
                    'counseling_method' => $method,
                    'scheduled_date' => $request->input('scheduled_date'),
                    'start_time' => $request->input('start_time'),
                    'end_time' => $request->input('end_time'),
                    'location' => $location,
                    'meeting_link' => $meetingLink,
                    'schedule_status' => 'scheduled',
                    'student_confirmation' => 'confirmed',
                    'notes' => $request->input('notes'),
                ]
            );

            $oldStatus = $konseling->status;
            $konseling->update([
                'status' => 'scheduled',
                'counseling_method' => $method,
                'assigned_counselor_id' => $request->input('counselor_id'),
            ]);

            $methodText = $method === 'online_meet' ? "Online Google Meet: {$meetingLink}" : "Tatap Muka di {$location}";
            CounselingRequestHistory::create([
                'counseling_request_id' => $konseling->id,
                'user_id' => Auth::id(),
                'status_from' => $oldStatus,
                'status_to' => 'scheduled',
                'notes' => "Rencana jadwal konseling disetujui pada {$schedule->scheduled_date->format('d/m/Y')} pukul {$schedule->start_time} - {$schedule->end_time} via {$methodText}.",
            ]);
        });

        return back()->with('success', 'Rencana jadwal konseling berhasil disetujui dan ditetapkan. Status pada murid kini "Disetujui" dan kartu konseling dapat diunduh.');
    }

    /**
     * Tandai Sesi Konseling Sudah Tertangani (Selesai) & Realisasi Layanan BK
     */
    public function complete(Request $request, CounselingRequest $konseling): RedirectResponse
    {
        $request->validate([
            'evaluation_result' => ['required', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'activity_description' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $konseling) {
            $oldStatus = $konseling->status;
            $evaluation = $request->input('evaluation_result');
            $activityDesc = $request->input('activity_description') ?: ('Pelaksanaan sesi konseling mengenai ' . $konseling->purpose);

            $konseling->update([
                'status' => 'completed',
                'resolution_notes' => $evaluation,
                'completed_at' => now(),
            ]);

            if ($konseling->schedule) {
                $konseling->schedule->update([
                    'schedule_status' => 'completed',
                ]);
            }

            // Realisasi ke CounselingService
            $activeSemester = active_semester();
            CounselingService::create([
                'service_type_id' => $konseling->service_type_id ?? ServiceType::layanan()->first()?->id,
                'guidance_field_id' => $konseling->guidance_field_id ?? GuidanceField::first()?->id,
                'counselor_id' => $konseling->assigned_counselor_id ?? Counselor::first()?->id,
                'academic_year_id' => $activeSemester?->academic_year_id,
                'semester_id' => $activeSemester?->id,
                'counseling_request_id' => $konseling->id,
                'title' => 'Layanan Konseling: ' . $konseling->purpose,
                'target_type' => 'individual',
                'target_student_id' => $konseling->student_id,
                'participant_count' => 1,
                'execution_date' => $konseling->schedule?->scheduled_date ?? now()->toDateString(),
                'duration_minutes' => $request->input('duration_minutes', 45),
                'location' => $konseling->schedule?->location ?? 'Ruang BK',
                'activity_description' => $activityDesc,
                'evaluation_result' => $evaluation,
                'follow_up_plan' => null,
            ]);

            CounselingRequestHistory::create([
                'counseling_request_id' => $konseling->id,
                'user_id' => Auth::id(),
                'status_from' => $oldStatus,
                'status_to' => 'completed',
                'notes' => 'Sesi konseling telah dilaksanakan dan diverifikasi Sudah Tertangani oleh Guru BK/Admin.',
            ]);
        });

        return back()->with('success', 'Sesi konseling berhasil disetujui sebagai "Sudah Tertangani" dan laporan realisasi telah tercatat.');
    }

    /**
     * Cetak / Unduh PDF Kartu Bukti Layanan Konseling Resmi
     */
    public function printCard(CounselingRequest $konseling): View
    {
        $konseling->load([
            'student.classrooms',
            'assignedCounselor.user',
            'schedule.counselor.user',
            'guidanceField',
            'serviceType',
            'imsAssessment',
        ]);

        $student = $konseling->student;

        return view('student.konseling.card-pdf', compact('konseling', 'student'));
    }

    /**
     * Hapus Data Pengajuan Agenda Konseling
     */
    public function destroy(CounselingRequest $konseling): RedirectResponse
    {
        DB::transaction(function () use ($konseling) {
            // Hapus jadwal terkait jika ada
            if ($konseling->schedule) {
                $konseling->schedule->delete();
            }
            // Hapus layanan konseling yang tercatat
            CounselingService::where('counseling_request_id', $konseling->id)->delete();
            // Hapus riwayat tracking
            CounselingRequestHistory::where('counseling_request_id', $konseling->id)->delete();
            // Hapus request konseling
            $konseling->delete();
        });

        return back()->with('success', 'Agenda konseling ' . $konseling->ticket_number . ' berhasil dihapus dari sistem.');
    }

    /**
     * Cetak / Unduh Format PDF Laporan Pelaksanaan Layanan Konseling Individual (Khusus Admin/Guru BK)
     */
    public function printReport(CounselingRequest $konseling): View
    {
        $konseling->load([
            'student.classrooms',
            'assignedCounselor.user',
            'schedule.counselor.user',
            'guidanceField',
            'serviceType',
            'counselingService',
        ]);

        $student = $konseling->student;
        $counselor = $konseling->assignedCounselor ?? auth()->user()->counselor;
        $service = $konseling->counselingService;
        $schedule = $konseling->schedule;

        // Hitung pertemuan ke berapa untuk siswa ini
        $meetingNumber = CounselingRequest::where('student_id', $student->id)
            ->where('status', 'completed')
            ->where('id', '<=', $konseling->id)
            ->count();
        if ($meetingNumber === 0) {
            $meetingNumber = 1;
        }

        return view('panel.konseling.laporan-individual-pdf', compact(
            'konseling',
            'student',
            'counselor',
            'service',
            'schedule',
            'meetingNumber'
        ));
    }
}
