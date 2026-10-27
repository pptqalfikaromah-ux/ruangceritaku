<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\Counselor;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\Student;
use App\Models\StudentPamphlet;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ImsCounselingAndPamphletFeatureTest extends TestCase
{
    protected User $adminUser;
    protected User $counselorUser;
    protected Counselor $counselor;
    protected User $muridUser;
    protected Student $student;
    protected Classroom $classroom;
    protected AcademicYear $year;
    protected Semester $semester;
    protected ServiceType $service;

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

        $this->semester = Semester::firstOrCreate(
            ['academic_year_id' => $this->year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );
        $this->semester->update(['is_active' => true]);

        $this->adminUser = User::firstOrCreate(
            ['username' => 'admin_test_pamphlet'],
            [
                'name' => 'Admin Ruang Ceritaku',
                'role' => 'admin',
                'password' => bcrypt('password123'),
                'status' => 'aktif',
                'must_change_password' => false,
            ]
        );

        $this->counselorUser = User::firstOrCreate(
            ['username' => 'gurubk_test_pamphlet'],
            [
                'name' => 'Guru BK Test',
                'role' => 'guru_bk',
                'password' => bcrypt('password123'),
                'status' => 'aktif',
                'must_change_password' => false,
            ]
        );

        $this->counselor = Counselor::firstOrCreate(
            ['user_id' => $this->counselorUser->id],
            [
                'nip' => '198701012015011001',
                'name' => 'Guru BK Test, S.Pd',
                'gender' => 'L',
                'phone' => '081234567890',
                'status' => 'aktif',
            ]
        );

        $this->classroom = Classroom::firstOrCreate(
            ['name' => 'VII-A', 'academic_year_id' => $this->year->id],
            ['level' => '7']
        );

        $this->muridUser = User::firstOrCreate(
            ['username' => 'murid_test_pamphlet'],
            [
                'name' => 'Murid Test Kreatif',
                'role' => 'murid',
                'password' => bcrypt('password123'),
                'status' => 'aktif',
                'must_change_password' => false,
            ]
        );

        $this->student = Student::firstOrCreate(
            ['nisn' => '9988776655'],
            [
                'user_id' => $this->muridUser->id,
                'name' => 'Murid Test Kreatif',
                'gender' => 'L',
                'status' => 'aktif',
            ]
        );
        $this->student->update(['user_id' => $this->muridUser->id]);
        $this->student->refresh();
        $this->muridUser->refresh();

        $this->student->classrooms()->syncWithoutDetaching([
            $this->classroom->id => ['academic_year_id' => $this->year->id],
        ]);

        $guidanceField = \App\Models\GuidanceField::where('code', 'BID-01')->first()
            ?? \App\Models\GuidanceField::firstOrCreate(
                ['code' => 'BID-01'],
                ['name' => 'Pribadi', 'description' => 'Bidang pribadi']
            );

        $this->service = ServiceType::firstOrCreate(
            ['name' => 'Bimbingan Pribadi'],
            [
                'code' => 'BP-01',
                'description' => 'Layanan bimbingan masalah pribadi',
                'is_active' => true,
            ]
        );
    }

    public function test_counseling_workflow_with_google_meet_online_method(): void
    {
        CounselingRequest::where('student_id', $this->student->id)->delete();
        $guidanceField = \App\Models\GuidanceField::first();

        // 1. Murid mengajukan konseling dengan metode online (Google Meet)
        $response = $this->actingAs($this->muridUser)->post(route('murid.konseling.store'), [
            'purpose' => 'Konseling Peningkatan Percaya Diri Online',
            'guidance_field_id' => $guidanceField?->id,
            'service_type_id' => $this->service->id,
            'description' => 'Saya merasa kurang percaya diri saat berbicara di depan kelas.',
            'counseling_method' => 'online_meet',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('counseling_requests', [
            'student_id' => $this->student->id,
            'counseling_method' => 'online_meet',
            'status' => 'pending',
        ]);

        $counseling = CounselingRequest::where('student_id', $this->student->id)->latest()->first();

        $counselorId = Counselor::where('user_id', $this->counselorUser->id)->value('id') ?? Counselor::value('id');

        // 2. Admin / Guru BK menyetujui rencana dan mengisikan Jadwal + Link Google Meet
        $responseSchedule = $this->actingAs($this->counselorUser)->post(route('panel.konseling.schedule', $counseling), [
            'counselor_id' => $counselorId,
            'scheduled_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '09:45',
            'counseling_method' => 'online_meet',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'notes' => 'Siapkan kamera dan microphone aktif',
        ]);

        $responseSchedule->assertSessionHasNoErrors();
        $responseSchedule->assertRedirect();
        $counseling->refresh();

        // Status di database adalah scheduled (Disetujui Rencana di Admin, Disetujui di Murid)
        $this->assertEquals('scheduled', $counseling->status);
        $this->assertEquals('https://meet.google.com/abc-defg-hij', $counseling->schedule->meeting_link);
        $this->assertEquals('online_meet', $counseling->counseling_method);

        // 3. Murid melihat status Disetujui dan dapat cetak Kartu Konseling PDF
        $responseShow = $this->actingAs($this->muridUser)->get(route('murid.konseling.show', $counseling));
        $responseShow->assertOk();
        $responseShow->assertSee('Disetujui');
        $responseShow->assertSee('https://meet.google.com/abc-defg-hij');

        $responsePrint = $this->actingAs($this->muridUser)->get(route('murid.konseling.printCard', $counseling));
        $responsePrint->assertOk();
        $responsePrint->assertSee('KARTU BUKTI LAYANAN KONSELING SISWA');
        $responsePrint->assertSee('https://meet.google.com/abc-defg-hij');

        // 4. Setelah sesi dilaksanakan, Admin/BK menandai Sudah Tertangani
        $responseComplete = $this->actingAs($this->counselorUser)->post(route('panel.konseling.complete', $counseling), [
            'resolution_notes' => 'Sesi konseling online telah berlangsung dengan baik via Google Meet. Siswa berkomitmen mempraktikkan latihan pernapasan.',
            'duration_minutes' => 45,
            'activity_description' => 'Konseling online percaya diri',
            'evaluation_result' => 'Sangat baik',
            'follow_up_plan' => 'Pemantauan berkala',
        ]);

        $responseComplete->assertRedirect();
        $counseling->refresh();
        $this->assertEquals('completed', $counseling->status);
        $this->assertNotNull($counseling->completed_at);
        $this->assertEquals('Sudah Tertangani / Selesai', $counseling->status_label);
    }

    public function test_announcements_crud_and_display_across_dashboards(): void
    {
        // 1. Admin membuat pengumuman baru
        $response = $this->actingAs($this->adminUser)->post(route('panel.pengumuman.store'), [
            'title' => 'Pengumuman Ujian Tengah Semester',
            'content' => 'UTS akan dilaksanakan minggu depan.',
            'target_role' => 'all',
            'category' => 'penting',
            'is_pinned' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('panel.pengumuman.index'));
        $this->assertDatabaseHas('announcements', [
            'title' => 'Pengumuman Ujian Tengah Semester',
            'target_role' => 'all',
            'category' => 'penting',
        ]);

        // 2. Tampil di Dashboard Admin
        $respAdminDash = $this->actingAs($this->adminUser)->get(route('panel.dashboard'));
        $respAdminDash->assertOk();
        $respAdminDash->assertSee('Pengumuman Ujian Tengah Semester');

        // 3. Tampil di Dashboard Murid
        $respMuridDash = $this->actingAs($this->muridUser)->get(route('murid.dashboard'));
        $respMuridDash->assertOk();
        $respMuridDash->assertSee('Pengumuman Ujian Tengah Semester');
    }

    public function test_student_pamphlet_upload_validation_and_admin_moderation(): void
    {
        // 1. Validasi ukuran file > 1MB (1025 KB harus ditolak)
        $oversizedFile = UploadedFile::fake()->image('large_poster.jpg')->size(1025);
        $responseFail = $this->actingAs($this->muridUser)->post(route('murid.pamflet.store'), [
            'title' => 'Poster Terlalu Besar',
            'image' => $oversizedFile,
        ]);
        $responseFail->assertSessionHasErrors('image');

        // 2. Upload file valid (maks 1MB)
        $validFile = UploadedFile::fake()->image('poster_kreatif.png')->size(500);
        $responseSuccess = $this->actingAs($this->muridUser)->post(route('murid.pamflet.store'), [
            'title' => 'Poster Budaya Ramah Anak',
            'description' => 'Mari saling menyayangi antar sesama teman.',
            'image' => $validFile,
        ]);

        $responseSuccess->assertRedirect(route('murid.pamflet.index'));
        $this->assertDatabaseHas('student_pamphlets', [
            'student_id' => $this->student->id,
            'title' => 'Poster Budaya Ramah Anak',
            'status' => 'pending',
            'is_active' => false,
        ]);

        $pamphlet = StudentPamphlet::where('title', 'Poster Budaya Ramah Anak')->first();

        // 3. Admin moderasi: Setujui Pamflet
        $responseApprove = $this->actingAs($this->adminUser)->post(route('panel.pamflet-kreatifitas.approve', $pamphlet));
        $responseApprove->assertRedirect();

        $pamphlet->refresh();
        $this->assertEquals('disetujui', $pamphlet->status);
        $this->assertTrue($pamphlet->is_active);

        // 4. Terbit di Dashboard dan menampilkan Nama serta Kelas Murid
        $respDash = $this->actingAs($this->muridUser)->get(route('murid.dashboard'));
        $respDash->assertOk();
        $respDash->assertSee('Poster Budaya Ramah Anak');
        $respDash->assertSee($this->student->name);
        $respDash->assertSee($this->classroom->name);

        // 5. Admin tolak karya lain
        $rejectedFile = UploadedFile::fake()->image('poster_ditolak.png')->size(300);
        $this->actingAs($this->muridUser)->post(route('murid.pamflet.store'), [
            'title' => 'Poster Kurang Tepat',
            'image' => $rejectedFile,
        ]);
        $pamphlet2 = StudentPamphlet::where('title', 'Poster Kurang Tepat')->first();

        $responseReject = $this->actingAs($this->adminUser)->post(route('panel.pamflet-kreatifitas.reject', $pamphlet2), [
            'rejection_reason' => 'Gambar kurang jelas, mohon gunakan resolusi lebih tinggi.',
        ]);
        $responseReject->assertRedirect();
        $pamphlet2->refresh();
        $this->assertEquals('ditolak', $pamphlet2->status);
        $this->assertFalse($pamphlet2->is_active);
        $this->assertEquals('Gambar kurang jelas, mohon gunakan resolusi lebih tinggi.', $pamphlet2->rejection_reason);
    }
}
