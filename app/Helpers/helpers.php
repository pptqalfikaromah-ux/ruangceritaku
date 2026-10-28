<?php

use App\Models\Setting;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Counselor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (!function_exists('app_setting')) {
    /**
     * Mengambil nilai pengaturan dari database dengan caching
     */
    function app_setting(string $key, mixed $default = null): mixed
    {
        try {
            $settings = Cache::rememberForever('app_settings_all', function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            return $settings[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('app_logo')) {
    /**
     * Mengambil URL logo aplikasi (fallback ke public/images/logo.png)
     */
    function app_logo(): string
    {
        $customLogo = app_setting('app_logo');

        if ($customLogo) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if (Storage::disk($disk)->exists($customLogo)) {
                return Storage::disk($disk)->url($customLogo);
            }
            if (Storage::disk('public')->exists($customLogo)) {
                return asset('storage/' . $customLogo);
            }
        }

        return asset('images/logo.png');
    }
}

if (!function_exists('active_semester')) {
    /**
     * Mengambil semester aktif saat ini
     */
    function active_semester(): ?Semester
    {
        return Cache::remember('active_semester_model', 300, function () {
            return Semester::with('academicYear')->where('is_active', true)->first();
        });
    }
}

if (!function_exists('active_academic_year')) {
    /**
     * Mengambil tahun ajaran aktif saat ini
     */
    function active_academic_year(): ?AcademicYear
    {
        return Cache::remember('active_academic_year_model', 300, function () {
            return AcademicYear::where('is_active', true)->first();
        });
    }
}

if (!function_exists('current_counselor')) {
    /**
     * Mengambil profil Guru BK dari user yang sedang login
     */
    function current_counselor(): ?Counselor
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['guru_bk', 'admin'])) {
            return null;
        }

        return Auth::user()->counselor ?? Counselor::where('user_id', Auth::id())->first();
    }
}

if (!function_exists('panel_notifications')) {
    /**
     * Mengambil ringkasan hitungan notifikasi panel
     */
    function panel_notifications(): array
    {
        return app(\App\Services\PanelNotificationService::class)->getCounts();
    }
}

if (!function_exists('panel_recent_notifications')) {
    /**
     * Mengambil daftar item notifikasi terbaru untuk header lonceng
     */
    function panel_recent_notifications(int $limit = 8): \Illuminate\Support\Collection
    {
        return app(\App\Services\PanelNotificationService::class)->getRecentNotifications($limit);
    }
}
