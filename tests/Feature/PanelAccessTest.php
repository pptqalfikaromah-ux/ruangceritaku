<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelAccessTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/panel');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $admin->update(['password' => bcrypt('admin'), 'must_change_password' => false]);
        }

        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertRedirect('/panel');
        $this->assertAuthenticated();

        $panelResponse = $this->get('/panel');
        $panelResponse->assertStatus(200);
        $panelResponse->assertSee('Dashboard Administrator');
    }

    public function test_guru_bk_can_login_and_access_dashboard(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'gurubk'],
            [
                'name' => 'Siti Rahmawati',
                'password' => bcrypt('gurubk'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );
        $user->update(['password' => bcrypt('gurubk'), 'must_change_password' => false]);

        $response = $this->post('/login', [
            'login' => 'gurubk',
            'password' => 'gurubk',
        ]);

        $response->assertRedirect('/panel');
        $this->assertAuthenticated();

        $panelResponse = $this->get('/panel');
        $panelResponse->assertStatus(200);
        $panelResponse->assertSee('Dashboard Guru BK');
        $panelResponse->assertSee('Mode Guru BK: Menampilkan Data Rombel Binaan');
    }

    public function test_guru_bk_cannot_access_admin_sensitive_routes(): void
    {
        $guruBk = User::firstOrCreate(
            ['username' => 'gurubk'],
            [
                'name' => 'Siti Rahmawati',
                'password' => bcrypt('gurubk123'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        // 1. Data Guru BK (admin only)
        $this->actingAs($guruBk)->get('/panel/guru-bk')->assertStatus(403);

        // 2. Manajemen Akun (admin only)
        $this->actingAs($guruBk)->get('/panel/akun')->assertStatus(403);

        // 3. Pengaturan Sekolah (admin only)
        $this->actingAs($guruBk)->get('/panel/setting')->assertStatus(403);
    }

    public function test_guru_bk_only_sees_assigned_classrooms_and_students(): void
    {
        $guruBk = User::firstOrCreate(
            ['username' => 'gurubk'],
            [
                'name' => 'Siti Rahmawati',
                'password' => bcrypt('gurubk123'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        $otherGuru = User::firstOrCreate(
            ['username' => 'ahmadfauzi'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => bcrypt('gurubk123'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        $counselor1 = Counselor::firstOrCreate(
            ['user_id' => $guruBk->id],
            ['name' => 'Siti Rahmawati', 'status' => 'aktif', 'gender' => 'P']
        );

        $counselor2 = Counselor::firstOrCreate(
            ['user_id' => $otherGuru->id],
            ['name' => 'Ahmad Fauzi', 'status' => 'aktif', 'gender' => 'L']
        );

        $semester = Semester::first();
        $academicYear = $semester?->academicYear ?? AcademicYear::first();

        $rombel1 = Classroom::firstOrCreate(['name' => 'VII-A'], ['level' => '7', 'academic_year_id' => $academicYear->id]);
        $rombel2 = Classroom::firstOrCreate(['name' => 'VII-B'], ['level' => '7', 'academic_year_id' => $academicYear->id]);
        $rombel3 = Classroom::firstOrCreate(['name' => 'VIII-A'], ['level' => '8', 'academic_year_id' => $academicYear->id]);

        CounselorAssignment::updateOrCreate(
            ['classroom_id' => $rombel1->id, 'semester_id' => $semester->id],
            ['counselor_id' => $counselor1->id]
        );
        CounselorAssignment::updateOrCreate(
            ['classroom_id' => $rombel2->id, 'semester_id' => $semester->id],
            ['counselor_id' => $counselor1->id]
        );
        CounselorAssignment::updateOrCreate(
            ['classroom_id' => $rombel3->id, 'semester_id' => $semester->id],
            ['counselor_id' => $counselor2->id]
        );

        $student1 = Student::firstOrCreate(
            ['nisn' => '99999901'],
            ['name' => 'Aditya Pratama', 'gender' => 'L', 'status' => 'aktif']
        );
        $student1->classrooms()->syncWithoutDetaching([$rombel1->id => ['academic_year_id' => $academicYear->id]]);

        $student2 = Student::firstOrCreate(
            ['nisn' => '99999902'],
            ['name' => 'Bima Sakti', 'gender' => 'L', 'status' => 'aktif']
        );
        $student2->classrooms()->syncWithoutDetaching([$rombel3->id => ['academic_year_id' => $academicYear->id]]);

        $this->actingAs($guruBk);

        $response = $this->get('/panel/rombel');
        $response->assertStatus(200);
        $response->assertSee('VII-A');
        $response->assertSee('VII-B');
        $response->assertDontSee('VIII-A');

        $studentResponse = $this->get('/panel/murid');
        $studentResponse->assertStatus(200);
        $studentResponse->assertSee('Aditya Pratama');
        $studentResponse->assertDontSee('Bima Sakti');
    }
}
