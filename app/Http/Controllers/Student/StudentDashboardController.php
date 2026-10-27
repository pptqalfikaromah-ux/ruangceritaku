<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CounselingRequest;
use App\Models\ImsAssessment;
use App\Models\StudentComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $student = $user->student;

        abort_unless($student, 403, 'Profil data murid tidak ditemukan untuk akun ini.');

        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        // 1. Status IMS
        $currentAssessment = null;
        if ($activeSemester) {
            $currentAssessment = ImsAssessment::where('student_id', $student->id)
                ->where('semester_id', $activeSemester->id)
                ->first();
        }

        // 2. Active Counseling Agenda
        $activeCounseling = CounselingRequest::with(['assignedCounselor.user', 'schedule'])
            ->where('student_id', $student->id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->latest()
            ->first();

        // 3. Recent Counseling List
        $recentCounselings = CounselingRequest::where('student_id', $student->id)
            ->latest()
            ->limit(3)
            ->get();

        // 4. Recent Complaints
        $recentComplaints = StudentComplaint::where('student_id', $student->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'activeSemester',
            'activeYear',
            'currentAssessment',
            'activeCounseling',
            'recentCounselings',
            'recentComplaints'
        ));
    }
}
