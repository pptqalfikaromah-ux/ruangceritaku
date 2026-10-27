<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentImportRequest;
use App\Http\Requests\StudentRequest;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Services\StudentCsvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function __construct(
        protected StudentCsvService $csvService
    ) {}

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'gender' => $request->input('gender'),
            'classroom_id' => $request->input('classroom_id'),
        ];

        $classrooms = Classroom::binaan()->get();

        $students = Student::with(['classrooms', 'user'])
            ->binaan()
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('panel.murid.index', compact('students', 'filters', 'classrooms'));
    }

    public function create(): View
    {
        $this->authorize('create', Student::class);

        $classrooms = Classroom::latest()->get();

        return view('panel.murid.create', compact('classrooms'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        $data = $request->safe()->except(['photo', 'classroom_id']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/students', 'public');
        }

        $student = Student::create($data);

        // Tambah ke rombel jika dipilih
        if ($request->filled('classroom_id')) {
            $classroom = Classroom::findOrFail($request->classroom_id);
            $student->classrooms()->attach($classroom->id, [
                'academic_year_id' => $classroom->academic_year_id,
            ]);
        }

        return redirect()->route('panel.murid.index')
            ->with('success', 'Data murid baru berhasil disimpan.');
    }

    public function show(Student $murid): View
    {
        $this->authorize('view', $murid);

        $murid->load(['classrooms.academicYear', 'user']);

        return view('panel.murid.show', compact('murid'));
    }

    public function edit(Student $murid): View
    {
        $this->authorize('update', $murid);

        $classrooms = Classroom::latest()->get();
        $currentClassroom = $murid->current_classroom;

        return view('panel.murid.edit', compact('murid', 'classrooms', 'currentClassroom'));
    }

    public function update(StudentRequest $request, Student $murid): RedirectResponse
    {
        $this->authorize('update', $murid);

        $data = $request->safe()->except(['photo', 'classroom_id']);

        if ($request->hasFile('photo')) {
            if ($murid->photo && Storage::disk('public')->exists($murid->photo)) {
                Storage::disk('public')->delete($murid->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/students', 'public');
        }

        $murid->update($data);

        // Update rombel di tahun ajaran aktif
        if ($request->filled('classroom_id')) {
            $classroom = Classroom::findOrFail($request->classroom_id);
            $murid->classrooms()->wherePivot('academic_year_id', $classroom->academic_year_id)->detach();
            $murid->classrooms()->attach($classroom->id, [
                'academic_year_id' => $classroom->academic_year_id,
            ]);
        }

        return redirect()->route('panel.murid.index')
            ->with('success', 'Data murid berhasil diperbarui.');
    }

    public function destroy(Student $murid): RedirectResponse
    {
        $this->authorize('delete', $murid);

        if ($murid->photo && Storage::disk('public')->exists($murid->photo)) {
            Storage::disk('public')->delete($murid->photo);
        }

        $murid->delete();

        return redirect()->route('panel.murid.index')
            ->with('success', 'Data murid berhasil dihapus.');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $this->authorize('import', Student::class);

        return $this->csvService->downloadTemplate();
    }

    public function import(StudentImportRequest $request): RedirectResponse
    {
        $this->authorize('import', Student::class);

        $result = $this->csvService->import(
            $request->file('file'),
            $request->input('classroom_id') ? (int) $request->input('classroom_id') : null
        );

        $message = "Berhasil mengimpor {$result['success_count']} data murid.";

        if (!empty($result['failed_rows'])) {
            return back()->with('warning', $message . ' Terdapat baris yang gagal diimpor.')
                ->with('import_failed_rows', $result['failed_rows']);
        }

        return back()->with('success', $message);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('export', Student::class);

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'gender' => $request->input('gender'),
            'classroom_id' => $request->input('classroom_id'),
        ];

        return $this->csvService->export($filters);
    }
}
