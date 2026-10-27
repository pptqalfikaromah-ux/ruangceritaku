<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StudentAndAccountTest extends TestCase
{
    public function test_admin_can_download_student_template_and_export(): void
    {
        $admin = User::where('role', 'admin')->first();

        $templateResponse = $this->actingAs($admin)->get('/panel/murid-csv/template');
        $templateResponse->assertStatus(200);
        $templateResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $exportResponse = $this->actingAs($admin)->get('/panel/murid-csv/export');
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_mass_student_account_generation_and_export_credentials(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/panel/akun/generate-murid', [
            'default_password' => 'pass123456',
        ]);

        $response->assertSessionHas('success');

        // Check if users were created
        $studentsWithoutUser = Student::whereNull('user_id')->count();
        $this->assertEquals(0, $studentsWithoutUser);

        // Export credentials
        $exportResponse = $this->actingAs($admin)->get('/panel/akun/export-kredensial?default_password=pass123456');
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_must_change_password_middleware_redirection(): void
    {
        $uniqueUser = 'user_' . uniqid();
        $userWithMustChange = User::create([
            'name' => 'Testing Murid',
            'username' => $uniqueUser,
            'password' => bcrypt('password123'),
            'role' => 'admin', // temporary role to test panel
            'is_active' => true,
            'must_change_password' => true,
        ]);

        // Trying to access dashboard should be redirected to /panel/ubah-password
        $response = $this->actingAs($userWithMustChange)->get('/panel');
        $response->assertRedirect('/panel/ubah-password');

        // Can access /panel/ubah-password
        $pageResponse = $this->actingAs($userWithMustChange)->get('/panel/ubah-password');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Pemberitahuan Wajib Ganti Password');

        // Submit new password
        $updateResponse = $this->actingAs($userWithMustChange)->put('/panel/ubah-password', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $updateResponse->assertRedirect('/panel');
        $userWithMustChange->refresh();
        $this->assertFalse($userWithMustChange->must_change_password);

        $userWithMustChange->delete();
    }

    public function test_student_login_and_cross_portal_redirection(): void
    {
        $uniqueNisn = '0088' . rand(100000, 999999);
        $studentUser = User::create([
            'name' => 'Siswa Tes Multi Role',
            'username' => $uniqueNisn,
            'password' => bcrypt('password123'),
            'role' => 'murid',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        // 1. Login using NISN redirects to murid dashboard
        $loginResponse = $this->post('/login', [
            'login' => $uniqueNisn,
            'password' => 'password123',
        ]);
        $loginResponse->assertRedirect(route('murid.dashboard'));

        // 2. Student accessing /murid and /murid/dashboard gets 200 OK
        $dashboardResponse = $this->actingAs($studentUser)->get('/murid');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee($studentUser->name);

        // 3. Student accessing /panel is automatically redirected to /murid
        $panelAttempt = $this->actingAs($studentUser)->get('/panel');
        $panelAttempt->assertRedirect(route('murid.dashboard'));

        // 4. Admin accessing /murid is automatically redirected to /panel
        $admin = User::where('role', 'admin')->first();
        $muridAttempt = $this->actingAs($admin)->get('/murid');
        $muridAttempt->assertRedirect(route('panel.dashboard'));

        // 5. Check real seeded student 0098765432 gets 200 OK on /murid
        $seededStudentUser = User::where('username', '0098765432')->first();
        if ($seededStudentUser) {
            $seededResponse = $this->actingAs($seededStudentUser)->get('/murid');
            $seededResponse->assertStatus(200);
            $seededResponse->assertSee('Ahmad Fauzi');
        }

        $studentUser->delete();
    }
}

