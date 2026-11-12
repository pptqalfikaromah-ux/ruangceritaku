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
use Tests\TestCase;

class GroupCounselingFeatureTest extends TestCase
{
    protected User $admin;
    protected User $studentUser;
    protected Student $student;
    protected Student $memberStudent;
    protected Counselor $counselor;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

        $this->admin = User::firstOrCreate(
            ['username' => 'admin_test'],
            ['name' => 'Admin Test', 'email' => 'admin_test@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
        );

        $this->studentUser = User::firstOrCreate(
            ['username' => 'murid_test'],
            ['name' => 'Murid Test', 'email' => 'murid_test@test.com', 'password' => bcrypt('password'), 'role' => 'murid', 'is_active' => true]
        );

        $this->student = $this->studentUser->student ?? Student::first();
        $this->student->update(['user_id' => $this->studentUser->id]);

        $this->memberStudent = Student::where('id', '!=', $this->student->id)->first();
        $this->counselor = Counselor::first();
    }

    public function test_student_can_view_group_counseling_index_and_form(): void
    {
        $responseIndex = $this->actingAs($this->studentUser)->get(route('murid.layanan-kelompok.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Layanan Kelompok');
        $responseIndex->assertSee('Ajukan Sesi Kelompok');

        $responseCreate = $this->actingAs($this->studentUser)->get(route('murid.layanan-kelompok.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Pilih Jenis Layanan Kelompok');
    }

    public function test_student_can_submit_group_counseling_request(): void
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $response = $this->actingAs($this->studentUser)->post(route('murid.layanan-kelompok.store'), [
            'service_type' => 'bimbingan_kelompok',
            'topic' => 'Kiat Manajemen Waktu dan Belajar Efektif',
            'description' => 'Kami merasa kesulitan mengatur waktu belajar antara hafalan dan tugas sekolah.',
            'group_goal' => 'Bisa membuat jadwal belajar harian yang konsisten.',
            'member_ids' => [$this->memberStudent->id],
            'preferred_date' => $tomorrow,
            'preferred_session' => 'Istirahat Pertama (09:30 - 10:00)',
        ]);

        $group = GroupCounseling::where('topic', 'Kiat Manajemen Waktu dan Belajar Efektif')->first();
        $this->assertNotNull($group);
        $response->assertRedirect(route('murid.layanan-kelompok.show', $group));

        $this->assertEquals('bimbingan_kelompok', $group->service_type);
        $this->assertEquals('menunggu_persetujuan', $group->status);
        $this->assertTrue($group->members()->where('student_id', $this->memberStudent->id)->exists());
    }

    public function test_panel_admin_can_view_group_counseling_and_schedule(): void
    {
        $group = GroupCounseling::firstOrCreate(
            ['topic' => 'Tes Sesi BKP Bersama'],
            [
                'ticket_number' => 'BKP-TEST-001',
                'service_type' => 'bimbingan_kelompok',
                'leader_student_id' => $this->student->id,
                'description' => 'Deskripsi tes sesi bimbingan kelompok.',
                'status' => 'menunggu_persetujuan',
            ]
        );

        $responseIndex = $this->actingAs($this->admin)->get(route('panel.layanan-kelompok.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('BKP-TEST-001');

        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $responseSchedule = $this->actingAs($this->admin)->post(route('panel.layanan-kelompok.schedule', $group), [
            'counselor_id' => $this->counselor->id,
            'scheduled_date' => $tomorrow,
            'start_time' => '10:00',
            'end_time' => '10:45',
            'location' => 'Ruang BK Lantai 1',
            'counselor_notes' => 'Harap membawa buku catatan.',
        ]);

        $responseSchedule->assertRedirect(route('panel.layanan-kelompok.show', $group));
        $group->refresh();
        $this->assertEquals('dijadwalkan', $group->status);
        $this->assertEquals('Ruang BK Lantai 1', $group->location);
    }

    public function test_counselor_can_complete_group_counseling_with_evaluation(): void
    {
        $group = GroupCounseling::where('topic', 'like', '%BKP%')->first();
        if (!$group) {
            $group = GroupCounseling::create([
                'ticket_number' => 'BKP-TEST-002',
                'service_type' => 'bimbingan_kelompok',
                'leader_student_id' => $this->student->id,
                'description' => 'Deskripsi tes',
                'topic' => 'Tes BKP Selesai',
                'status' => 'dijadwalkan',
                'scheduled_date' => now()->toDateString(),
                'start_time' => '09:00',
                'end_time' => '09:45',
                'location' => 'Ruang BK',
                'counselor_id' => $this->counselor->id,
            ]);
        }

        $responseComplete = $this->actingAs($this->admin)->post(route('panel.layanan-kelompok.complete', $group), [
            'duration_minutes' => 45,
            'counselor_notes' => 'Anggota sangat aktif berdiskusi.',
            'evaluation_result' => 'Semua anggota menyepakati komitmen belajar.',
            'follow_up_plan' => 'Evaluasi berkala tiap 2 minggu.',
        ]);

        $responseComplete->assertRedirect(route('panel.layanan-kelompok.show', $group));
        $group->refresh();
        $this->assertEquals('selesai', $group->status);
        $this->assertNotNull($group->completed_at);
    }

    protected function tearDown(): void
    {
        // Bersihkan data tes kelompok agar database tetap bersih
        GroupCounseling::where('ticket_number', 'like', '%TEST%')
            ->orWhere('topic', 'like', '%Kiat Manajemen Waktu%')
            ->orWhere('topic', 'like', '%Tes Sesi BKP%')
            ->orWhere('topic', 'like', '%Tes BKP Selesai%')
            ->delete();

        parent::tearDown();
    }
}
