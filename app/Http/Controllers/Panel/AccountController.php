<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Classroom;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService
    ) {}

    public function index(Request $request): View
    {
        $role = $request->input('role');
        $status = $request->input('status');
        $search = $request->input('search');

        $users = User::query()
            ->when($role, fn ($q) => $q->where('role', $role))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('is_active', (bool) $status))
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classrooms = Classroom::latest()->get();

        return view('panel.akun.index', compact('users', 'role', 'status', 'search', 'classrooms'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
            'must_change_password' => true,
        ]);

        return redirect()->route('panel.akun.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function update(UserRequest $request, User $akun): RedirectResponse
    {
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $akun->update($data);

        return redirect()->route('panel.akun.index')
            ->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $akun): RedirectResponse
    {
        // Cegah admin menonaktifkan akunnya sendiri
        if ($akun->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $akun->update(['is_active' => !$akun->is_active]);

        $statusText = $akun->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$akun->name} berhasil {$statusText}.");
    }

    public function resetPassword(Request $request, User $akun): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ]);

        $akun->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => true,
        ]);

        return back()->with('success', "Password untuk akun {$akun->name} berhasil direset. User wajib menggantinya saat login berikutnya.");
    }

    public function destroy(User $akun): RedirectResponse
    {
        if ($akun->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $akun->delete();

        return redirect()->route('panel.akun.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function generateStudentAccounts(Request $request): RedirectResponse
    {
        $request->validate([
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'default_password' => ['required', 'string', 'min:6'],
        ]);

        $classroomId = $request->input('classroom_id') ? (int) $request->input('classroom_id') : null;
        $defaultPassword = $request->input('default_password');

        $result = $this->accountService->generateStudentAccounts($classroomId, $defaultPassword);

        return back()->with('success', "Berhasil membuat {$result['generated_count']} akun murid baru dari total {$result['total_processed']} murid.");
    }

    public function exportStudentCredentials(Request $request): StreamedResponse
    {
        $classroomId = $request->input('classroom_id') ? (int) $request->input('classroom_id') : null;
        $defaultPassword = $request->input('default_password', 'password123');

        return $this->accountService->exportStudentCredentials($classroomId, $defaultPassword);
    }
}
