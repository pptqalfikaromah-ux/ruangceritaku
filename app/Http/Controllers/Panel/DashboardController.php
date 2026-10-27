<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Mengumpulkan data dashboard & statistik konseling
     */
    private function getDashboardData(): array
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $activeSemester = active_semester();
        $activeYear = active_academic_year();
        $counselor = Counselor::where('user_id', $user->id)->first();

        if ($isAdmin) {
            // Data untuk Admin (seluruh madrasah/sekolah)
            $totalStudents = Student::where('status', 'aktif')->count();
            $totalClassrooms = Classroom::count();
            $totalCounselors = Counselor::where('status', 'aktif')->count();

            // Komposisi Gender
            $maleCount = Student::where('status', 'aktif')->where('gender', 'L')->count();
            $femaleCount = Student::where('status', 'aktif')->where('gender', 'P')->count();

            // Rombel & murid per rombel untuk grafik HTML/CSS
            $classroomsData = Classroom::withCount('students')
                ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))
                ->take(8)
                ->get();

            // Penugasan Guru BK terbaru
            $recentAssignments = CounselorAssignment::with(['counselor', 'classroom', 'semester.academicYear'])
                ->latest()
                ->take(6)
                ->get();
        } else {
            // Data untuk Guru BK (rombel binaannya saja)
            $assignedClassroomIds = [];

            if ($counselor && $activeSemester) {
                $assignedClassroomIds = CounselorAssignment::where('counselor_id', $counselor->id)
                    ->where('semester_id', $activeSemester->id)
                    ->pluck('classroom_id')
                    ->toArray();
            }

            $totalClassrooms = count($assignedClassroomIds);

            // Hitung murid di rombel binaan
            $studentsQuery = Student::where('status', 'aktif')
                ->whereHas('classrooms', function ($q) use ($assignedClassroomIds, $activeYear) {
                    $q->whereIn('classrooms.id', $assignedClassroomIds)
                      ->when($activeYear, fn ($sub) => $sub->where('classroom_student.academic_year_id', $activeYear->id));
                });

            $totalStudents = $studentsQuery->count();
            $totalCounselors = Counselor::where('status', 'aktif')->count();

            $maleCount = (clone $studentsQuery)->where('gender', 'L')->count();
            $femaleCount = (clone $studentsQuery)->where('gender', 'P')->count();

            $classroomsData = Classroom::whereIn('id', $assignedClassroomIds)
                ->withCount('students')
                ->get();

            $recentAssignments = CounselorAssignment::with(['counselor', 'classroom', 'semester.academicYear'])
                ->where('counselor_id', $counselor?->id ?? 0)
                ->latest()
                ->take(6)
                ->get();
        }

        $totalGender = $maleCount + $femaleCount;
        $malePercentage = $totalGender > 0 ? round(($maleCount / $totalGender) * 100) : 0;
        $femalePercentage = $totalGender > 0 ? round(($femaleCount / $totalGender) * 100) : 0;

        $totalServiceTypes = \App\Models\ServiceType::count();
        $totalGuidanceFields = \App\Models\GuidanceField::count();

        return compact(
            'user',
            'isAdmin',
            'counselor',
            'activeSemester',
            'activeYear',
            'totalStudents',
            'totalClassrooms',
            'totalCounselors',
            'maleCount',
            'femaleCount',
            'malePercentage',
            'femalePercentage',
            'classroomsData',
            'recentAssignments',
            'totalServiceTypes',
            'totalGuidanceFields'
        );
    }

    /**
     * Halaman Beranda (Personal Dashboard PTK / Guru BK & Admin - Sesuai Screenshot EMIS GTK)
     */
    public function index(): View
    {
        $data = $this->getDashboardData();
        return view('panel.dashboard.beranda', $data);
    }

    /**
     * Halaman Peta Situs (Sitemap Grid Navigasi Alur Kerja & Layanan)
     */
    public function petaSitus(): View
    {
        $data = $this->getDashboardData();
        return view('panel.dashboard.index', $data);
    }

    /**
     * Tandai seluruh notifikasi masuk sebagai telah dibaca
     */
    public function markAllNotificationsRead(\App\Services\PanelNotificationService $service): \Illuminate\Http\RedirectResponse
    {
        $service->markAllAsRead();
        return back()->with('success', 'Semua notifikasi berhasil ditandai telah dibaca.');
    }
}
