<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\GroupCounseling;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudentNotificationBellTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_student_notification_bell_renders_and_counts_correctly()
    {
        $unique = 'notif_' . Str::lower(Str::random(6));

        $year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        $sem = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );

        $counselor = Counselor::first();
        if (!$counselor) {
            $counselorUser = User::create([
                'name' => 'Guru BK Notif ' . $unique,
                'username' => 'gbk_' . $unique,
                'email' => "{$unique}_gbk@test.com",
                'password' => bcrypt('password'),
                'role' => 'guru_bk',
                'is_active' => true,
            ]);

            $counselor = Counselor::create([
                'user_id' => $counselorUser->id,
                'name' => 'Guru BK Notif ' . $unique,
                'nip' => '998877' . rand(100, 999),
                'specialization' => 'Bimbingan Siswa',
                'is_active' => true,
            ]);
        } else {
            $counselorUser = null;
        }

        $studentUser = User::create([
            'name' => 'Siswa Notif ' . $unique,
            'username' => 'std_' . $unique,
            'email' => "{$unique}_std@test.com",
            'password' => bcrypt('password'),
            'role' => 'murid',
            'is_active' => true,
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'nisn' => '9911' . rand(1000, 9999),
            'nis' => '11' . rand(100, 999),
            'name' => 'Siswa Notif ' . $unique,
            'gender' => 'L',
            'status' => 'aktif',
            'birth_date' => '2009-07-15',
        ]);

        $classroom = Classroom::first() ?? Classroom::create([
            'name' => 'IX-Z',
            'level' => '9',
            'academic_year_id' => $year->id,
        ]);
        $student->classrooms()->syncWithoutDetaching([$classroom->id => ['academic_year_id' => $year->id]]);

        // 1. Visit dashboard as student - initially 0 or only active announcements
        $response = $this->actingAs($studentUser)->get(route('murid.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('dropdownStudentNotifBell');
        $response->assertSee('Notifikasi Siswa');

        // 2. Guru BK schedules a BKP session for this student
        $group = GroupCounseling::create([
            'academic_year_id' => $year->id,
            'semester_id' => $sem->id,
            'counselor_id' => $counselor->id,
            'leader_student_id' => $student->id,
            'ticket_number' => 'BKP-' . strtoupper(Str::random(6)),
            'service_type' => 'bimbingan_kelompok',
            'topic' => 'Motivasi Belajar ' . $unique,
            'description' => 'Deskripsi BKP ' . $unique,
            'scheduled_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'location' => 'Ruang BK Utama',
            'status' => 'dijadwalkan',
        ]);
        $group->members()->attach($student->id);

        // 3. Visit dashboard again - student should see the notification item in the bell
        $response2 = $this->actingAs($studentUser)->get(route('murid.dashboard'));
        $response2->assertStatus(200);
        $response2->assertSee('Jadwal BKP Ditetapkan Guru BK');
        $response2->assertSee('Motivasi Belajar ' . $unique);

        // 4. Mark all as read
        $markReadResponse = $this->actingAs($studentUser)->post(route('murid.notifikasi.mark-all-read'));
        $markReadResponse->assertRedirect();

        // 5. Check bell count is reset
        $service = app(\App\Services\StudentNotificationService::class);
        $counts = $service->getCounts();
        $this->assertEquals(0, $counts['total']);

        // Clean up
        $group->members()->detach();
        $group->delete();
        $student->classrooms()->detach();
        $student->delete();
        $studentUser->delete();
        if ($counselorUser) {
            $counselor->delete();
            $counselorUser->delete();
        }
    }
}
