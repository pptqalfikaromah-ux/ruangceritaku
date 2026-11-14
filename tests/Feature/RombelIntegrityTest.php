<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

class RombelIntegrityTest extends BaseTestCase
{
    use CreatesApplication;

    protected User $admin;
    protected User $guruBk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin')->first()
            ?? User::where('username', 'admin_test')->first()
            ?? User::create(['username' => 'admin_test', 'name' => 'Admin Test', 'email' => 'admin_test@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]);

        $this->guruBk = User::where('role', 'guru_bk')->first()
            ?? User::where('username', 'gurubk_test')->first()
            ?? User::create(['username' => 'gurubk_test', 'name' => 'Guru BK Test', 'email' => 'gurubk_test@test.com', 'password' => bcrypt('password'), 'role' => 'guru_bk', 'is_active' => true]);
    }

    public function test_admin_can_access_rombel_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/panel/rombel');
        $response->assertStatus(200);
        $response->assertSee('Data Rombongan Belajar', false);
    }

    public function test_guru_bk_can_access_rombel_index(): void
    {
        $response = $this->actingAs($this->guruBk)->get('/panel/rombel');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_rombel_create_via_both_routes(): void
    {
        // 1. Direct standard REST URL /panel/rombel/create
        $resStandard = $this->actingAs($this->admin)->get('/panel/rombel/create');
        $resStandard->assertStatus(200);
        $resStandard->assertSee('Tambah Rombel Baru', false);

        // 2. Named route /panel/rombel-tambah/baru
        $resAlias = $this->actingAs($this->admin)->get(route('panel.rombel.create'));
        $resAlias->assertStatus(200);
        $resAlias->assertSee('Tambah Rombel Baru', false);
    }

    public function test_all_existing_classrooms_render_show_without_error(): void
    {
        $classrooms = Classroom::all();
        $this->assertNotEmpty($classrooms);

        foreach ($classrooms as $rombel) {
            $response = $this->actingAs($this->admin)->get(route('panel.rombel.show', $rombel));
            $response->assertStatus(200);
            $response->assertSee($rombel->name, false);
        }
    }

    public function test_all_existing_classrooms_render_edit_without_error(): void
    {
        $classrooms = Classroom::all();

        foreach ($classrooms as $rombel) {
            $response = $this->actingAs($this->admin)->get(route('panel.rombel.edit', $rombel));
            $response->assertStatus(200);
            $response->assertSee('Ubah Data Rombel', false);
        }
    }
}
