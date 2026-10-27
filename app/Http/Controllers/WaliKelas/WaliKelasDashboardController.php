<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\CounselingRequest;
use App\Models\HomeroomComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WaliKelasDashboardController extends Controller
{
    /**
     * Beranda Dashboard Wali Kelas
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        $assignment = $teacher?->currentAssignment;
        $classroom = $assignment?->classroom;

        $students = collect();
        $totalStudents = 0;
        $totalCounseling = 0;
        $activeCounselingCount = 0;
        $totalComplaints = 0;
        $recentCounselings = collect();
        $recentComplaints = collect();

        if ($classroom) {
            $students = $classroom->students()->orderBy('name')->get();
            $totalStudents = $students->count();
            $studentIds = $students->pluck('id')->toArray();

            $totalCounseling = CounselingRequest::whereIn('student_id', $studentIds)->count();
            $activeCounselingCount = CounselingRequest::whereIn('student_id', $studentIds)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();

            $recentCounselings = CounselingRequest::with(['student', 'assignedCounselor.user', 'schedule'])
                ->whereIn('student_id', $studentIds)
                ->latest()
                ->limit(5)
                ->get();

            $totalComplaints = HomeroomComplaint::where('teacher_id', $teacher->id)->count();
            $recentComplaints = HomeroomComplaint::with(['student', 'handledByCounselor'])
                ->where('teacher_id', $teacher->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('walikelas.dashboard', compact(
            'user',
            'teacher',
            'classroom',
            'students',
            'totalStudents',
            'totalCounseling',
            'activeCounselingCount',
            'totalComplaints',
            'recentCounselings',
            'recentComplaints',
            'activeSemester',
            'activeYear'
        ));
    }
}
