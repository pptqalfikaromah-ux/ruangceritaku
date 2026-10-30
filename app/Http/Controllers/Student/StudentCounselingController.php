<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\CounselingRequest;
use App\Models\CounselingRequestHistory;
use App\Models\GuidanceField;
use App\Models\ImsStatement;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentCounselingController extends Controller
{
    /**
     * Daftar Pengajuan Konseling Mandiri Murid
     */
    public function index(Request $request): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $query = CounselingRequest::with(['guidanceField', 'serviceType', 'assignedCounselor', 'schedule'])
            ->where('student_id', $student->id);

        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        $requests = $query->latest()->paginate(10);

        $counts = [
            'all' => CounselingRequest::where('student_id', $student->id)->count(),
            'pribadi' => CounselingRequest::where('student_id', $student->id)->where('source', 'pribadi')->count(),
            'ims' => CounselingRequest::where('student_id', $student->id)->where('source', 'ims')->count(),
        ];

        return view('student.konseling.index', compact('requests', 'counts'));
    }

    /**
     * Pengajuan Konseling Mandiri / Pribadi
     */
    public function ajukan(): RedirectResponse
    {
        return redirect()->route('murid.konseling.create');
    }

    /**
     * Formulir Pengajuan Konseling Pribadi (Mandiri)
     */
    public function create(Request $request): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $guidanceFields = GuidanceField::all();
        $serviceTypes = ServiceType::layanan()->get();
        $counselors = Counselor::with('user')->get();
        $allImsStatements = ImsStatement::with(['guidanceField', 'serviceType'])
            ->where('is_active', true)
            ->orderBy('statement_number')
            ->get();

        return view('student.konseling.create', compact('student', 'guidanceFields', 'serviceTypes', 'counselors', 'allImsStatements'));
    }

    /**
     * Simpan Pengajuan Konseling Pribadi (Mandiri) Murid
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $request->validate([
            'purpose' => ['required', 'string', 'max:255'],
            'counseling_method' => ['nullable', 'in:tatap_muka,online_meet'],
            'guidance_field_id' => ['nullable', 'exists:guidance_fields,id'],
            'service_type_id' => ['nullable', 'exists:service_types,id'],
            'is_service_unknown' => ['nullable', 'boolean'],
            'description' => ['required', 'string', 'max:2000'],
            'preferred_schedule' => ['nullable', 'string', 'max:255'],
            'additional_notes' => ['nullable', 'string', 'max:1000'],
            'counselor_id' => ['nullable', 'exists:counselors,id'],
            'assigned_counselor_id' => ['nullable', 'exists:counselors,id'],
            'attached_statements' => ['nullable', 'array'],
            'attached_statements.*' => ['integer', 'exists:ims_statements,id'],
        ]);

        // Pencegahan pengajuan ganda untuk topik yang masih berstatus pending / needs_information
        $hasDuplicatePending = CounselingRequest::where('student_id', $student->id)
            ->where('source', 'pribadi')
            ->whereIn('status', ['pending', 'needs_information'])
            ->where('purpose', $request->input('purpose'))
            ->exists();

        if ($hasDuplicatePending) {
            return back()->withInput()->with('error', 'Anda sudah memiliki permohonan konseling pribadi dengan tujuan yang sama yang sedang menunggu peninjauan.');
        }

        // Generate Ticket Number: AGD-YYYYMMDD-XXXX
        $dateStr = date('Ymd');
        $randomSeq = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        $ticketNumber = "AGD-{$dateStr}-{$randomSeq}";

        $counselorId = $request->input('counselor_id') ?? $request->input('assigned_counselor_id');
        $attachedStatements = $request->input('attached_statements', []);

        DB::transaction(function () use ($request, $student, $ticketNumber, $counselorId, $attachedStatements) {
            $counseling = CounselingRequest::create([
                'ticket_number' => $ticketNumber,
                'source' => 'pribadi',
                'student_id' => $student->id,
                'ims_assessment_id' => null,
                'purpose' => $request->input('purpose'),
                'counseling_method' => $request->input('counseling_method', 'tatap_muka'),
                'guidance_field_id' => $request->input('guidance_field_id'),
                'service_type_id' => $request->input('service_type_id'),
                'is_service_unknown' => $request->boolean('is_service_unknown'),
                'description' => $request->input('description'),
                'attached_ims_statements' => !empty($attachedStatements) ? array_map('intval', $attachedStatements) : null,
                'additional_notes' => $request->input('additional_notes'),
                'preferred_schedule' => $request->input('preferred_schedule'),
                'assigned_counselor_id' => $counselorId,
                'status' => 'pending',
            ]);

            CounselingRequestHistory::create([
                'counseling_request_id' => $counseling->id,
                'user_id' => Auth::id(),
                'status_from' => 'draft',
                'status_to' => 'pending',
                'notes' => 'Permohonan konseling pribadi (mandiri) berhasil diajukan oleh murid.',
            ]);
        });

        return redirect()->route('murid.konseling.index')
            ->with('success', "Permohonan Konseling Pribadi berhasil diajukan dengan nomor tiket #{$ticketNumber}. Guru BK akan segera meninjau permohonan Anda.");
    }

    /**
     * Detail Pengajuan & Respon Konselor
     */
    public function show(CounselingRequest $konseling): View
    {
        $student = Auth::user()->student;
        abort_unless($student && $konseling->student_id === $student->id, 403, 'Akses ditolak.');

        $konseling->load([
            'guidanceField',
            'serviceType',
            'assignedCounselor.user',
            'schedule.counselor.user',
            'histories.actor',
        ]);

        return view('student.konseling.show', compact('konseling'));
    }

    /**
     * Konfirmasi Kehadiran Jadwal oleh Murid
     */
    public function confirmSchedule(CounselingRequest $konseling): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student && $konseling->student_id === $student->id, 403);

        abort_unless($konseling->schedule, 400, 'Jadwal belum ditetapkan oleh Guru BK.');

        DB::transaction(function () use ($konseling) {
            $konseling->schedule->update([
                'student_confirmation' => 'confirmed',
            ]);

            $konseling->update([
                'status' => 'scheduled',
            ]);

            CounselingRequestHistory::create([
                'counseling_request_id' => $konseling->id,
                'user_id' => Auth::id(),
                'status_from' => 'awaiting_student_confirmation',
                'status_to' => 'scheduled',
                'notes' => 'Murid telah mengonfirmasi kehadiran untuk jadwal konseling ini.',
            ]);
        });

        return back()->with('success', 'Kehadiran Anda telah dikonfirmasi. Sampai jumpa di ruang BK sesuai jadwal!');
    }

    /**
     * Pengajuan Reschedule Jadwal oleh Murid
     */
    public function requestReschedule(Request $request, CounselingRequest $konseling): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student && $konseling->student_id === $student->id, 403);

        $request->validate([
            'reschedule_reason' => ['required', 'string', 'max:500'],
        ], [
            'reschedule_reason.required' => 'Mohon tuliskan alasan berhalangan hadir atau usulan waktu baru.',
        ]);

        DB::transaction(function () use ($request, $konseling) {
            $reason = $request->input('reschedule_reason');

            if ($konseling->schedule) {
                $konseling->schedule->update([
                    'student_confirmation' => 'reschedule_requested',
                    'reschedule_reason' => $reason,
                ]);
            }

            $oldStatus = $konseling->status;
            $konseling->update([
                'status' => 'reschedule_requested',
            ]);

            CounselingRequestHistory::create([
                'counseling_request_id' => $konseling->id,
                'user_id' => Auth::id(),
                'status_from' => $oldStatus,
                'status_to' => 'reschedule_requested',
                'notes' => "Murid mengajukan penyesuaian jadwal ulang dengan alasan: {$reason}",
            ]);
        });

        return back()->with('success', 'Permintaan jadwal ulang Anda telah disampaikan ke Guru BK.');
    }

    /**
     * Cetak / Unduh PDF Kartu Bukti Layanan Konseling Murid
     */
    public function printCard(CounselingRequest $konseling): View
    {
        $student = Auth::user()->student;
        abort_unless($student && $konseling->student_id === $student->id, 403, 'Akses kartu konseling tidak diizinkan.');

        $konseling->load([
            'student.classrooms',
            'assignedCounselor.user',
            'schedule.counselor.user',
            'guidanceField',
            'serviceType',
            'imsAssessment',
        ]);

        return view('student.konseling.card-pdf', compact('konseling', 'student'));
    }
}
