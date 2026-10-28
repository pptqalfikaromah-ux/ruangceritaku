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
        $maleStudentsCount = 0;
        $femaleStudentsCount = 0;
        $totalComplaints = 0;
        $recentComplaints = collect();

        if ($classroom) {
            $students = $classroom->students()->orderBy('name')->get();
            $totalStudents = $students->count();
            $maleStudentsCount = $students->where('gender', 'L')->count();
            $femaleStudentsCount = $students->where('gender', 'P')->count();

            $totalComplaints = HomeroomComplaint::where('teacher_id', $teacher->id)->count();
            $recentComplaints = HomeroomComplaint::with(['student', 'handledByCounselor'])
                ->where('teacher_id', $teacher->id)
                ->latest()
                ->limit(6)
                ->get();
        }

        return view('walikelas.dashboard', compact(
            'user',
            'teacher',
            'classroom',
            'students',
            'totalStudents',
            'maleStudentsCount',
            'femaleStudentsCount',
            'totalComplaints',
            'recentComplaints',
            'activeSemester',
            'activeYear'
        ));
    }
}
