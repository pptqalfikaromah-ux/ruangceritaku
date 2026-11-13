<?php

namespace Tests\Feature;

use App\Models\Counselor;
use App\Models\GroupCounseling;
use App\Models\GroupCounselingRequest;
use App\Models\Setting;
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

    protected function tearDown(): void
    {
        // Bersihkan data uji agar database remote tetap bersih
        GroupCounselingRequest::where('student_id', $this->student->id)->delete();
        GroupCounselingRequest::where('topic', 'like', '%Tes%')->orWhere('topic', 'like', '%Kiat%')->orWhere('ticket_number', 'like', '%REQ%')->delete();
        $testGroups = GroupCounseling::where('topic', 'like', '%Tes%')->orWhere('ticket_number', 'like', '%TEST%')->get();
        foreach ($testGroups as $g) {
            $g->members()->detach();
            $g->delete();
        }

        Setting::set('ims_enabled', '1');
        Setting::set('bkp_enabled', '1');
        Setting::set('kkp_enabled', '1');

        parent::tearDown();
    }

    public function test_student_zodiac_calculation(): void
    {
        // Tes zodiak student berdasarkan tanggal lahir
        $this->student->update(['birth_date' => '2010-03-25']); // Aries
        $this->assertEquals('Aries', $this->student->zodiac_name);
        $this->assertEquals('♈', $this->student->zodiac_icon);
        $this->assertEquals('♈ Aries', $this->student->zodiac_label);

        $this->student->update(['birth_date' => '2010-07-10']); // Cancer
        $this->assertEquals('Cancer', $this->student->zodiac_name);
        $this->assertEquals('♋', $this->student->zodiac_icon);
    }

    public function test_admin_can_toggle_bkp_and_kkp_settings(): void
    {
        // Nonaktifkan BKP, aktifkan KKP
        $response = $this->actingAs($this->admin)->put(route('panel.setting.update'), [
            'school_name' => 'Sekolah Test',
            'ims_enabled' => '1',
            'kkp_enabled' => '1',
            // bkp_enabled omitted -> should become '0'
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('0', app_setting('bkp_enabled'));
        $this->assertEquals('1', app_setting('kkp_enabled'));

        // Reset keduanya aktif
        Setting::set('ims_enabled', '1');
        Setting::set('bkp_enabled', '1');
        Setting::set('kkp_enabled', '1');
    }

    public function test_student_can_submit_single_group_counseling_request(): void
    {
        Setting::set('bkp_enabled', '1');
        Setting::set('kkp_enabled', '1');

        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $response = $this->actingAs($this->studentUser)->post(route('murid.layanan-kelompok.store'), [
            'service_type' => 'bimbingan_kelompok',
            'topic' => 'Kiat Manajemen Waktu dan Belajar Efektif (Tes)',
            'description' => 'Saya merasa kesulitan mengatur waktu belajar antara hafalan dan tugas sekolah.',
            'preferred_date' => $tomorrow,
            'preferred_session' => 'Istirahat Pertama (09:30 - 10:00)',
        ]);

        $response->assertRedirect(route('murid.layanan-kelompok.index', ['type' => 'bimbingan_kelompok']));

        $req = GroupCounselingRequest::where('topic', 'Kiat Manajemen Waktu dan Belajar Efektif (Tes)')->first();
        $this->assertNotNull($req);
        $this->assertEquals('bimbingan_kelompok', $req->service_type);
        $this->assertEquals('menunggu_kelompok', $req->status);
        $this->assertEquals($this->student->id, $req->student_id);
    }

    public function test_admin_can_group_students_and_student_receives_notification(): void
    {
        // 1. Buat pengajuan mandiri siswa
        $req = GroupCounselingRequest::create([
            'ticket_number' => 'REQ-TEST-001',
            'student_id' => $this->student->id,
            'service_type' => 'bimbingan_kelompok',
            'topic' => 'Topik Tes Pengelompokan',
            'description' => 'Permasalahan tes siswa',
            'status' => 'menunggu_kelompok',
        ]);

        // 2. Admin membuka halaman panel
        $responseIndex = $this->actingAs($this->admin)->get(route('panel.layanan-kelompok.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('REQ-TEST-001');

        // 3. Admin membentuk kelompok & menetapkan jadwal
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $responseForm = $this->actingAs($this->admin)->post(route('panel.layanan-kelompok.form-group'), [
            'service_type' => 'bimbingan_kelompok',
            'topic' => 'Sesi Terjadwal Kelompok Belajar (Tes)',
            'description' => 'Kelompok bimbingan bersama guru BK',
            'student_ids' => [$this->student->id, $this->memberStudent->id],
            'request_ids' => [$req->id],
            'counselor_id' => $this->counselor->id,
            'scheduled_date' => $tomorrow,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'location' => 'Ruang BK Utama',
        ]);

        $group = GroupCounseling::where('topic', 'Sesi Terjadwal Kelompok Belajar (Tes)')->first();
        $this->assertNotNull($group);
        $responseForm->assertRedirect(route('panel.layanan-kelompok.show', $group));

        // Verifikasi anggota & request terupdate
        $this->assertTrue($group->members()->where('students.id', $this->student->id)->exists());
        $this->assertTrue($group->members()->where('students.id', $this->memberStudent->id)->exists());
        $this->assertEquals('dikelompokkan', $req->fresh()->status);
        $this->assertEquals($group->id, $req->fresh()->group_counseling_id);

        // 4. Verifikasi notifikasi muncul di Dashboard Murid
        $responseDashboard = $this->actingAs($this->studentUser)->get(route('murid.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Jadwal &amp; Anggota Kelompok Anda Telah Ditetapkan!', false);
        $responseDashboard->assertSee('Sesi Terjadwal Kelompok Belajar (Tes)');
        $responseDashboard->assertSee($this->memberStudent->name);
    }

    public function test_student_can_submit_with_single_click_without_inputs(): void
    {
        Setting::set('bkp_enabled', '1');
        Setting::set('kkp_enabled', '1');

        $response = $this->actingAs($this->studentUser)->post(route('murid.layanan-kelompok.store'), [
            'service_type' => 'konseling_kelompok',
        ]);

        $response->assertRedirect(route('murid.layanan-kelompok.index', ['type' => 'konseling_kelompok']));

        $req = GroupCounselingRequest::where('student_id', $this->student->id)
            ->where('service_type', 'konseling_kelompok')
            ->first();

        $this->assertNotNull($req);
        $this->assertEquals('konseling_kelompok', $req->service_type);
        $this->assertEquals('menunggu_kelompok', $req->status);
        $this->assertNotEmpty($req->topic);

        // Uji pembatalan pengajuan mandiri
        $cancelResponse = $this->actingAs($this->studentUser)->delete(route('murid.layanan-kelompok.cancel-request', $req->id));
        $cancelResponse->assertRedirect(route('murid.layanan-kelompok.index', ['type' => 'konseling_kelompok']));
        $this->assertNull($req->fresh());
    }
}
