<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_access_puzzle_game(): void
    {
        $response = $this->actingAs($this->admin)->get(route('panel.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Game Puzzle Gambar 5x5');
        $response->assertSee('Kotak 5x5 (25 Keping)');
        $response->assertSee('puzzleBoard');
        $response->assertSee('Ganti Gambar Puzzle (Admin)');
    }

    public function test_walikelas_can_access_puzzle_game(): void
    {
        $response = $this->actingAs($this->waliKelas)->get(route('walikelas.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Game Puzzle Gambar 5x5');
        $response->assertSee('Portal Wali Kelas');
        $response->assertSee('puzzleBoard');
    }

    public function test_student_can_access_puzzle_game(): void
    {
        $response = $this->actingAs($this->studentUser)->get(route('murid.game-konseling.index'));

        $response->assertStatus(200);
        $response->assertSee('Game Puzzle Gambar 5x5');
        $response->assertSee('Portal Murid');
        $response->assertSee('puzzleBoard');
    }

    public function test_admin_can_upload_and_reset_puzzle_image(): void
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('custom_puzzle.jpg', 600, 600);

        // Upload custom image
        $uploadRes = $this->actingAs($this->admin)->post(route('panel.game-konseling.upload'), [
            'puzzle_image' => $fakeImage,
        ]);

        $uploadRes->assertRedirect();
        $this->assertNotEmpty(app_setting('puzzle_image'));

        // Reset custom image
        $resetRes = $this->actingAs($this->admin)->post(route('panel.game-konseling.reset'));
        $resetRes->assertRedirect();
        $this->assertNull(app_setting('puzzle_image'));
    }
}
