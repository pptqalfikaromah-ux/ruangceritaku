<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GroupCounseling;
use App\Models\GroupCounselingRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentGroupCounselingController extends Controller
{
    /**
     * Riwayat & Daftar Sesi Kelompok Siswa
     */
    public function index(Request $request): View|RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $bkpEnabled = app_setting('bkp_enabled', '1') === '1';
        $kkpEnabled = app_setting('kkp_enabled', '1') === '1';

        // Jika kedua fitur dinonaktifkan
        if (!$bkpEnabled && !$kkpEnabled) {
            return redirect()->route('murid.dashboard')
                ->with('error', 'Layanan Bimbingan Kelompok dan Konseling Kelompok sedang dinonaktifkan oleh pihak sekolah.');
        }

        // Tentukan filter tipe yang aktif
        $filterType = $request->input('type');
        if ($filterType === 'bimbingan_kelompok' && !$bkpEnabled) {
            $filterType = $kkpEnabled ? 'konseling_kelompok' : null;
        } elseif ($filterType === 'konseling_kelompok' && !$kkpEnabled) {
            $filterType = $bkpEnabled ? 'bimbingan_kelompok' : null;
        }

        // 1. Ambil sesi kelompok terjadwal / aktif di mana siswa terdaftar sebagai anggota
        $activeGroups = GroupCounseling::with(['members.classrooms', 'counselor.user'])
            ->whereHas('members', function ($q) use ($student) {
                $q->where('students.id', $student->id);
            })
            ->when($filterType, function ($q, $t) {
                $q->where('service_type', $t);
            })
            ->latest()
            ->get();

        // 2. Ambil riwayat pengajuan mandiri siswa (menunggu pengelompokan atau sudah dikelompokkan)
        $myRequests = GroupCounselingRequest::with(['groupCounseling.members.classrooms', 'groupCounseling.counselor.user'])
            ->where('student_id', $student->id)
            ->when($filterType, function ($q, $t) {
                $q->where('service_type', $t);
            })
            ->latest()
            ->paginate(10);

        return view('student.layanan-kelompok.index', compact(
            'student',
            'bkpEnabled',
            'kkpEnabled',
            'filterType',
            'activeGroups',
            'myRequests'
        ));
    }

    /**
     * Formulir Pengajuan Bimbingan / Konseling Kelompok Mandiri
     */
    public function create(Request $request): View|RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $bkpEnabled = app_setting('bkp_enabled', '1') === '1';
        $kkpEnabled = app_setting('kkp_enabled', '1') === '1';

        if (!$bkpEnabled && !$kkpEnabled) {
            return redirect()->route('murid.dashboard')
                ->with('error', 'Layanan Kelompok sedang dinonaktifkan sementara oleh Guru BK.');
        }

        // Tentukan pilihan default layanan
        $selectedType = $request->input('type');
        if (!$selectedType || ($selectedType === 'bimbingan_kelompok' && !$bkpEnabled)) {
            $selectedType = $kkpEnabled ? 'konseling_kelompok' : 'bimbingan_kelompok';
        }

        return view('student.layanan-kelompok.create', compact('student', 'bkpEnabled', 'kkpEnabled', 'selectedType'));
    }

    /**
     * Simpan Pengajuan Mandiri Sesi Kelompok Siswa
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $bkpEnabled = app_setting('bkp_enabled', '1') === '1';
        $kkpEnabled = app_setting('kkp_enabled', '1') === '1';

        $serviceType = $request->input('service_type', 'bimbingan_kelompok');
        if ($serviceType === 'bimbingan_kelompok' && !$bkpEnabled) {
            return back()->withInput()->with('error', 'Layanan Bimbingan Kelompok (BKP) sedang dinonaktifkan oleh sekolah.');
        }
        if ($serviceType === 'konseling_kelompok' && !$kkpEnabled) {
            return back()->withInput()->with('error', 'Layanan Konseling Kelompok (KKP) sedang dinonaktifkan oleh sekolah.');
        }

        $request->validate([
            'service_type' => 'required|in:bimbingan_kelompok,konseling_kelompok',
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_session' => 'nullable|string|max:100',
        ], [
            'service_type.required' => 'Pilih jenis layanan (BKP atau KKP).',
            'topic.required' => 'Topik bahasan yang ingin kamu ikuti harus diisi.',
            'description.required' => 'Alasan atau kebutuhan bimbingan harus dijelaskan.',
            'preferred_date.after_or_equal' => 'Usulan tanggal pelaksanaan tidak boleh tanggal yang sudah lewat.',
        ]);

        $activeSemester = active_semester();
        $ticket = GroupCounselingRequest::generateTicketNumber($serviceType);

        GroupCounselingRequest::create([
            'ticket_number' => $ticket,
            'student_id' => $student->id,
            'academic_year_id' => $activeSemester?->academic_year_id,
            'semester_id' => $activeSemester?->id,
            'service_type' => $serviceType,
            'topic' => $request->input('topic'),
            'description' => $request->input('description'),
            'preferred_date' => $request->input('preferred_date'),
            'preferred_session' => $request->input('preferred_session'),
            'status' => 'menunggu_kelompok',
        ]);

        $serviceLabel = $serviceType === 'konseling_kelompok' ? 'Konseling Kelompok (KKP)' : 'Bimbingan Kelompok (BKP)';

        return redirect()->route('murid.layanan-kelompok.index', ['type' => $serviceType])
            ->with('success', "Pengajuan mandiri {$serviceLabel} berhasil dikirim! Guru BK akan mengelompokkan Anda dengan teman yang memiliki topik serupa dan menetapkan jadwalnya.");
    }

    /**
     * Tampilkan Detail Sesi Kelompok Terjadwal Beserta Anggota
     */
    public function show(GroupCounseling $layananKelompok): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        // Pastikan siswa ini memang terdaftar di kelompok ini
        $isMember = $layananKelompok->members()->where('students.id', $student->id)->exists()
            || $layananKelompok->leader_student_id === $student->id
            || $layananKelompok->requests()->where('student_id', $student->id)->exists();

        abort_unless($isMember, 403, 'Anda tidak terdaftar sebagai peserta dalam kelompok ini.');

        $layananKelompok->load(['members.classrooms', 'counselor.user', 'leader.classrooms']);

        return view('student.layanan-kelompok.show', [
            'group' => $layananKelompok,
            'student' => $student,
        ]);
    }

    /**
     * Cetak Kartu Undangan / Bukti Kelompok Terjadwal Siswa
     */
    public function printCard(GroupCounseling $layananKelompok): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $isMember = $layananKelompok->members()->where('students.id', $student->id)->exists()
            || $layananKelompok->leader_student_id === $student->id
            || $layananKelompok->requests()->where('student_id', $student->id)->exists();

        abort_unless($isMember, 403, 'Anda bukan anggota kelompok ini.');

        $layananKelompok->load(['members.classrooms', 'counselor.user', 'academicYear', 'semester']);

        return view('student.layanan-kelompok.card-pdf', [
            'group' => $layananKelompok,
            'student' => $student,
        ]);
    }
}
