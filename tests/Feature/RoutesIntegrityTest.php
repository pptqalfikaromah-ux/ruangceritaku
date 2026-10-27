<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\CounselingRequest;
use App\Models\GuidanceField;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\Student;
use App\Models\StudentComplaint;
use App\Models\StudentPamphlet;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

class RoutesIntegrityTest extends BaseTestCase
{
    use CreatesApplication;

    protected User $admin;
    protected User $guruBk;
    protected User $waliKelas;
    protected User $murid;
    protected Student $student;
    protected StudentComplaint $complaint;
    protected CounselingRequest $counseling;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['username' => 'admin_test'],
            ['name' => 'Admin Test', 'email' => 'admin_test@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
        );

        $this->guruBk = User::firstOrCreate(
            ['username' => 'gurubk_test'],
            ['name' => 'Guru BK Test', 'email' => 'gurubk_test@test.com', 'password' => bcrypt('password'), 'role' => 'guru_bk', 'is_active' => true]
        );

        $this->waliKelas = User::firstOrCreate(
            ['username' => 'walikelas_test'],
            ['name' => 'Wali Kelas Test', 'email' => 'walikelas_test@test.com', 'password' => bcrypt('password'), 'role' => 'wali_kelas', 'is_active' => true]
        );

        $this->murid = User::firstOrCreate(
            ['username' => 'murid_test'],
            ['name' => 'Murid Test', 'email' => 'murid_test@test.com', 'password' => bcrypt('password'), 'role' => 'murid', 'is_active' => true]
        );

        $this->student = $this->murid->student
            ?? Student::where('user_id', $this->murid->id)->first()
            ?? Student::first();
        $this->student->update(['user_id' => $this->murid->id]);
        $this->student->refresh();
        $this->murid->refresh();

        $year = AcademicYear::first() ?? AcademicYear::firstOrCreate(['name' => '2026/2027'], ['is_active' => true]);
        $classroom = Classroom::first() ?? Classroom::create(['name' => 'X-TEST', 'level' => 10, 'academic_year_id' => $year->id]);
        $this->student->classrooms()->syncWithoutDetaching([
            $classroom->id => ['academic_year_id' => $year->id],
        ]);

        $this->complaint = StudentComplaint::firstOrCreate(
            ['ticket_number' => 'PGD-TEST-001'],
            [
                'student_id' => $this->student->id,
                'category' => 'Perundungan / Bullying',
                'urgency_level' => 'darurat',
                'description' => 'Test laporan pengaduan',
                'status' => 'submitted',
                'is_anonymous' => false,
                'safety_risk_flag' => true,
            ]
        );
        $this->complaint->update(['student_id' => $this->student->id]);

        $guidance = GuidanceField::where('code', 'BID-01')->first()
            ?? GuidanceField::firstOrCreate(['code' => 'BID-01'], ['name' => 'Pribadi']);

        CounselingRequest::withTrashed()->where('ticket_number', 'AGD-TEST-001')->forceDelete();
        $this->counseling = CounselingRequest::create([
            'ticket_number' => 'AGD-TEST-001',
            'student_id' => $this->student->id,
            'guidance_field_id' => $guidance->id,
            'source' => 'pribadi',
            'purpose' => 'Konseling Test',
            'description' => 'Deskripsi masalah konseling test',
            'counseling_method' => 'tatap_muka',
            'status' => 'pending',
        ]);
    }

    public function test_student_pengaduan_pages_render_without_blade_or_logic_errors(): void
    {
        // 1. Index pengaduan
        $resIndex = $this->actingAs($this->murid)->get(route('murid.pengaduan.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Pengaduan & Perlindungan Diri', false);

        // 2. Form buat pengaduan
        $resCreate = $this->actingAs($this->murid)->get(route('murid.pengaduan.create'));
        $resCreate->assertStatus(200);
        $resCreate->assertSee('Formulir Pengaduan & Perlindungan Diri', false);

        // 3. Detail pengaduan
        $resShow = $this->actingAs($this->murid)->get(route('murid.pengaduan.show', $this->complaint));
        $resShow->assertStatus(200);
        $resShow->assertSee($this->complaint->ticket_number);
    }

    public function test_panel_pengaduan_pages_render_successfully(): void
    {
        // Admin view pengaduan index & show
        $resAdminIndex = $this->actingAs($this->admin)->get(route('panel.pengaduan.index'));
        $resAdminIndex->assertStatus(200);

        $resAdminShow = $this->actingAs($this->admin)->get(route('panel.pengaduan.show', $this->complaint));
        $resAdminShow->assertStatus(200);

        // Guru BK view pengaduan index & show
        $resBkIndex = $this->actingAs($this->guruBk)->get(route('panel.pengaduan.index'));
        $resBkIndex->assertStatus(200);

        $resBkShow = $this->actingAs($this->guruBk)->get(route('panel.pengaduan.show', $this->complaint));
        $resBkShow->assertStatus(200);
    }

    public function test_student_konseling_and_ims_views_render_successfully(): void
    {
        // To access create form, complete all existing pending requests for this student
        CounselingRequest::where('student_id', $this->student->id)
            ->whereIn('status', ['pending', 'scheduled'])
            ->update(['status' => 'completed']);

        $resKonseling = $this->actingAs($this->murid)->get(route('murid.konseling.index'));
        $resKonseling->assertStatus(200);

        $resAjukan = $this->actingAs($this->murid)->get(route('murid.konseling.create'));
        $resAjukan->assertRedirect(route('murid.ims.index'));

        $resIms = $this->actingAs($this->murid)->get(route('murid.ims.index'));
        $resIms->assertStatus(200);

        $resKreatifitas = $this->actingAs($this->murid)->get(route('murid.pamflet.index'));
        $resKreatifitas->assertStatus(200);

        $resUnggah = $this->actingAs($this->murid)->get(route('murid.pamflet.create'));
        $resUnggah->assertStatus(200);
    }

    public function test_panel_konseling_views_render_successfully(): void
    {
        $resIndex = $this->actingAs($this->admin)->get(route('panel.konseling.index'));
        $resIndex->assertStatus(200);

        $resShow = $this->actingAs($this->admin)->get(route('panel.konseling.show', $this->counseling));
        $resShow->assertStatus(200);
    }
}
