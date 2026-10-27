<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\BkProgram;
use App\Models\BkRpl;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\CounselingRequestHistory;
use App\Models\CounselingSchedule;
use App\Models\CounselingService;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\HomeroomAssignment;
use App\Models\HomeroomComplaint;
use App\Models\ImsAnswer;
use App\Models\ImsAssessment;
use App\Models\ImsCustomStatement;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentComplaint;
use App\Models\StudentPamphlet;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Kosongkan semua data transaksional
        CounselingRequestHistory::truncate();
        CounselingSchedule::truncate();
        CounselingService::truncate();
        CounselingRequest::truncate();

        HomeroomComplaint::truncate();
        StudentComplaint::truncate();
        StudentPamphlet::truncate();
        Announcement::truncate();

        ImsAnswer::truncate();
        ImsCustomStatement::truncate();
        ImsAssessment::truncate();

        BkProgram::truncate();
        BkRpl::truncate();

        // 2. Kosongkan relasi & data master murid, guru, rombel, akun
        DB::table('classroom_student')->truncate();
        HomeroomAssignment::truncate();
        CounselorAssignment::truncate();

        Student::truncate();
        Teacher::truncate();
        Counselor::truncate();
        Classroom::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Setup Tahun Ajaran Aktif (2025/2026) & Semester Aktif (Ganjil)
        AcademicYear::query()->update(['is_active' => false]);
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );
        $year->update(['is_active' => true]);

        Semester::where('academic_year_id', $year->id)->update(['is_active' => false]);
        $semGanjil = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );
        $semGanjil->update(['is_active' => true]);

        Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Genap'],
            ['is_active' => false]
        );

        // 4. Buat 1 Akun Administrator: Budi Santoso, S.Pd.
        $admin = User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'username' => 'admin',
            'email' => 'admin@ruangceritaku.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        // Buat profil Konselor / Guru BK pendamping untuk Budi Santoso
        Counselor::create([
            'user_id' => $admin->id,
            'nip_nuptk' => '198705152012011004',
            'name' => 'Budi Santoso, S.Pd.',
            'title' => 'S.Pd.',
            'gender' => 'L',
            'phone' => '081234567890',
            'email' => 'admin@ruangceritaku.sch.id',
            'status' => 'aktif',
        ]);
    }
}
