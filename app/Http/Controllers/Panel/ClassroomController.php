<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $academicYearId = $request->input('academic_year_id');
        $academicYears = AcademicYear::latest()->get();

        $query = Classroom::with(['academicYear'])
            ->withCount('students')
            ->binaan();

        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }

        $classrooms = $query->latest()->paginate(10)->withQueryString();

        return view('panel.rombel.index', compact('classrooms', 'academicYears', 'academicYearId'));
    }

    public function create(): View
    {
        $this->authorize('create', Classroom::class);
        $academicYears = AcademicYear::latest()->get();

        return view('panel.rombel.create', compact('academicYears'));
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {
        $this->authorize('create', Classroom::class);

        Classroom::create($request->validated());

        return redirect()->route('panel.rombel.index')
            ->with('success', 'Rombel/Kelas baru berhasil ditambahkan.');
    }

    public function show(Classroom $rombel): View
    {
        $this->authorize('view', $rombel);

        $rombel->load(['academicYear', 'currentAssignment.counselor']);
        $students = $rombel->students()->paginate(15);

        // Murid aktif yang belum ada di rombel tahun ajaran ini (untuk opsi tambah murid)
        $availableStudents = Student::where('status', 'aktif')
            ->whereDoesntHave('classrooms', function ($q) use ($rombel) {
                $q->where('classroom_student.academic_year_id', $rombel->academic_year_id);
            })
            ->orderBy('name')
            ->get();

        return view('panel.rombel.show', compact('rombel', 'students', 'availableStudents'));
    }

    public function edit(Classroom $rombel): View
    {
        $this->authorize('update', $rombel);
        $academicYears = AcademicYear::latest()->get();

        return view('panel.rombel.edit', compact('rombel', 'academicYears'));
    }

    public function update(ClassroomRequest $request, Classroom $rombel): RedirectResponse
    {
        $this->authorize('update', $rombel);

        $rombel->update($request->validated());

        return redirect()->route('panel.rombel.index')
            ->with('success', 'Data rombel berhasil diperbarui.');
    }

    public function destroy(Classroom $rombel): RedirectResponse
    {
        $this->authorize('delete', $rombel);

        if ($rombel->students()->count() > 0) {
            return back()->with('error', 'Rombel tidak dapat dihapus karena masih memiliki anggota murid.');
        }

        $rombel->delete();

        return redirect()->route('panel.rombel.index')
            ->with('success', 'Rombel berhasil dihapus.');
    }

    public function addStudent(Request $request, Classroom $rombel): RedirectResponse
    {
        $this->authorize('manageStudents', $rombel);

        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $student = Student::findOrFail($request->student_id);

        // Lepaskan dari rombel lain di tahun ajaran yang sama jika ada (pindah rombel)
        $rombel->students()->wherePivot('academic_year_id', $rombel->academic_year_id);
        $student->classrooms()->wherePivot('academic_year_id', $rombel->academic_year_id)->detach();

        // Masukkan ke rombel ini
        $rombel->students()->attach($student->id, [
            'academic_year_id' => $rombel->academic_year_id,
        ]);

        return back()->with('success', "Murid {$student->name} berhasil ditambahkan ke rombel {$rombel->name}.");
    }

    public function removeStudent(Classroom $rombel, Student $student): RedirectResponse
    {
        $this->authorize('manageStudents', $rombel);

        $rombel->students()->wherePivot('academic_year_id', $rombel->academic_year_id)->detach($student->id);

        return back()->with('success', "Murid {$student->name} berhasil dikeluarkan dari rombel {$rombel->name}.");
    }
}
