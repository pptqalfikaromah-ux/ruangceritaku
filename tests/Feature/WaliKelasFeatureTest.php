<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\Counselor;
use App\Models\HomeroomAssignment;
use App\Models\HomeroomComplaint;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WaliKelasFeatureTest extends TestCase
{
    private function setupBaseData(): array
    {
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        $semester = Semester::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );

        $admin = User::firstOrCreate(
            ['username' => 'admin_test'],
            [
                'name' => 'Administrator Test',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        $classroom = Classroom::firstOrCreate(
            ['name' => 'VII-Z'],
            ['level' => '7', 'academic_year_id' => $year->id]
        );

        $student = Student::firstOrCreate(
            ['nisn' => '8888777701'],
            ['nis' => '8801', 'name' => 'Siswa Binaan Test', 'gender' => 'L', 'status' => 'aktif']
        );
        $student->classrooms()->syncWithoutDetaching([$classroom->id => ['academic_year_id' => $year->id]]);

        return compact('year', 'semester', 'admin', 'classroom', 'student');
    }

    public function test_admin_can_create_wali_kelas_with_user_account(): void
    {
        $data = $this->setupBaseData();

        $nip = '19790101' . rand(10000000, 99999999);
        $uname = 'hendro_' . uniqid();
        $response = $this->actingAs($data['admin'])->post(route('panel.wali-kelas.store'), [
            'name' => 'Drs. Hendro Wibowo',
            'nip_nuptk' => $nip,
            'gender' => 'L',
            'phone' => '081234000111',
            'email' => $uname . '@test.sch.id',
            'status' => 'aktif',
            'username' => $uname,
            'password' => 'hendro123',
            'password_confirmation' => 'hendro123',
        ]);

        $response->assertRedirect(route('panel.wali-kelas.index'));
        $this->assertDatabaseHas('teachers', ['nip_nuptk' => $nip]);
        $this->assertDatabaseHas('users', ['username' => $uname, 'role' => 'wali_kelas']);
    }

    public function test_admin_can_assign_wali_kelas_to_rombel(): void
    {
        $data = $this->setupBaseData();

        $classroom2 = Classroom::firstOrCreate(
            ['name' => 'VII-Y'],
            ['level' => '7', 'academic_year_id' => $data['year']->id]
        );

        $user = User::create([
            'username' => 'srihandayani_' . uniqid(),
            'name' => 'Ibu Sri Handayani, M.Pd.',
            'password' => Hash::make('password123'),
            'role' => 'wali_kelas',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'name' => 'Ibu Sri Handayani, M.Pd.',
            'nip_nuptk' => '19810202200601' . rand(1000, 9999),
            'gender' => 'P',
            'phone' => '081234000222',
            'email' => 'sri.handayani@test.sch.id',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($data['admin'])->post(route('panel.penugasan-wali-kelas.store'), [
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom2->id,
            'notes' => 'Wali Kelas VII-Y Resmi',
        ]);

        $response->assertRedirect(route('panel.penugasan-wali-kelas.index'));
        $this->assertDatabaseHas('homeroom_assignments', [
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom2->id,
            'semester_id' => $data['semester']->id,
        ]);
    }

    public function test_wali_kelas_can_login_and_access_dashboard_and_monitoring(): void
    {
        $data = $this->setupBaseData();

        $suffix = uniqid();
        $uname = 'walikelas_' . $suffix;
        $waliUser = User::create([
            'name' => 'Wali Kelas VII-Z',
            'username' => $uname,
            'password' => Hash::make('secret123'),
            'role' => 'wali_kelas',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $teacher = Teacher::create([
            'user_id' => $waliUser->id,
            'name' => 'Wali Kelas VII-Z',
            'nip_nuptk' => '19850303' . rand(10000000, 99999999),
            'gender' => 'L',
            'status' => 'aktif',
        ]);

        HomeroomAssignment::updateOrCreate(
            [
                'academic_year_id' => $data['year']->id,
                'semester_id' => $data['semester']->id,
                'classroom_id' => $data['classroom']->id,
            ],
            [
                'teacher_id' => $teacher->id,
            ]
        );

        // Login as wali_kelas
        $loginRes = $this->post('/login', [
            'login' => $uname,
            'password' => 'secret123',
        ]);
        $loginRes->assertRedirect(route('walikelas.dashboard'));

        // Access Dashboard
        $dashRes = $this->actingAs($waliUser)->get(route('walikelas.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertSee('VII-Z');
        $dashRes->assertSee($data['student']->name);

        // Access Monitoring
        $monRes = $this->actingAs($waliUser)->get(route('walikelas.monitoring.index'));
        $monRes->assertStatus(200);
    }

    public function test_wali_kelas_can_submit_complaint_and_counselor_can_respond(): void
    {
        $data = $this->setupBaseData();

        $suffix = uniqid();
        $waliUser = User::create([
            'name' => 'Wali Kelas Aduan',
            'username' => 'wali_aduan_' . $suffix,
            'password' => Hash::make('secret123'),
            'role' => 'wali_kelas',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $teacher = Teacher::create([
            'user_id' => $waliUser->id,
            'name' => 'Wali Kelas Aduan',
            'nip_nuptk' => '19890404' . rand(10000000, 99999999),
            'gender' => 'L',
            'status' => 'aktif',
        ]);

        HomeroomAssignment::updateOrCreate(
            [
                'academic_year_id' => $data['year']->id,
                'semester_id' => $data['semester']->id,
                'classroom_id' => $data['classroom']->id,
            ],
            [
                'teacher_id' => $teacher->id,
            ]
        );

        // Wali kelas submits aduan
        $submitRes = $this->actingAs($waliUser)->post(route('walikelas.aduan.store'), [
            'student_id' => $data['student']->id,
            'incident_date' => now()->toDateString(),
            'category' => 'pelanggaran_aturan',
            'title' => 'Membawa HP ke kelas tanpa izin saat ulangan harian',
            'description' => 'Siswa kedapatan menyembunyikan handphone di laci meja saat ulangan berlangsung.',
            'initial_action' => 'HP disita sementara dan disimpan di ruang wali kelas.',
            'teacher_notes' => 'Mohon Guru BK memanggil siswa dan orang tua untuk pembinaan integritas.',
        ]);

        $submitRes->assertRedirect(route('walikelas.aduan.index'));

        $complaint = HomeroomComplaint::where('student_id', $data['student']->id)->latest()->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('menunggu_tindak_lanjut', $complaint->status);

        // Guru BK checks aduan in panel and responds
        $counselorUser = User::firstOrCreate(
            ['username' => 'gurubk_tester'],
            [
                'name' => 'Guru BK Tester',
                'password' => Hash::make('password123'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );
        $counselor = Counselor::firstOrCreate(
            ['user_id' => $counselorUser->id],
            ['name' => 'Guru BK Tester', 'status' => 'aktif', 'gender' => 'P']
        );

        $bkListRes = $this->actingAs($counselorUser)->get(route('panel.aduan-walikelas.index'));
        $bkListRes->assertStatus(200);
        $bkListRes->assertSee('Membawa HP ke kelas');

        $bkFollowUpRes = $this->actingAs($counselorUser)->put(route('panel.aduan-walikelas.update', $complaint->id), [
            'status' => 'sedang_ditangani',
            'counselor_response' => 'Siswa sudah dipanggil ke ruang BK untuk konseling individual sesi 1 dan telah menandatangani surat komitmen.',
        ]);

        $bkFollowUpRes->assertRedirect(route('panel.aduan-walikelas.show', $complaint->id));

        $complaint->refresh();
        $this->assertEquals('sedang_ditangani', $complaint->status);
        $this->assertNotNull($complaint->handled_at);
        $this->assertEquals($counselor->id, $complaint->handled_by_counselor_id);
    }
}
