<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CounselingGameFeatureTest extends TestCase
{
    protected User $admin;
    protected User $waliKelas;
    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

        $this->admin = User::firstOrCreate(
            ['username' => 'admin_test'],
            ['name' => 'Admin Test', 'email' => 'admin_test@test.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
        );

        $waliUser = User::where('role', 'wali_kelas')->where('is_active', true)->first();
        if (!$waliUser) {
            $waliUser = User::create([
                'username' => 'walikelas_test',
                'name' => 'Wali Kelas Test',
                'email' => 'walikelas_test@test.com',
                'password' => bcrypt('password'),
                'role' => 'wali_kelas',
                'is_active' => true,
            ]);
        }
        $this->waliKelas = $waliUser;

        $this->studentUser = User::firstOrCreate(
            ['username' => 'murid_test'],
            ['name' => 'Murid Test', 'email' => 'murid_test@test.com', 'password' => bcrypt('password'), 'role' => 'murid', 'is_active' => true]
        );
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('panel.game-konseling.index'))->assertRedirect(route('login'));
        $this->get(route('walikelas.game-konseling.index'))->assertRedirect(route('login'));
        $this->get(route('murid.game-konseling.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_access_counseling_game(): void
    {
        $response = $this->actingAs($this->admin)->get(route('panel.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Ruang Rasa');
        $response->assertSee('Kartu Ruang Rasa');
        $response->assertSee('Gelembung Beban');
        $response->assertSee('Pilihan Bijak');
    }

    public function test_walikelas_can_access_counseling_game(): void
    {
        $response = $this->actingAs($this->waliKelas)->get(route('walikelas.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Ruang Rasa');
        $response->assertSee('Portal Wali Kelas');
        $response->assertSee('Kartu Ruang Rasa');
        $response->assertSee('Gelembung Beban');
    }

    public function test_student_can_access_counseling_game(): void
    {
        $response = $this->actingAs($this->studentUser)->get(route('murid.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Ruang Rasa');
        $response->assertSee('Portal Murid');
        $response->assertSee('Kartu Ruang Rasa');
        $response->assertSee('Gelembung Beban');
        $response->assertSee('Panduan Napas Rileks');
    }
}
