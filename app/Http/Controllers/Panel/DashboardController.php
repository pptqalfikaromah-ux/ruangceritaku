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

        // Data statistik sekolah untuk Admin & seluruh Guru BK
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

        $recentAssignments = collect();

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
