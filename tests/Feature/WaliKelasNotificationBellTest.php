<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\HomeroomAssignment;
use App\Models\HomeroomComplaint;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class WaliKelasNotificationBellTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_walikelas_notification_bell_renders_and_marks_read(): void
    {
        $unique = 'walas_' . Str::lower(Str::random(6));

        $year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        $sem = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );

        $walasUser = User::create([
            'name' => 'Wali Kelas ' . $unique,
            'username' => 'wls_' . $unique,
            'email' => "{$unique}@test.com",
            'password' => bcrypt('password'),
            'role' => 'wali_kelas',
            'is_active' => true,
        ]);

        $teacher = Teacher::create([
            'user_id' => $walasUser->id,
            'name' => 'Wali Kelas ' . $unique,
            'nip_nuptk' => '198765' . rand(100, 999),
            'status' => 'aktif',
        ]);

        $classroom = Classroom::first() ?? Classroom::create([
            'name' => 'VII-W',
            'level' => '7',
            'academic_year_id' => $year->id,
        ]);

        HomeroomAssignment::create([
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'academic_year_id' => $year->id,
            'semester_id' => $sem->id,
            'is_active' => true,
        ]);

        $student = Student::first();

        // 1. Visit walikelas dashboard
        $response = $this->actingAs($walasUser)->get(route('walikelas.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('dropdownWalasNotifBell');
        $response->assertSee('Notifikasi Masuk');

        // 2. Create complaint updated by BK
        $complaint = HomeroomComplaint::create([
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'student_id' => $student->id,
            'title' => 'Kasus Siswa ' . $unique,
            'category' => 'Kedisiplinan',
            'incident_date' => now()->toDateString(),
            'description' => 'Sering terlambat masuk jam pertama.',
            'status' => 'selesai',
            'counselor_response' => 'Sudah dipanggil dan dibina.',
        ]);

        // 3. Visit again, walas should see notification
        $response2 = $this->actingAs($walasUser)->get(route('walikelas.dashboard'));
        $response2->assertStatus(200);
        $response2->assertSee('Selesai Ditangani BK');

        // 4. Mark all as read
        $markReadResponse = $this->actingAs($walasUser)->post(route('walikelas.notifikasi.mark-all-read'));
        $markReadResponse->assertRedirect();

        // 5. Check count reset
        $service = app(\App\Services\WaliKelasNotificationService::class);
        $counts = $service->getCounts();
        $this->assertEquals(0, $counts['total']);

        // Clean up
        $complaint->delete();
        HomeroomAssignment::where('teacher_id', $teacher->id)->delete();
        $teacher->delete();
        $walasUser->delete();
    }
}
