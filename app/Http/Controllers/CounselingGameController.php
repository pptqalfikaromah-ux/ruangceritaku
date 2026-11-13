<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CounselingGameController extends Controller
{
    /**
     * Tampilkan Game Puzzle Gambar 5x5 di Panel Admin / Guru BK
     */
    public function panelIndex(): View
    {
        $user = Auth::user();
        $canManage = $user && in_array($user->role, ['admin', 'guru_bk']);

        return view('game.puzzle', [
            'layout' => 'layouts.panel',
            'roleType' => 'panel',
            'roleLabel' => 'Panel Admin & Guru BK',
            'puzzleImageUrl' => puzzle_image_url(),
            'canManage' => $canManage,
            'hasCustomImage' => (bool) app_setting('puzzle_image'),
        ]);
    }

    /**
     * Tampilkan Game Puzzle Gambar 5x5 di Portal Wali Kelas
     */
    public function waliKelasIndex(): View
    {
        return view('game.puzzle', [
            'layout' => 'layouts.walikelas',
            'roleType' => 'walikelas',
            'roleLabel' => 'Portal Wali Kelas',
            'puzzleImageUrl' => puzzle_image_url(),
            'canManage' => false,
            'hasCustomImage' => (bool) app_setting('puzzle_image'),
        ]);
    }

    /**
     * Tampilkan Game Puzzle Gambar 5x5 di Portal Murid
     */
    public function studentIndex(): View
    {
        return view('game.puzzle', [
            'layout' => 'layouts.student',
            'roleType' => 'murid',
            'roleLabel' => 'Portal Murid',
            'puzzleImageUrl' => puzzle_image_url(),
            'canManage' => false,
            'hasCustomImage' => (bool) app_setting('puzzle_image'),
        ]);
    }

    /**
     * Upload & Ubah Gambar Puzzle oleh Admin / Guru BK
     */
    public function updatePuzzleImage(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'guru_bk']), 403, 'Akses ditolak.');

        $request->validate([
            'puzzle_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ], [
            'puzzle_image.required' => 'Pilih file gambar puzzle terlebih dahulu.',
            'puzzle_image.image' => 'File harus berupa gambar.',
            'puzzle_image.mimes' => 'Format gambar yang diperbolehkan adalah JPG, PNG, WEBP, atau GIF.',
            'puzzle_image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $oldImage = app_setting('puzzle_image');
        if ($oldImage && Storage::disk($disk)->exists($oldImage)) {
            Storage::disk($disk)->delete($oldImage);
        }

        $path = $request->file('puzzle_image')->store('images/puzzle', $disk);
        Setting::set('puzzle_image', $path, 'games', 'Gambar aktif untuk Game Puzzle 5x5');
        Cache::forget('app_settings_all');

        return back()->with('success', 'Gambar puzzle 5x5 berhasil diperbarui! Seluruh murid, guru, dan wali kelas kini dapat memainkan puzzle gambar ini.');
    }

    /**
     * Kembalikan Gambar Puzzle ke Gambar Default
     */
    public function resetPuzzleImage(): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'guru_bk']), 403, 'Akses ditolak.');

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $oldImage = app_setting('puzzle_image');
        if ($oldImage && Storage::disk($disk)->exists($oldImage)) {
            Storage::disk($disk)->delete($oldImage);
        }

        Setting::where('key', 'puzzle_image')->delete();
        Cache::forget('app_settings_all');

        return back()->with('success', 'Gambar puzzle berhasil dikembalikan ke gambar default bawaan.');
    }
}
