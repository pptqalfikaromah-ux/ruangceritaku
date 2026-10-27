<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CounselorAssignmentRequest;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CounselorAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $semesterId = $request->input('semester_id', active_semester()?->id);
        $semesters = Semester::with('academicYear')->latest()->get();

        $query = CounselorAssignment::with(['counselor', 'classroom.academicYear', 'semester.academicYear'])
            ->binaan();

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $assignments = $query->latest()->paginate(15)->withQueryString();

        // Tampilan ringkas: Guru BK -> Rombel Binaan -> Jumlah Murid
        $counselorsSummary = Counselor::with(['assignments' => function ($q) use ($semesterId) {
                if ($semesterId) {
                    $q->where('semester_id', $semesterId);
                }
                $q->with(['classroom.students']);
            }])
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'aktif')
            ->get();

        return view('panel.penugasan.index', compact('assignments', 'semesters', 'semesterId', 'counselorsSummary', 'isAdmin'));
    }

    public function create(): View
    {
        $this->authorize('create', CounselorAssignment::class);

        $activeSemester = active_semester();
        $semesters = Semester::with('academicYear')->latest()->get();
        $counselors = Counselor::where('status', 'aktif')->orderBy('name')->get();

        // Ambil rombel yang belum ditugaskan di semester aktif (atau semester pertama yang ada)
        $assignedClassroomIds = CounselorAssignment::where('semester_id', $activeSemester?->id ?? 0)
            ->pluck('classroom_id');

        $classrooms = Classroom::whereNotIn('id', $assignedClassroomIds)
            ->when($activeSemester, fn ($q) => $q->where('academic_year_id', $activeSemester->academic_year_id))
            ->get();

        return view('panel.penugasan.create', compact('counselors', 'classrooms', 'semesters', 'activeSemester'));
    }

    public function store(CounselorAssignmentRequest $request): RedirectResponse
    {
        $this->authorize('create', CounselorAssignment::class);

        CounselorAssignment::create($request->validated());

        return redirect()->route('panel.penugasan.index')
            ->with('success', 'Penugasan Guru BK ke rombel berhasil disimpan.');
    }

    public function destroy(CounselorAssignment $penugasan): RedirectResponse
    {
        $this->authorize('delete', $penugasan);

        $penugasan->delete();

        return redirect()->route('panel.penugasan.index')
            ->with('success', 'Penugasan Guru BK berhasil dihapus.');
    }
}
