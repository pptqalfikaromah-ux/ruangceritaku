<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\HomeroomComplaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WaliKelasAduanController extends Controller
{
    /**
     * Tampilkan daftar aduan kasus murid yang diajukan Wali Kelas
     */
    public function index(Request $request): View
    {
        $teacher = Auth::user()->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;

        $query = HomeroomComplaint::with(['student', 'handledByCounselor'])
            ->where('teacher_id', $teacher?->id ?? 0);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $complaints = $query->latest('incident_date')->paginate(15)->withQueryString();

        return view('walikelas.aduan.index', compact('classroom', 'complaints'));
    }

    /**
     * Form pengaduan insidental kasus murid binaan
     */
    public function create(): View|RedirectResponse
    {
        $teacher = Auth::user()->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;

        if (!$classroom) {
            return redirect()->route('walikelas.dashboard')
                ->with('error', 'Anda belum ditugaskan ke rombel kelas aktif. Silakan hubungi Administrator.');
        }

        $students = $classroom->students()->orderBy('name')->get();

        return view('walikelas.aduan.create', compact('classroom', 'students'));
    }

    /**
     * Simpan laporan aduan kasus murid oleh Wali Kelas
     */
    public function store(Request $request): RedirectResponse
    {
        $teacher = Auth::user()->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;

        if (!$classroom) {
            return back()->with('error', 'Rombel binaan tidak ditemukan.');
        }

        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'incident_date' => ['required', 'date'],
            'category' => ['required', 'in:kedisiplinan,pelanggaran_aturan,akademik,sosial_pertemanan,perilaku_sikap,bullying_konflik,lainnya'],
            'description' => ['required', 'string', 'min:5'],
        ], [
            'student_id.required' => 'Pilih murid yang terkait kasus.',
            'incident_date.required' => 'Tanggal kejadian wajib diisi.',
            'category.required' => 'Pilih kategori kasus / permasalahan.',
            'description.required' => 'Deskripsi kejadian wajib diisi.',
        ]);

        $student = $classroom->students()->find($validated['student_id']);
        $categoryLabels = [
            'kedisiplinan' => 'Kedisiplinan & Kerapian',
            'pelanggaran_aturan' => 'Pelanggaran Aturan',
            'akademik' => 'Akademik & Belajar',
            'sosial_pertemanan' => 'Sosial Pertemanan',
            'perilaku_sikap' => 'Perilaku & Sikap',
            'bullying_konflik' => 'Perundungan / Konflik',
            'lainnya' => 'Kasus Lainnya',
        ];
        $categoryTitle = $categoryLabels[$validated['category']] ?? 'Kasus Murid';
        $autoTitle = $categoryTitle . ' - ' . ($student?->name ?? 'Murid');

        HomeroomComplaint::create([
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'student_id' => $validated['student_id'],
            'academic_year_id' => $activeYear?->id,
            'semester_id' => $activeSemester?->id,
            'incident_date' => $validated['incident_date'],
            'category' => $validated['category'],
            'title' => $autoTitle,
            'description' => $validated['description'],
            'initial_action' => null,
            'teacher_notes' => null,
            'status' => 'menunggu_tindak_lanjut',
        ]);

        return redirect()->route('walikelas.aduan.index')
            ->with('success', 'Laporan kasus murid berhasil dikirimkan ke Guru BK.');
    }

    /**
     * Detail kasus aduan dan tanggapan dari Guru BK
     */
    public function show(HomeroomComplaint $aduan): View
    {
        $teacher = Auth::user()->teacher;
        abort_unless($aduan->teacher_id === $teacher?->id, 403, 'Akses terbatas.');

        $aduan->load(['student', 'classroom', 'handledByCounselor']);

        return view('walikelas.aduan.show', compact('aduan'));
    }
}
