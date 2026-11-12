<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GroupCounseling;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentGroupCounselingController extends Controller
{
    /**
     * Riwayat & Daftar Sesi Kelompok Siswa
     */
    public function index(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $groups = GroupCounseling::with(['leader.classrooms', 'members.classrooms', 'counselor.user'])
            ->where(function ($q) use ($student) {
                $q->where('leader_student_id', $student->id)
                  ->orWhereHas('members', function ($sq) use ($student) {
                      $sq->where('student_id', $student->id);
                  });
            })
            ->latest()
            ->paginate(10);

        return view('student.layanan-kelompok.index', compact('groups', 'student'));
    }

    /**
     * Formulir Pengajuan Bimbingan / Konseling Kelompok Baru
     */
    public function create(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        // Ambil daftar teman siswa lainnya yang berstatus aktif
        $classmates = Student::with('classrooms')
            ->where('id', '!=', $student->id)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('student.layanan-kelompok.create', compact('student', 'classmates'));
    }

    /**
     * Simpan Pengajuan Sesi Kelompok
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $request->validate([
            'service_type' => 'required|in:bimbingan_kelompok,konseling_kelompok',
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'group_goal' => 'nullable|string',
            'member_ids' => 'required|array|min:1|max:12',
            'member_ids.*' => 'exists:students,id',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_session' => 'nullable|string|max:100',
        ], [
            'service_type.required' => 'Pilih jenis layanan (BKP atau KKP).',
            'topic.required' => 'Topik bahasan kelompok harus diisi.',
            'description.required' => 'Latar belakang / alasan kebutuhan kelompok harus dijelaskan.',
            'member_ids.required' => 'Pilih minimal 1 orang teman untuk menjadi anggota kelompok.',
            'member_ids.min' => 'Pilih minimal 1 orang teman anggota kelompok.',
            'member_ids.max' => 'Anggota kelompok maksimal berjumlah 12 orang.',
            'preferred_date.after_or_equal' => 'Usulan tanggal pelaksanaan tidak boleh tanggal yang sudah lewat.',
        ]);

        $activeSemester = active_semester();
        $ticket = GroupCounseling::generateTicketNumber($request->input('service_type'));

        $group = DB::transaction(function () use ($request, $student, $activeSemester, $ticket) {
            $record = GroupCounseling::create([
                'ticket_number' => $ticket,
                'service_type' => $request->input('service_type'),
                'leader_student_id' => $student->id,
                'academic_year_id' => $activeSemester?->academic_year_id,
                'semester_id' => $activeSemester?->id,
                'topic' => $request->input('topic'),
                'description' => $request->input('description'),
                'group_goal' => $request->input('group_goal'),
                'preferred_date' => $request->input('preferred_date'),
                'preferred_session' => $request->input('preferred_session'),
                'status' => 'menunggu_persetujuan',
            ]);

            // Filter agar ketua tidak masuk dua kali di tabel anggota
            $memberIds = array_diff($request->input('member_ids', []), [$student->id]);
            $record->members()->sync($memberIds);

            return $record;
        });

        return redirect()->route('murid.layanan-kelompok.show', $group)
            ->with('success', 'Pengajuan ' . $group->service_type_label . ' berhasil dikirim dengan tiket ' . $group->ticket_number . '. Guru BK akan meninjau dan mengonfirmasi jadwal kelompokmu.');
    }

    /**
     * Detail Pengajuan & Kartu Sesi Kelompok Siswa
     */
    public function show(GroupCounseling $layananKelompok): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        // Pastikan siswa adalah ketua atau salah satu anggota
        $isMember = $layananKelompok->leader_student_id === $student->id ||
                    $layananKelompok->members()->where('student_id', $student->id)->exists();

        abort_unless($isMember, 403, 'Kamu tidak memiliki akses ke tiket kelompok ini.');

        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester'
        ]);

        return view('student.layanan-kelompok.show', compact('group', 'student'));
    }

    /**
     * Cetak Kartu Bukti Layanan Kelompok Siswa (PDF / Print View)
     */
    public function printCard(GroupCounseling $layananKelompok): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $isMember = $layananKelompok->leader_student_id === $student->id ||
                    $layananKelompok->members()->where('student_id', $student->id)->exists();

        abort_unless($isMember, 403);

        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester'
        ]);

        return view('student.layanan-kelompok.card-pdf', compact('group', 'student'));
    }
}

