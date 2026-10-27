<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentRegisterController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran khusus murid
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
            if (Auth::user()->role === 'wali_kelas') {
                return redirect()->route('walikelas.dashboard');
            }
            return redirect()->route('panel.dashboard');
        }

        $activeYear = active_academic_year();
        $classrooms = Classroom::with('academicYear')
            ->when($activeYear, function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        if ($classrooms->isEmpty()) {
            $classrooms = Classroom::with('academicYear')->orderBy('name')->get();
        }

        return view('auth.register-murid', compact('classrooms', 'activeYear'));
    }

    /**
     * Proses pendaftaran murid baru
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'digits_between:8,15', 'unique:students,nisn'],
            'nis' => ['nullable', 'string', 'max:20'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'guardian_phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits_between' => 'NISN harus berupa angka 8-15 digit.',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem.',
            'classroom_id.required' => 'Silakan pilih rombel / kelas Anda dari daftar.',
            'classroom_id.exists' => 'Rombel yang dipilih tidak valid.',
            'gender.required' => 'Pilih jenis kelamin.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'username.required' => 'Username akun wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis hubung (-), dan garis bawah (_).',
            'username.unique' => 'Username ini sudah digunakan. Silakan gunakan username lain.',
            'password.required' => 'Password akun wajib diisi.',
            'password.min' => 'Password minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang diajukan.',
        ]);

        $classroom = Classroom::findOrFail($validated['classroom_id']);

        $user = DB::transaction(function () use ($validated, $classroom) {
            // 1. Buat User Account Murid
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'murid',
                'is_active' => true,
                'must_change_password' => false,
            ]);

            // 2. Buat Data Student
            $student = Student::create([
                'user_id' => $user->id,
                'nisn' => $validated['nisn'],
                'nis' => $validated['nis'] ?? null,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'birth_place' => $validated['birth_place'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => 'aktif',
            ]);

            // 3. Masukkan ke Rombel agar tidak salah kamar
            $student->classrooms()->attach($classroom->id, [
                'academic_year_id' => $classroom->academic_year_id ?? active_academic_year()?->id,
            ]);

            return $user;
        });

        // Login otomatis pengguna baru
        Auth::login($user);

        return redirect()->route('murid.dashboard')
            ->with('success', "Pendaftaran berhasil! Selamat datang di Ruang Ceritaku, {$user->name}. Anda telah terdaftar di kelas {$classroom->name}.");
    }
}
