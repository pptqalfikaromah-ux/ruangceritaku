<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CounselorRequest;
use App\Models\Counselor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CounselorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $counselors = Counselor::with('user')
            ->when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nuptk', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('panel.guru-bk.index', compact('counselors', 'search'));
    }

    public function create(): View
    {
        return view('panel.guru-bk.create');
    }

    public function store(CounselorRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $userId = null;

            // Jika admin memilih untuk membuatkan akun login
            if ($request->boolean('create_account')) {
                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'guru_bk',
                    'is_active' => $request->status === 'aktif',
                    'must_change_password' => true,
                ]);

                $userId = $user->id;
            }

            $photoPath = null;
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos/counselors', $disk);
            }

            Counselor::create([
                'user_id' => $userId,
                'nip_nuptk' => $request->nip_nuptk,
                'name' => $request->name,
                'title' => $request->title,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'photo' => $photoPath,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('panel.guru-bk.index')
            ->with('success', 'Data Guru BK berhasil ditambahkan.');
    }

    public function edit(Counselor $guru_bk): View
    {
        $guru_bk->load('user');

        return view('panel.guru-bk.edit', compact('guru_bk'));
    }

    public function update(CounselorRequest $request, Counselor $guru_bk): RedirectResponse
    {
        $data = $request->safe()->except(['photo', 'create_account', 'username', 'password']);
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

        if ($request->hasFile('photo')) {
            if ($guru_bk->photo && Storage::disk($disk)->exists($guru_bk->photo)) {
                Storage::disk($disk)->delete($guru_bk->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos/counselors', $disk);
        }

        $guru_bk->update($data);

        // Jika terhubung dengan akun user, sinkronkan nama, email, dan status aktif
        if ($guru_bk->user) {
            $guru_bk->user->update([
                'name' => $guru_bk->name,
                'email' => $guru_bk->email,
                'is_active' => $guru_bk->status === 'aktif',
            ]);
        }

        return redirect()->route('panel.guru-bk.index')
            ->with('success', 'Data Guru BK berhasil diperbarui.');
    }

    public function destroy(Counselor $guru_bk): RedirectResponse
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        if ($guru_bk->photo && Storage::disk($disk)->exists($guru_bk->photo)) {
            Storage::disk($disk)->delete($guru_bk->photo);
        }

        $guru_bk->delete();

        return redirect()->route('panel.guru-bk.index')
            ->with('success', 'Data Guru BK berhasil dihapus.');
    }
}
