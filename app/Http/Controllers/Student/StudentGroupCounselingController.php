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

        // Tentukan filter tipe yang aktif secara tegas satu-per-satu
        $filterType = $request->input('type');
        if (!$filterType) {
            $filterType = $bkpEnabled ? 'bimbingan_kelompok' : 'konseling_kelompok';
        }

        // Validasi ketersediaan fitur
        if ($filterType === 'bimbingan_kelompok' && !$bkpEnabled) {
            return redirect()->route('murid.layanan-kelompok.index', ['type' => 'konseling_kelompok'])
                ->with('warning', 'Layanan Bimbingan Kelompok (BKP) sedang dinonaktifkan oleh sekolah.');
        }
        if ($filterType === 'konseling_kelompok' && !$kkpEnabled) {
            return redirect()->route('murid.layanan-kelompok.index', ['type' => 'bimbingan_kelompok'])
                ->with('warning', 'Layanan Konseling Kelompok (KKP) sedang dinonaktifkan oleh sekolah.');
        }

        // Cek pengajuan aktif siswa yang masih menunggu pengelompokan
        $pendingRequest = GroupCounselingRequest::where('student_id', $student->id)
            ->where('service_type', $filterType)
            ->where('status', 'menunggu_kelompok')
            ->latest()
            ->first();

        // 1. Ambil sesi kelompok terjadwal / aktif di mana siswa terdaftar sebagai anggota
        $activeGroups = GroupCounseling::with(['members.classrooms', 'counselor.user'])
            ->whereHas('members', function ($q) use ($student) {
                $q->where('students.id', $student->id);
            })
            ->where('service_type', $filterType)
            ->latest()
            ->get();

        // 2. Ambil riwayat pengajuan mandiri siswa untuk tipe ini
        $myRequests = GroupCounselingRequest::with(['groupCounseling.members.classrooms', 'groupCounseling.counselor.user'])
            ->where('student_id', $student->id)
            ->where('service_type', $filterType)
            ->latest()
            ->paginate(10);

        return view('student.layanan-kelompok.index', compact(
            'student',
            'bkpEnabled',
            'kkpEnabled',
            'filterType',
            'pendingRequest',
            'activeGroups',
            'myRequests'
        ));
    }

    /**
     * Formulir Pengajuan Bimbingan / Konseling Kelompok Mandiri
     * (Dialihkan langsung ke halaman utama layanan yang dipilih untuk 1-klik pengajuan)
     */
    public function create(Request $request): RedirectResponse
    {
        $selectedType = $request->input('type', 'bimbingan_kelompok');
        return redirect()->route('murid.layanan-kelompok.index', ['type' => $selectedType]);
    }

    /**
     * Simpan Pengajuan Mandiri Sesi Kelompok Siswa (Cukup 1 Klik Tanpa Input Rumit)
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $bkpEnabled = app_setting('bkp_enabled', '1') === '1';
        $kkpEnabled = app_setting('kkp_enabled', '1') === '1';

        $serviceType = $request->input('service_type', 'bimbingan_kelompok');
        if ($serviceType === 'bimbingan_kelompok' && !$bkpEnabled) {
            return back()->with('error', 'Layanan Bimbingan Kelompok (BKP) sedang dinonaktifkan oleh sekolah.');
        }
        if ($serviceType === 'konseling_kelompok' && !$kkpEnabled) {
            return back()->with('error', 'Layanan Konseling Kelompok (KKP) sedang dinonaktifkan oleh sekolah.');
        }

        // Cek jika siswa sudah mengajukan dan statusnya masih menunggu_kelompok
        $existing = GroupCounselingRequest::where('student_id', $student->id)
            ->where('service_type', $serviceType)
            ->where('status', 'menunggu_kelompok')
            ->first();

        if ($existing) {
            return redirect()->route('murid.layanan-kelompok.index', ['type' => $serviceType])
                ->with('info', 'Anda sudah memiliki pengajuan aktif yang sedang diproses oleh Guru BK.');
        }

        $activeSemester = active_semester();
        $ticket = GroupCounselingRequest::generateTicketNumber($serviceType);

        $defaultTopic = $serviceType === 'konseling_kelompok'
            ? 'Pengajuan Konseling Kelompok (KKP)'
            : 'Pengajuan Bimbingan Kelompok (BKP)';

        $defaultDesc = 'Pengajuan mandiri dari siswa. Waktu, tempat, dan pembagian kelompok ditentukan oleh Guru BK.';

        GroupCounselingRequest::create([
            'ticket_number' => $ticket,
            'student_id' => $student->id,
            'academic_year_id' => $activeSemester?->academic_year_id,
            'semester_id' => $activeSemester?->id,
            'service_type' => $serviceType,
            'topic' => $request->input('topic', $defaultTopic) ?: $defaultTopic,
            'description' => $request->input('description', $defaultDesc) ?: $defaultDesc,
            'preferred_date' => $request->input('preferred_date'),
            'preferred_session' => $request->input('preferred_session') ?: 'Ditentukan Guru BK',
            'status' => 'menunggu_kelompok',
        ]);

        $serviceLabel = $serviceType === 'konseling_kelompok' ? 'Konseling Kelompok (KKP)' : 'Bimbingan Kelompok (BKP)';

        return redirect()->route('murid.layanan-kelompok.index', ['type' => $serviceType])
            ->with('success', "Pengajuan mandiri {$serviceLabel} berhasil dikirim! Guru BK akan menyatukanmu ke dalam kelompok dan menetapkan jadwal serta ruangannya.");
    }

    /**
     * Batalkan Pengajuan Mandiri Siswa
     */
    public function cancelRequest(GroupCounselingRequest $groupCounselingRequest): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        if ($groupCounselingRequest->student_id !== $student->id) {
            abort(403, 'Anda tidak memiliki hak untuk membatalkan pengajuan ini.');
        }

        if ($groupCounselingRequest->status !== 'menunggu_kelompok') {
            return back()->with('error', 'Pengajuan ini tidak dapat dibatalkan karena telah diproses/dijadwalkan oleh Guru BK.');
        }

        $serviceType = $groupCounselingRequest->service_type;
        $groupCounselingRequest->delete();

        return redirect()->route('murid.layanan-kelompok.index', ['type' => $serviceType])
            ->with('success', 'Pengajuan mandiri Anda berhasil dibatalkan.');
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
