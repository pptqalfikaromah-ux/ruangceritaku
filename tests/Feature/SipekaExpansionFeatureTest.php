<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\Counselor;
use App\Models\GuidanceField;
use App\Models\ImsStatement;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\Student;
use App\Models\StudentComplaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SipekaExpansionFeatureTest extends TestCase
{
    private User $admin;
    private User $guruBk;
    private User $muridUser;
    private Student $student;
    private Counselor $counselor;
    private AcademicYear $year;
    private Semester $semester;
    private Classroom $classroom;

    protected function setUp(): void
    {
        parent::setUp();

        $this->year = AcademicYear::firstOrCreate(
            ['name' => '2024/2025'],
            ['start_year' => 2024, 'end_year' => 2025, 'is_active' => true]
        );

        $this->semester = Semester::firstOrCreate(
            ['academic_year_id' => $this->year->id, 'name' => 'Ganjil'],
            ['is_active' => true]
        );

        $this->classroom = Classroom::firstOrCreate(
            ['name' => 'VII A', 'academic_year_id' => $this->year->id],
            ['level' => '7', 'capacity' => 32]
        );

        $this->admin = User::firstOrCreate(
            ['username' => 'admin_test'],
            [
                'name' => 'Administrator Test',
                'email' => 'admin_test@sipeka.sch.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        $guruUser = User::firstOrCreate(
            ['username' => 'gurubk_test'],
            [
                'name' => 'Ibu Siti Rahma, S.Pd.',
                'email' => 'siti_test@sipeka.sch.id',
                'password' => Hash::make('password123'),
                'role' => 'guru_bk',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );
        $this->guruBk = $guruUser;

        $this->counselor = Counselor::firstOrCreate(
            ['user_id' => $guruUser->id],
            [
                'nip' => '198501012010012001',
                'name' => 'Ibu Siti Rahma, S.Pd.',
                'gender' => 'P',
                'status' => 'aktif',
            ]
        );

        $this->muridUser = User::firstOrCreate(
            ['username' => 'murid_test'],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'fauzi_test@siswa.sipeka.sch.id',
                'password' => Hash::make('password123'),
                'role' => 'murid',
                'is_active' => true,
                'must_change_password' => false,
            ]
        );

        $this->student = Student::where('user_id', $this->muridUser->id)->first()
            ?? Student::firstOrCreate(
                ['nisn' => '0098765432'],
                [
                    'user_id' => $this->muridUser->id,
                    'nis' => '12345',
                    'name' => 'Ahmad Fauzi',
                    'gender' => 'L',
                    'status' => 'aktif',
                ]
            );
        $this->student->update(['user_id' => $this->muridUser->id]);
        Student::where('user_id', $this->muridUser->id)->where('id', '!=', $this->student->id)->delete();
        $this->student->refresh();
        $this->muridUser->refresh();

        if (!$this->student->classrooms()->where('classroom_id', $this->classroom->id)->exists()) {
            $this->student->classrooms()->attach($this->classroom->id, [
                'academic_year_id' => $this->year->id,
            ]);
        }
    }

    public function test_ims_master_data_viewable_and_filtered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('panel.ims.master'));
        $response->assertStatus(200);
        $response->assertSee('Master Instrumen Masalah Siswa (IMS)');
        $response->assertSee('180 Butir Standar BK');
    }

    public function test_ims_monitoring_and_rekap_accessible(): void
    {
        $responseMon = $this->actingAs($this->guruBk)->get(route('panel.ims.monitoring'));
        $responseMon->assertStatus(200);
        $responseMon->assertSee('Monitoring Pengisian IMS');

        $responseRek = $this->actingAs($this->guruBk)->get(route('panel.ims.rekap'));
        $responseRek->assertStatus(200);
        $responseRek->assertSee('Rekapitulasi Instrumen Masalah Siswa');
    }

    public function test_student_ims_tiered_page_flow(): void
    {
        \App\Models\ImsAssessment::where('student_id', $this->student->id)->forceDelete();

        // 1. Halaman 1: Pilih Layanan
        $resLayanan = $this->actingAs($this->muridUser)->get(route('murid.ims.index'));
        $resLayanan->assertStatus(200);
        $resLayanan->assertSee('Pilih Menu Layanan Konseling');
        $resLayanan->assertSee('Orientasi');

        // 2. Halaman 2: Pilih Bidang untuk Layanan Orientasi
        $orientasi = ServiceType::where('name', 'like', '%Orientasi%')->firstOrFail();
        $resBidang = $this->actingAs($this->muridUser)->get(route('murid.ims.service', $orientasi->id));
        $resBidang->assertStatus(200);
        $resBidang->assertSee('Layanan Orientasi');
        $resBidang->assertSee('Bidang Pribadi');
        $resBidang->assertSee('Bidang Karier');

        // 3. Halaman 3: Instrumen 5 Butir & Catatan Lebar (Orientasi + Karir -> butir 16-20)
        $karier = GuidanceField::where('name', 'Karier')->firstOrFail();
        $resInstrumen = $this->actingAs($this->muridUser)->get(route('murid.ims.instrument', [$orientasi->id, $karier->id]));
        $resInstrumen->assertStatus(200);
        $resInstrumen->assertSee('Bidang Karier');
        $resInstrumen->assertSee('Membutuhkan pengenalan ekstrakurikuler yang menunjang belajar saya');
        $resInstrumen->assertSee('Catatan Siswa untuk Masalah di Atas');

        // 4. Simpan instrumen dan catatan lebar
        $stmtKarir = ImsStatement::where('service_type_id', $orientasi->id)->where('guidance_field_id', $karier->id)->firstOrFail();
        $resSave = $this->actingAs($this->muridUser)->post(route('murid.ims.saveInstrument', [$orientasi->id, $karier->id]), [
            'selected' => [$stmtKarir->id],
            'wide_note' => 'Saya ingin tahu ekstrakurikuler yang menunjang cita-cita kedokteran.',
            'scope_statement_ids' => [$stmtKarir->id],
        ]);
        $resSave->assertRedirect(route('murid.ims.instrument', [$orientasi->id, $karier->id]));
        $resSave->assertSessionHas('success');
    }

    public function test_student_can_fill_ims_draft_and_submit(): void
    {
        \App\Models\ImsAssessment::where('student_id', $this->student->id)->forceDelete();

        $stmt = ImsStatement::first();
        $this->assertNotNull($stmt);

        // Save Draft
        $responseDraft = $this->actingAs($this->muridUser)->post(route('murid.ims.saveDraft'), [
            'selected' => [$stmt->id],
            'notes' => [$stmt->id => 'Saya merasa cemas saat berbicara di depan kelas.'],
            'final_notes' => 'Tolong dijadwalkan sesi konseling ya bu.',
        ]);
        $responseDraft->assertRedirect();
        $responseDraft->assertSessionHas('success');

        // Submit Final
        $responseSubmit = $this->actingAs($this->muridUser)->post(route('murid.ims.submit'), [
            'selected' => [$stmt->id],
            'notes' => [$stmt->id => 'Saya merasa cemas saat berbicara di depan kelas.'],
            'final_notes' => 'Tolong dijadwalkan sesi konseling ya bu.',
        ]);
        $responseSubmit->assertRedirect(route('murid.ims.index'));
        $responseSubmit->assertSessionHas('success');

        $this->assertDatabaseHas('ims_assessments', [
            'student_id' => $this->student->id,
            'status' => 'submitted',
        ]);
    }

    public function test_student_can_request_counseling_directly_from_ims(): void
    {
        \App\Models\ImsAssessment::where('student_id', $this->student->id)->forceDelete();
        \App\Models\CounselingRequest::where('student_id', $this->student->id)->forceDelete();

        $stmt = ImsStatement::first();

        // 1. Pilih masalah di IMS dan pilih "Simpan & Pilih Jadwal Konseling IMS"
        $responseIms = $this->actingAs($this->muridUser)->post(route('murid.ims.saveDraft'), [
            'action' => 'counseling',
            'selected' => [$stmt->id],
            'notes' => [$stmt->id => 'Saya butuh solusi terkait hal ini segera.'],
            'final_notes' => 'Catatan umum dari IMS.',
        ]);

        $responseIms->assertRedirect(route('murid.ims.counseling.create'));

        // 2. Buka halaman khusus pemilihan jadwal konseling IMS
        $formResponse = $this->actingAs($this->muridUser)->get(route('murid.ims.counseling.create'));
        $formResponse->assertStatus(200);
        $formResponse->assertSee('Tindak Lanjut Instrumen Masalah (IMS)');
        $formResponse->assertSee($stmt->statement_text);

        // 3. Simpan usulan jadwal konseling IMS
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $submitResponse = $this->actingAs($this->muridUser)->post(route('murid.ims.counseling.store'), [
            'preferred_date' => $tomorrow,
            'preferred_session' => 'Istirahat Pertama (Sekitar 09:30 - 10:00)',
            'student_expectation' => 'Mohon dipanggil saat jam istirahat saja ya bu.',
        ]);

        $submitResponse->assertRedirect(route('murid.konseling.index'));
        $submitResponse->assertSessionHas('success');

        $this->assertDatabaseHas('counseling_requests', [
            'student_id' => $this->student->id,
            'source' => 'ims',
            'purpose' => 'Konseling Tindak Lanjut Masalah IMS',
        ]);
    }

    public function test_student_counseling_request_lifecycle(): void
    {
        $field = GuidanceField::first();
        $service = ServiceType::layanan()->first();

        // 1. Murid mengajukan konseling mandiri
        $responseCreate = $this->actingAs($this->muridUser)->post(route('murid.konseling.store'), [
            'purpose' => 'Konsultasi Kesulitan Belajar Matematika',
            'guidance_field_id' => $field?->id,
            'service_type_id' => $service?->id,
            'description' => 'Saya merasa sangat kesulitan memahami rumus dan ingin belajar teknik membagi waktu belajar.',
            'preferred_schedule' => 'Rabu siang jam istirahat',
        ]);
        $responseCreate->assertRedirect(route('murid.konseling.index'));
        $responseCreate->assertSessionHas('success');

        $counseling = CounselingRequest::where('student_id', $this->student->id)->latest()->first();
        $this->assertNotNull($counseling);
        $this->assertStringStartsWith('AGD-', $counseling->ticket_number);
        $this->assertEquals('pending', $counseling->status);

        // 2. Pencegahan pengajuan ganda untuk topik yang sama saat pending
        $responseDuplicate = $this->actingAs($this->muridUser)->post(route('murid.konseling.store'), [
            'purpose' => 'Konsultasi Kesulitan Belajar Matematika',
            'description' => 'Mencoba submit lagi topik yang sama',
        ]);
        $responseDuplicate->assertSessionHas('error');

        // 3. Guru BK meninjau dan menetapkan jadwal
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $responseSchedule = $this->actingAs($this->guruBk)->post(route('panel.konseling.schedule', $counseling), [
            'counselor_id' => $this->counselor->id,
            'scheduled_date' => $tomorrow,
            'start_time' => '10:00',
            'end_time' => '10:45',
            'location' => 'Ruang BK Lantai 1',
            'notes' => 'Membawa buku catatan tugas',
        ]);
        $responseSchedule->assertRedirect();
        $responseSchedule->assertSessionHas('success');

        $counseling->refresh();
        $this->assertEquals('scheduled', $counseling->status);
        $this->assertNotNull($counseling->schedule);

        // Murid dapat mencetak kartu konseling (PDF/Print View)
        $responsePrint = $this->actingAs($this->muridUser)->get(route('murid.konseling.printCard', $counseling));
        $responsePrint->assertOk();
        $responsePrint->assertSee('KARTU BUKTI LAYANAN KONSELING SISWA');

        // 5. Konselor menyelesaikan sesi konseling
        $responseComplete = $this->actingAs($this->guruBk)->post(route('panel.konseling.complete', $counseling), [
            'duration_minutes' => 45,
            'activity_description' => 'Konseli menyampaikan kendala belajar dan disepakati jadwal belajar mandiri 30 menit sehari.',
            'evaluation_result' => 'Konseli merasa lebih tenang dan memahami langkah belajar yang tepat.',
            'follow_up_plan' => 'Evaluasi 2 minggu ke depan.',
        ]);
        $responseComplete->assertRedirect();
        $counseling->refresh();
        $this->assertEquals('completed', $counseling->status);
        $this->assertDatabaseHas('counseling_services', [
            'counseling_request_id' => $counseling->id,
            'target_student_id' => $this->student->id,
        ]);
    }

    public function test_student_complaint_and_safety_risk_flagging(): void
    {
        // 1. Murid membuat laporan perundungan dengan opsi anonim
        $responseReport = $this->actingAs($this->muridUser)->post(route('murid.pengaduan.store'), [
            'category' => 'Perundungan / Bullying',
            'urgency_level' => 'darurat',
            'incident_date' => date('Y-m-d'),
            'incident_location' => 'Belakang Kantin',
            'description' => 'Ada pemalakan uang saku dan dorongan fisik oleh kakak kelas.',
            'is_anonymous' => 1,
        ]);
        $responseReport->assertRedirect(route('murid.pengaduan.index'));

        $complaint = StudentComplaint::where('student_id', $this->student->id)->latest()->first();
        $this->assertNotNull($complaint);
        $this->assertFalse($complaint->is_anonymous);
        $this->assertEquals($this->student->name, $complaint->reporter_name);
        $this->assertTrue($complaint->safety_risk_flag); // Otomatis darurat/bullying ditandai risiko keselamatan

        // 2. Guru BK memeriksa dan menindaklanjuti
        $responseHandle = $this->actingAs($this->guruBk)->put(route('panel.pengaduan.status', $complaint), [
            'status' => 'investigating',
            'handled_by_counselor_id' => $this->counselor->id,
            'safety_risk_flag' => 1,
            'handling_notes' => 'Telah dilakukan observasi kantin dan koordinasi dengan waka kesiswaan.',
        ]);
        $responseHandle->assertRedirect();
        $complaint->refresh();
        $this->assertEquals('investigating', $complaint->status);
    }

    public function test_reports_and_print_view(): void
    {
        $response = $this->actingAs($this->admin)->get(route('panel.laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan & Rekapitulasi');

        $responsePrint = $this->actingAs($this->admin)->get(route('panel.laporan.print', ['type' => 'konseling']));
        $responsePrint->assertStatus(200);
        $responsePrint->assertSee('Kementerian Agama Republik Indonesia');
    }
}
