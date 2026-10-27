<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentPamphlet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentPamphletController extends Controller
{
    /**
     * Tampilkan galeri karya & status pamflet yang diunggah oleh murid ini
     */
    public function index(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        $pamphlets = StudentPamphlet::where('student_id', $student->id)
            ->latest()
            ->paginate(12);

        return view('student.pamphlet.index', compact('student', 'pamphlets'));
    }

    /**
     * Form unggah pamflet kreativitas baru
     */
    public function create(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        return view('student.pamphlet.create', compact('student'));
    }

    /**
     * Proses simpan berkas pamflet kreativitas murid (maks 1 MB, JPG/PNG/WEBP)
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:1024', // Maksimal 1024 KB = 1 MB
            ],
        ], [
            'image.required' => 'Wajib memilih file foto atau gambar pamflet karya.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diperbolehkan hanya JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran berkas gambar maksimal adalah 1 MB (1024 KB).',
            'title.required' => 'Judul karya kreativitas wajib diisi.',
            'title.max' => 'Judul karya maksimal 150 karakter.',
        ]);

        $file = $request->file('image');
        $filename = 'pamphlet_' . $student->id . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();

        $disk = config('filesystems.default');
        if ($disk === 's3') {
            $path = $file->storeAs('uploads/pamphlets', $filename, 's3');
            $relativePath = $path;
        } else {
            $destinationPath = public_path('uploads/pamphlets');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $file->move($destinationPath, $filename);
            $relativePath = 'uploads/pamphlets/' . $filename;
        }

        StudentPamphlet::create([
            'student_id' => $student->id,
            'classroom_id' => $student->current_classroom?->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_path' => $relativePath,
            'status' => 'pending',
            'is_active' => false,
        ]);

        return redirect()->route('murid.pamflet.index')
            ->with('success', 'Karya pamflet kreativitas Anda berhasil diunggah! Saat ini menunggu moderasi dan persetujuan Guru BK / Admin agar tayang di Dashboard.');
    }

    /**
     * Hapus pamflet kreativitas milik sendiri
     */
    public function destroy(StudentPamphlet $pamphlet): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student && $pamphlet->student_id === $student->id, 403, 'Anda tidak memiliki akses menghapus karya ini.');

        if ($pamphlet->image_path && File::exists(public_path($pamphlet->image_path))) {
            File::delete(public_path($pamphlet->image_path));
        }

        $pamphlet->delete();

        return redirect()->route('murid.pamflet.index')
            ->with('success', 'Karya pamflet kreativitas berhasil dihapus.');
    }
}
