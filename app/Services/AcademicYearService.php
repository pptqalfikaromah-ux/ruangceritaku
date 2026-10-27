<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AcademicYearService
{
    /**
     * Mengaktifkan semester tertentu dan tahun ajarannya,
     * serta menonaktifkan seluruh semester & tahun ajaran lainnya.
     */
    public function activateSemester(Semester $semester): void
    {
        DB::transaction(function () use ($semester) {
            // Nonaktifkan semua semester & tahun ajaran
            Semester::query()->update(['is_active' => false]);
            AcademicYear::query()->update(['is_active' => false]);

            // Aktifkan semester terpilih dan tahun ajarannya
            $semester->update(['is_active' => true]);
            $semester->academicYear->update(['is_active' => true]);

            // Bersihkan cache
            Cache::forget('active_semester_model');
            Cache::forget('active_academic_year_model');
        });
    }
}
