<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\HomeroomComplaint;
use App\Models\SpinnerParticipant;
use App\Models\Student;
use App\Models\StudentComplaint;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SpinnerAndComplaintManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_admin_can_access_spinner_page_and_see_default_participants(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin']);
        }

        $response = $this->actingAs($admin)->get(route('panel.spinner.index'));
        $response->assertStatus(200);
        $response->assertSee('Roda Undian');
        $response->assertSee('SPIN');
        $response->assertSee('Muslih Nurwanto');
        $response->assertSee('Anis Labib');
    }

    public function test_admin_can_update_spinner_participants(): void
    {
        $admin = User::where('role', 'admin')->first();

        $customNames = "Ahmad\nBudi\nCitra\nDewi";

        $response = $this->actingAs($admin)->post(route('panel.spinner.update-participants'), [
            'names_text' => $customNames,
        ]);

        $response->assertRedirect(route('panel.spinner.index'));
        $this->assertEquals(4, SpinnerParticipant::count());

        // Reset kembali ke bawaan agar data bersih
        $this->actingAs($admin)->post(route('panel.spinner.reset'));
        $this->assertEquals(48, SpinnerParticipant::count());
    }

    public function test_complaint_can_be_deleted_from_panel(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Buat data aduan dummy sementara
        $complaint = StudentComplaint::create([
            'ticket_number' => 'TEST-DEL-' . uniqid(),
            'reporter_type' => 'student',
            'reporter_name' => 'Murid Uji Coba',
            'reporter_contact' => '08123456789',
            'category' => 'Perundungan / Bullying',
            'description' => 'Aduan uji coba untuk dites hapus',
            'incident_location' => 'Sekolah',
            'status' => 'pending',
            'safety_risk_flag' => false,
        ]);

        $response = $this->actingAs($admin)->delete(route('panel.pengaduan.destroy', $complaint->id));
        $response->assertRedirect();

        $this->assertSoftDeleted('student_complaints', ['id' => $complaint->id]);

        // Bersihkan data tes secara permanen
        $complaint->forceDelete();
    }

    public function test_homeroom_complaint_can_be_deleted_from_panel(): void
    {
        $admin = User::where('role', 'admin')->first();
        $teacher = Teacher::first();
        $classroom = Classroom::first();
        $student = Student::first();

        $homeroomComplaint = HomeroomComplaint::create([
            'teacher_id' => $teacher?->id ?? 1,
            'classroom_id' => $classroom?->id ?? 1,
            'student_id' => $student?->id ?? 1,
            'title' => 'Uji Kasus Hapus',
            'description' => 'Deskripsi uji kasus hapus',
            'category' => 'pelanggaran_aturan',
            'initial_action' => 'Diberikan teguran',
            'teacher_notes' => 'Catatan tes hapus',
            'incident_date' => now(),
            'status' => 'menunggu_tindak_lanjut',
        ]);

        $this->assertDatabaseHas('homeroom_complaints', ['id' => $homeroomComplaint->id]);

        $response = $this->actingAs($admin)->delete(route('panel.aduan-walikelas.destroy', $homeroomComplaint->id));
        $response->assertRedirect(route('panel.aduan-walikelas.index'));

        $this->assertDatabaseMissing('homeroom_complaints', ['id' => $homeroomComplaint->id]);
    }
}
