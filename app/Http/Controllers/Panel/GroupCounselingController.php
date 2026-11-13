<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\GroupCounseling;
use App\Models\GroupCounselingRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GroupCounselingController extends Controller
{
    /**
     * Daftar Layanan Bimbingan & Konseling Kelompok (Panel Admin & Guru BK)
     */
    public function index(Request $request): View
    {
        // 1. Antrean Pengajuan Mandiri Siswa (Status: Menunggu Pengelompokan)
        $pendingRequests = GroupCounselingRequest::with(['student.classrooms', 'academicYear', 'semester'])
            ->where('status', 'menunggu_kelompok')
            ->latest()
            ->paginate(10, ['*'], 'req_page');

        // 2. Daftar Sesi Kelompok Terbentuk / Terjadwal
        $query = GroupCounseling::with([
            'members.classrooms',
            'counselor.user',
            'requests.student'
        ])->latest();

        // Filter Jenis Layanan
        if ($request->filled('type')) {
            $query->where('service_type', $request->input('type'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Pencarian
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhereHas('members', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        $counters = [
            'total' => GroupCounseling::count(),
            'pending_requests' => GroupCounselingRequest::where('status', 'menunggu_kelompok')->count(),
            'bimbingan' => GroupCounseling::where('service_type', 'bimbingan_kelompok')->count(),
            'konseling' => GroupCounseling::where('service_type', 'konseling_kelompok')->count(),
            'scheduled' => GroupCounseling::where('status', 'dijadwalkan')->count(),
            'completed' => GroupCounseling::where('status', 'selesai')->count(),
        ];

        $groups = $query->paginate(15, ['*'], 'group_page')->withQueryString();
        $counselors = Counselor::with('user')->get();
        $allStudents = Student::with('classrooms')->where('status', 'aktif')->orderBy('name')->get();

        return view('panel.layanan-kelompok.index', compact(
            'pendingRequests',
            'groups',
            'counters',
            'counselors',
            'allStudents'
        ));
    }

    /**
     * Bentuk Kelompok Baru dari Pengajuan Siswa & Tetapkan Jadwal
     */
    public function formGroup(Request $request): RedirectResponse
    {
        $request->validate([
            'service_type' => 'required|in:bimbingan_kelompok,konseling_kelompok',
            'topic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'counselor_id' => 'required|exists:counselors,id',
            'scheduled_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'location' => 'required|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'request_ids' => 'nullable|array',
        ], [
            'service_type.required' => 'Pilih jenis layanan kelompok (BKP atau KKP).',
            'topic.required' => 'Topik bahasan kelompok harus diisi.',
            'student_ids.required' => 'Pilih minimal 1 orang siswa anggota kelompok.',
            'counselor_id.required' => 'Pilih Guru BK / Konselor pembimbing.',
            'scheduled_date.required' => 'Tentukan tanggal pelaksanaan sesi kelompok.',
            'start_time.required' => 'Tentukan jam mulai.',
            'end_time.required' => 'Tentukan jam selesai.',
            'location.required' => 'Tentukan tempat / ruangan pelaksanaan.',
        ]);

        $activeSemester = active_semester();
        $ticket = GroupCounseling::generateTicketNumber($request->input('service_type'));
        $studentIds = $request->input('student_ids');

        $group = DB::transaction(function () use ($request, $ticket, $activeSemester, $studentIds) {
            $grp = GroupCounseling::create([
                'ticket_number' => $ticket,
                'service_type' => $request->input('service_type'),
                'leader_student_id' => $studentIds[0] ?? null,
                'academic_year_id' => $activeSemester?->academic_year_id,
                'semester_id' => $activeSemester?->id,
                'topic' => $request->input('topic'),
                'description' => $request->input('description') ?? 'Sesi kelompok terarah dibentuk oleh Guru BK berdasarkan pengajuan siswa.',
                'group_goal' => $request->input('group_goal'),
                'status' => 'dijadwalkan',
                'counselor_id' => $request->input('counselor_id'),
                'scheduled_date' => $request->input('scheduled_date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'location' => $request->input('location'),
                'meeting_link' => $request->input('meeting_link'),
                'counselor_notes' => $request->input('counselor_notes'),
            ]);

            // Hubungkan seluruh siswa ke tabel group_counseling_members
            $grp->members()->syncWithoutDetaching($studentIds);

            // Perbarui status pengajuan mandiri siswa terkait menjadi 'dikelompokkan'
            $requestIds = $request->input('request_ids', []);
            if (!empty($requestIds)) {
                GroupCounselingRequest::whereIn('id', $requestIds)
                    ->update([
                        'status' => 'dikelompokkan',
                        'group_counseling_id' => $grp->id,
                    ]);
            }

            // Juga perbarui pengajuan pending milik student_ids yang topiknya cocok
            GroupCounselingRequest::whereIn('student_id', $studentIds)
                ->where('status', 'menunggu_kelompok')
                ->where('service_type', $request->input('service_type'))
                ->update([
                    'status' => 'dikelompokkan',
                    'group_counseling_id' => $grp->id,
                ]);

            return $grp;
        });

        return redirect()->route('panel.layanan-kelompok.show', $group)
            ->with('success', "Kelompok {$group->service_type_label} berhasil dibentuk dan dijadwalkan! Notifikasi beserta jadwal dan daftar seluruh anggota kelompok otomatis muncul di akun masing-masing murid.");
    }

    /**
     * Tambah Anggota Siswa ke Kelompok yang Sudah Ada
     */
    public function addMember(Request $request, GroupCounseling $layananKelompok): RedirectResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ], [
            'student_id.required' => 'Pilih siswa yang akan ditambahkan.',
        ]);

        $studentId = $request->input('student_id');
        $layananKelompok->members()->syncWithoutDetaching([$studentId]);

        // Hubungkan jika siswa ini punya request pending
        GroupCounselingRequest::where('student_id', $studentId)
            ->where('status', 'menunggu_kelompok')
            ->where('service_type', $layananKelompok->service_type)
            ->update([
                'status' => 'dikelompokkan',
                'group_counseling_id' => $layananKelompok->id,
            ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', 'Anggota siswa berhasil ditambahkan ke dalam kelompok.');
    }

    /**
     * Hapus Siswa dari Anggota Kelompok
     */
    public function removeMember(GroupCounseling $layananKelompok, Student $student): RedirectResponse
    {
        $layananKelompok->members()->detach($student->id);

        // Reset status request siswa ini jika terhubung
        GroupCounselingRequest::where('student_id', $student->id)
            ->where('group_counseling_id', $layananKelompok->id)
            ->update([
                'status' => 'menunggu_kelompok',
                'group_counseling_id' => null,
            ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', "Siswa {$student->name} berhasil dikeluarkan dari kelompok.");
    }

    /**
     * Detail Sesi Kelompok & Formulir Penjadwalan / Respon
     */
    public function show(GroupCounseling $layananKelompok): View
    {
        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester',
            'requests.student.classrooms'
        ]);

        $counselors = Counselor::with('user')->get();
        $allStudents = Student::with('classrooms')
            ->where('status', 'aktif')
            ->whereNotIn('id', $group->members->pluck('id')->toArray())
            ->orderBy('name')
            ->get();

        return view('panel.layanan-kelompok.show', compact('group', 'counselors', 'allStudents'));
    }

    /**
     * Konfirmasi & Tetapkan Jadwal Sesi Kelompok
     */
    public function schedule(Request $request, GroupCounseling $layananKelompok): RedirectResponse
    {
        $request->validate([
            'counselor_id' => 'required|exists:counselors,id',
            'scheduled_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'location' => 'required|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'counselor_notes' => 'nullable|string',
        ], [
            'counselor_id.required' => 'Pilih Guru BK / Konselor pembimbing.',
            'scheduled_date.required' => 'Tentukan tanggal pelaksanaan sesi kelompok.',
            'start_time.required' => 'Tentukan jam mulai.',
            'end_time.required' => 'Tentukan jam selesai.',
            'location.required' => 'Tentukan tempat / ruangan pelaksanaan.',
        ]);

        $layananKelompok->update([
            'counselor_id' => $request->input('counselor_id'),
            'scheduled_date' => $request->input('scheduled_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'location' => $request->input('location'),
            'meeting_link' => $request->input('meeting_link'),
            'counselor_notes' => $request->input('counselor_notes'),
            'status' => 'dijadwalkan',
        ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', 'Jadwal sesi ' . $layananKelompok->service_type_label . ' berhasil ditetapkan dan notifikasi otomatis tampil di akun seluruh siswa anggota.');
    }

    /**
     * Selesaikan Sesi Kelompok & Simpan Laporan Hasil / Evaluasi
     */
    public function complete(Request $request, GroupCounseling $layananKelompok): RedirectResponse
    {
        $request->validate([
            'duration_minutes' => 'required|integer|min:10|max:300',
            'counselor_notes' => 'required|string',
            'evaluation_result' => 'required|string',
            'follow_up_plan' => 'nullable|string',
        ], [
            'duration_minutes.required' => 'Durasi sesi kelompok harus diisi.',
            'counselor_notes.required' => 'Ringkasan dinamika kelompok harus diisi.',
            'evaluation_result.required' => 'Hasil evaluasi dan komitmen kelompok harus diisi.',
        ]);

        $layananKelompok->update([
            'duration_minutes' => $request->input('duration_minutes'),
            'counselor_notes' => $request->input('counselor_notes'),
            'evaluation_result' => $request->input('evaluation_result'),
            'follow_up_plan' => $request->input('follow_up_plan'),
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', 'Sesi ' . $layananKelompok->service_type_label . ' berhasil diselesaikan dan laporan evaluasi telah tersimpan.');
    }

    /**
     * Hapus Data Pengajuan Kelompok
     */
    public function destroy(GroupCounseling $layananKelompok): RedirectResponse
    {
        $ticket = $layananKelompok->ticket_number;

        // Reset request jika ada
        GroupCounselingRequest::where('group_counseling_id', $layananKelompok->id)
            ->update([
                'status' => 'menunggu_kelompok',
                'group_counseling_id' => null,
            ]);

        $layananKelompok->delete();

        return redirect()->route('panel.layanan-kelompok.index')
            ->with('success', 'Data sesi kelompok tiket ' . $ticket . ' berhasil dihapus.');
    }

    /**
     * Hapus Pengajuan Mandiri Siswa
     */
    public function destroyRequest(GroupCounselingRequest $groupCounselingRequest): RedirectResponse
    {
        $ticket = $groupCounselingRequest->ticket_number;
        $groupCounselingRequest->delete();

        return redirect()->route('panel.layanan-kelompok.index')
            ->with('success', 'Pengajuan mandiri siswa tiket ' . $ticket . ' berhasil dihapus.');
    }

    /**
     * Cetak Berita Acara / Laporan Hasil Layanan Kelompok PDF
     */
    public function printReport(GroupCounseling $layananKelompok): View
    {
        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester'
        ]);

        return view('panel.layanan-kelompok.laporan-kelompok-pdf', compact('group'));
    }
}
