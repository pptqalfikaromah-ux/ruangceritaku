<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\Counselor;
use App\Models\GuidanceField;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\Student;
use App\Models\StudentComplaint;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;
use Tests\CreatesApplication;

class AllRoutesCrawlerTest extends BaseTestCase
{
    use CreatesApplication;

    protected User $admin;
    protected User $walas;
    protected User $murid;

    protected Classroom $classroom;
    protected Student $student;
    protected Teacher $teacher;
    protected ServiceType $service;
    protected GuidanceField $field;
    protected AcademicYear $year;
    protected Semester $semester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin')->first()
            ?? User::create(['name' => 'Admin', 'username' => 'admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]);

        $this->walas = User::where('role', 'wali_kelas')->first()
            ?? User::create(['name' => 'Walas', 'username' => 'walas', 'email' => 'walas@test.com', 'password' => bcrypt('password'), 'role' => 'wali_kelas', 'is_active' => true]);

        $this->murid = User::where('role', 'murid')->first()
            ?? User::create(['name' => 'Murid', 'username' => 'murid', 'email' => 'murid@test.com', 'password' => bcrypt('password'), 'role' => 'murid', 'is_active' => true]);

        $this->classroom = Classroom::first();
        $this->student = Student::first();
        $this->teacher = Teacher::first();
        $this->service = ServiceType::first();
        $this->field = GuidanceField::where('code', 'BID-01')->first() ?? GuidanceField::first();
        $this->year = AcademicYear::first();
        $this->semester = Semester::first();
    }

    public function test_penugasan_wali_kelas_page_renders_without_errors(): void
    {
        $res = $this->actingAs($this->admin)->get(route('panel.penugasan-wali-kelas.index'));
        $res->assertStatus(200);
        $res->assertSee('Penugasan Wali Kelas ke Rombel');
        $res->assertDontSee('ErrorException');
        $res->assertDontSee('Undefined variable');
    }

    public function test_all_admin_panel_get_routes_do_not_throw_errors(): void
    {
        $adminRoutes = [
            'panel.dashboard' => [],
            'panel.kelas.index' => [],
            'panel.kelas.create' => [],
            'panel.kelas.show' => ['kela' => $this->classroom->id],
            'panel.murid.index' => [],
            'panel.murid.create' => [],
            'panel.murid.show' => ['murid' => $this->student->id],
            'panel.guru-bk.index' => [],
            'panel.wali-kelas.index' => [],
            'panel.penugasan-wali-kelas.index' => [],
            'panel.layanan-master.index' => [],
            'panel.tahun-akademik.index' => [],
            'panel.semester.index' => [],
            'panel.program-bk.index' => [],
            'panel.program-bk.create' => [],
            'panel.rpl-bk.index' => [],
            'panel.rpl-bk.create' => [],
            'panel.ims.master' => [],
            'panel.ims.rekap' => [],
            'panel.pengaduan.index' => [],
            'panel.konseling.index' => [],
            'panel.pamflet-kreatifitas.index' => [],
            'panel.pengumuman.index' => [],
            'panel.pengumuman.create' => [],
            'panel.laporan.index' => [],
            'panel.settings.index' => [],
        ];

        foreach ($adminRoutes as $routeName => $params) {
            if (!Route::has($routeName)) continue;

            $url = route($routeName, $params);
            $response = $this->actingAs($this->admin)->get($url);

            // Assert no 500 error and no PHP/Blade error
            $this->assertNotEquals(500, $response->getStatusCode(), "Route {$routeName} ({$url}) returned 500 error!");
            $response->assertDontSee('ErrorException');
            $response->assertDontSee('Undefined variable');
            $response->assertDontSee('Cannot end a section');
        }
    }

    public function test_all_wali_kelas_get_routes_do_not_throw_errors(): void
    {
        $walasRoutes = [
            'walikelas.dashboard' => [],
            'walikelas.monitoring.index' => [],
            'walikelas.aduan.index' => [],
            'walikelas.aduan.create' => [],
        ];

        foreach ($walasRoutes as $routeName => $params) {
            if (!Route::has($routeName)) continue;

            $url = route($routeName, $params);
            $response = $this->actingAs($this->walas)->get($url);

            $this->assertNotEquals(500, $response->getStatusCode(), "Route {$routeName} ({$url}) returned 500 error!");
            $response->assertDontSee('ErrorException');
            $response->assertDontSee('Undefined variable');
            $response->assertDontSee('Cannot end a section');
        }
    }

    public function test_all_murid_get_routes_do_not_throw_errors(): void
    {
        $muridRoutes = [
            'murid.dashboard' => [],
            'murid.ims.index' => [],
            'murid.ims.selectField' => ['service' => $this->service->id],
            'murid.ims.instrument' => ['service' => $this->service->id, 'field' => $this->field->id],
            'murid.konseling.index' => [],
            'murid.pengaduan.index' => [],
            'murid.pengaduan.create' => [],
            'murid.pamflet.index' => [],
            'murid.pamflet.create' => [],
        ];

        foreach ($muridRoutes as $routeName => $params) {
            if (!Route::has($routeName)) continue;

            $url = route($routeName, $params);
            $response = $this->actingAs($this->murid)->get($url);

            $this->assertNotEquals(500, $response->getStatusCode(), "Route {$routeName} ({$url}) returned 500 error!");
            $response->assertDontSee('ErrorException');
            $response->assertDontSee('Undefined variable');
            $response->assertDontSee('Cannot end a section');
        }
    }
}
