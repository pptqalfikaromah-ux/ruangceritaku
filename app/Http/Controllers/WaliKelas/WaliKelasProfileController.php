<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;

class WaliKelasProfileController extends Controller
{
    /**
     * Tampilkan Profil Wali Kelas
     */
    public function index(): View
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;
        $activeYear = active_academic_year();
        $activeSem = active_semester();
        $studentsCount = $classroom ? $classroom->students()->count() : 0;

        return view('walikelas.profile.index', compact('user', 'teacher', 'classroom', 'activeYear', 'activeSem', 'studentsCount'));
    }

    /**
     * Perbarui Profil Wali Kelas
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'nip_nuptk' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:6'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $photoPath = null;
        if ($request->hasFile('photo')) {
            if ($teacher && $teacher->photo && Storage::disk($disk)->exists($teacher->photo)) {
                Storage::disk($disk)->delete($teacher->photo);
            }
            $photoPath = $request->file('photo')->store('photos/teachers', $disk);
        }

        if ($teacher) {
            $teacherData = [
                'name' => $request->name,
                'email' => $request->email,
                'nip_nuptk' => $request->nip_nuptk,
                'gender' => $request->gender,
                'phone' => $request->phone,
            ];

            if ($photoPath) {
                $teacherData['photo'] = $photoPath;
            }

            $teacher->update($teacherData);
        }

        return back()->with('success', 'Profil data diri Anda berhasil diperbarui.');
    }
}
