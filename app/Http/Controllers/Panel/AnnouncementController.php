<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Daftar Pengumuman di Panel Admin
     */
    public function index(): View
    {
        $announcements = Announcement::with('author')
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(15);

        return view('panel.pengumuman.index', compact('announcements'));
    }

    /**
     * Simpan Pengumuman Baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:3072'],
            'target_role' => ['required', 'in:all,murid,guru_bk,wali_kelas'],
            'category' => ['required', 'in:penting,info,agenda,kegiatan'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $imagePath = $request->file('image')->store('announcements', $disk);
        }

        Announcement::create([
            'author_user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'target_role' => $validated['target_role'],
            'category' => $validated['category'],
            'is_pinned' => $request->boolean('is_pinned'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('panel.pengumuman.index')->with('success', 'Pengumuman baru berhasil diterbitkan.');
    }

    /**
     * Perbarui Pengumuman
     */
    public function update(Request $request, Announcement $pengumuman): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:3072'],
            'remove_image' => ['nullable', 'boolean'],
            'target_role' => ['required', 'in:all,murid,guru_bk,wali_kelas'],
            'category' => ['required', 'in:penting,info,agenda,kegiatan'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $imagePath = $pengumuman->image;

        if ($request->boolean('remove_image') && $imagePath) {
            Storage::disk($disk)->delete($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk($disk)->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('announcements', $disk);
        }

        $pengumuman->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'target_role' => $validated['target_role'],
            'category' => $validated['category'],
            'is_pinned' => $request->boolean('is_pinned'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('panel.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus Pengumuman
     */
    public function destroy(Announcement $pengumuman): RedirectResponse
    {
        if ($pengumuman->image) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            Storage::disk($disk)->delete($pengumuman->image);
        }

        $pengumuman->delete();

        return redirect()->route('panel.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
