<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\HomeroomAssignment;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeroomAssignmentController extends Controller
{
    /**
     * Tampilkan halaman penugasan Wali Kelas ke Rombel
     */
    public function index(): View
    {
        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        $classrooms = Classroom::with(['currentHomeroomAssignment.teacher.user'])
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();

        return view('panel.penugasan-wali-kelas.index', compact(
            'classrooms',
            'teachers',
            'activeSemester',
            'activeYear'
        ) + ['activeSem' => $activeSemester]);
    }

    /**
     * Simpan / Perbarui penugasan Wali Kelas ke Rombel
     */
    public function store(Request $request): RedirectResponse
    {
        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        if (!$activeSemester || !$activeYear) {
            return back()->with('error', 'Tidak ada semester aktif. Silakan aktifkan tahun ajaran & semester terlebih dahulu.');
        }

        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        HomeroomAssignment::updateOrCreate(
            [
                'academic_year_id' => $activeYear->id,
                'semester_id' => $activeSemester->id,
                'classroom_id' => $validated['classroom_id'],
            ],
            [
                'teacher_id' => $validated['teacher_id'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        // Update juga nama wali kelas di kolom homeroom_teacher tabel classrooms jika perlu
        $teacher = Teacher::find($validated['teacher_id']);
        if ($teacher) {
            Classroom::where('id', $validated['classroom_id'])->update([
                'homeroom_teacher' => $teacher->name,
            ]);
        }

        return redirect()->route('panel.penugasan-wali-kelas.index')->with('success', 'Penugasan Wali Kelas berhasil disimpan.');
    }

    /**
     * Hapus penugasan Wali Kelas dari rombel
     */
    public function destroy(HomeroomAssignment $penugasanWaliKela): RedirectResponse
    {
        $penugasanWaliKela->delete();

        return redirect()->route('panel.penugasan-wali-kelas.index')->with('success', 'Penugasan Wali Kelas berhasil dihapus.');
    }
}
