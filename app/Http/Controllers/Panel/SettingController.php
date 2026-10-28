<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Halaman Pengaturan Sekolah & Aplikasi (Khusus Admin)
     */
    public function index(): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('panel.setting.index');
    }

    /**
     * Simpan Pengaturan Sekolah & Aplikasi (Khusus Admin)
     */
    public function updateSettings(SettingRequest $request): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $keys = [
            'school_name',
            'school_npsn',
            'school_level',
            'school_address',
            'school_phone',
            'school_email',
            'headmaster_name',
            'headmaster_nip',
            'app_name',
            'theme_color',
            'app_footer',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Upload logo pengganti jika ada
        if ($request->hasFile('app_logo')) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $oldLogo = app_setting('app_logo');
            if ($oldLogo && Storage::disk($disk)->exists($oldLogo)) {
                Storage::disk($disk)->delete($oldLogo);
            }

            $path = $request->file('app_logo')->store('images/logos', $disk);
            Setting::set('app_logo', $path);
        }

        return back()->with('success', 'Pengaturan sekolah dan aplikasi berhasil diperbarui.');
    }

    /**
     * Halaman Profil Saya (Semua Role Panel)
     */
    public function profile(): View
    {
        $user = Auth::user();
        $counselor = $user->counselor;
        $teacher = $user->teacher;

        return view('panel.setting.profile', compact('user', 'counselor', 'teacher'));
    }

    /**
     * Update Profil Saya (Semua Role Panel)
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'nip_nuptk' => ['nullable', 'string', 'max:50'],
            'title' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:L,P'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $uploadedPhoto = null;
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo')->store('photos/counselors', $disk);
        }

        if ($user->counselor) {
            $counselorData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'title' => $request->title,
                'nip_nuptk' => $request->nip_nuptk,
                'gender' => $request->gender ?? $user->counselor->gender,
            ];

            if ($uploadedPhoto) {
                if ($user->counselor->photo && Storage::disk($disk)->exists($user->counselor->photo)) {
                    Storage::disk($disk)->delete($user->counselor->photo);
                }
                $counselorData['photo'] = $uploadedPhoto;
            }

            $user->counselor->update($counselorData);
        }

        if ($user->teacher) {
            $teacherData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'nip_nuptk' => $request->nip_nuptk,
                'gender' => $request->gender ?? $user->teacher->gender,
            ];

            if ($uploadedPhoto) {
                if ($user->teacher->photo && Storage::disk($disk)->exists($user->teacher->photo)) {
                    Storage::disk($disk)->delete($user->teacher->photo);
                }
                $teacherData['photo'] = $uploadedPhoto;
            }

            $user->teacher->update($teacherData);
        }

        return back()->with('success', 'Profil Anda berhasil diperbarui secara lengkap.');
    }

    /**
     * Form Ganti Password (Wajib untuk must_change_password & manual)
     */
    public function changePassword(): View
    {
        $user = Auth::user();

        return view('panel.setting.change-password', compact('user'));
    }

    /**
     * Simpan Password Baru
     */
    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('panel.dashboard')
            ->with('success', 'Password Anda berhasil diperbarui.');
    }
}
