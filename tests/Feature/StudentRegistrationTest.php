<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    protected Classroom $classroom;
    protected AcademicYear $year;

    protected function setUp(): void
    {
        parent::setUp();

        $this->year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            [
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_active' => true,
            ]
        );
        $this->year->update(['is_active' => true]);

        $this->classroom = Classroom::firstOrCreate(
            ['name' => 'VIII-B', 'academic_year_id' => $this->year->id],
            ['level' => '8']
        );
    }

    public function test_student_registration_page_is_accessible_and_shows_classrooms(): void
    {
        $response = $this->get(route('register.murid'));

        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran Murid Baru');
        $response->assertSee($this->classroom->name);
    }

    public function test_student_can_register_with_valid_data_and_selected_classroom(): void
    {
        $uniqueNisn = '1122334455';
        $uniqueUser = 'siswa_baru_test';

        // Clean up prior test data if exists
        User::where('username', $uniqueUser)->forceDelete();
        Student::where('nisn', $uniqueNisn)->forceDelete();

        $response = $this->post(route('register.murid.submit'), [
            'name' => 'Ahmad Siswa Baru',
            'nisn' => $uniqueNisn,
            'nis' => '12345',
            'classroom_id' => $this->classroom->id,
            'gender' => 'L',
            'birth_place' => 'Banyumas',
            'birth_date' => '2010-05-15',
            'guardian_phone' => '081298765432',
            'address' => 'Jl. Pendidikan No. 10',
            'username' => $uniqueUser,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('murid.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('username', $uniqueUser)->first();
        $this->assertNotNull($user);
        $this->assertEquals('murid', $user->role);
        $this->assertFalse($user->must_change_password);

        $student = Student::where('nisn', $uniqueNisn)->first();
        $this->assertNotNull($student);
        $this->assertEquals($user->id, $student->user_id);
        $this->assertEquals('Ahmad Siswa Baru', $student->name);

        // Verify student is attached to the selected classroom
        $this->assertTrue($student->classrooms->contains($this->classroom->id));
    }

    public function test_registration_validation_fails_on_password_mismatch(): void
    {
        $response = $this->post(route('register.murid.submit'), [
            'name' => 'Budi Santoso',
            'nisn' => '9900112233',
            'classroom_id' => $this->classroom->id,
            'gender' => 'L',
            'username' => 'budisantoso',
            'password' => 'password123',
            'password_confirmation' => 'salah_password',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_validation_fails_when_classroom_is_missing(): void
    {
        $response = $this->post(route('register.murid.submit'), [
            'name' => 'Citra Lestari',
            'nisn' => '9900112234',
            'classroom_id' => 99999, // non-existent
            'gender' => 'P',
            'username' => 'citralestari',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['classroom_id']);
        $this->assertGuest();
    }
}
