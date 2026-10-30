<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil Murid
     */
    public function index(): View
    {
        $user = Auth::user();
        $student = $user->student;
        abort_unless($student, 403, 'Data profil murid tidak ditemukan.');

        $classroom = $student->currentClassroom ?? $student->classrooms()->latest()->first();
        $activeYear = active_academic_year();
        $activeSem = active_semester();

        return view('student.profile.index', compact('user', 'student', 'classroom', 'activeYear', 'activeSem'));
    }

    /**
     * Simpan Perubahan Profil Murid
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $student = $user->student;
        abort_unless($student, 403, 'Data profil murid tidak ditemukan.');

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:6'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'password.min' => 'Password minimal 6 karakter jika ingin diubah.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // 1. Update akun pengguna (User)
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // 2. Upload Foto Profil Baru jika ada
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $photoPath = null;
        if ($request->hasFile('photo')) {
            if ($student->photo && !str_starts_with($student->photo, 'http') && Storage::disk($disk)->exists($student->photo)) {
                Storage::disk($disk)->delete($student->photo);
            }
            $photoPath = $request->file('photo')->store('photos/students', $disk);
        }

        // 3. Update Data Profil Murid
        $studentData = [
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'religion' => $request->religion,
            'address' => $request->address,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
        ];

        if ($photoPath) {
            $studentData['photo'] = $photoPath;
        }

        $student->update($studentData);

        return back()->with('success', 'Profil dan data diri Anda berhasil diperbarui.');
    }
}
