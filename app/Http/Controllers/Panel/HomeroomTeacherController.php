<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeroomTeacherController extends Controller
{
    /**
     * Tampilkan daftar data Wali Kelas
     */
    public function index(Request $request): View
    {
        $query = Teacher::with(['user', 'currentAssignment.classroom']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nuptk', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $teachers = $query->latest()->paginate(15)->withQueryString();

        return view('panel.wali-kelas.index', compact('teachers'));
    }

    /**
     * Form tambah Wali Kelas baru
     */
    public function create(): View
    {
        return view('panel.wali-kelas.create');
    }

    /**
     * Simpan data Wali Kelas dan akun login User
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip_nuptk' => ['nullable', 'string', 'max:30', 'unique:teachers,nip_nuptk'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'username.unique' => 'Username ini sudah digunakan akun lain.',
            'nip_nuptk.unique' => 'NIP/NUPTK ini sudah terdaftar.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'wali_kelas',
                'is_active' => $validated['status'] === 'aktif',
                'must_change_password' => false,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip_nuptk' => $validated['nip_nuptk'],
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('panel.wali-kelas.index')
            ->with('success', 'Data Wali Kelas dan akun login berhasil ditambahkan.');
    }

    /**
     * Form edit data Wali Kelas
     */
    public function edit(Teacher $waliKela): View
    {
        $teacher = $waliKela->load('user');
        return view('panel.wali-kelas.edit', compact('teacher'));
    }

    /**
     * Perbarui data Wali Kelas dan akun
     */
    public function update(Request $request, Teacher $waliKela): RedirectResponse
    {
        $teacher = $waliKela;
        $user = $teacher->user;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip_nuptk' => ['nullable', 'string', 'max:30', Rule::unique('teachers', 'nip_nuptk')->ignore($teacher->id)],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user?->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::transaction(function () use ($teacher, $user, $validated) {
            $teacher->update([
                'nip_nuptk' => $validated['nip_nuptk'],
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);

            if ($user) {
                $userData = [
                    'name' => $validated['name'],
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'is_active' => $validated['status'] === 'aktif',
                    'role' => 'wali_kelas',
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $user->update($userData);
            }
        });

        return redirect()->route('panel.wali-kelas.index')
            ->with('success', 'Data Wali Kelas berhasil diperbarui.');
    }

    /**
     * Hapus Wali Kelas
     */
    public function destroy(Teacher $waliKela): RedirectResponse
    {
        $waliKela->user?->delete();
        $waliKela->delete();

        return redirect()->route('panel.wali-kelas.index')
            ->with('success', 'Data Wali Kelas dan akun terkait berhasil dihapus.');
    }
}
