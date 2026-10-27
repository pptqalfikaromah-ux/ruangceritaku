<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\StudentPamphlet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class PamphletModerationController extends Controller
{
    /**
     * Halaman Moderasi Pamflet Kreativitas Siswa
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = StudentPamphlet::with(['student.user', 'classroom', 'approver'])
            ->latest();

        if (in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $pamphlets = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => StudentPamphlet::count(),
            'pending' => StudentPamphlet::where('status', 'pending')->count(),
            'disetujui' => StudentPamphlet::where('status', 'disetujui')->count(),
            'ditolak' => StudentPamphlet::where('status', 'ditolak')->count(),
        ];

        return view('panel.pamflet-kreatifitas.index', compact('pamphlets', 'counts', 'status'));
    }

    /**
     * Setujui pamflet agar langsung tayang di Dashboard semua akun
     */
    public function approve(StudentPamphlet $pamphlet): RedirectResponse
    {
        $pamphlet->update([
            'status' => 'disetujui',
            'is_active' => true,
            'approved_by_user_id' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Karya '{$pamphlet->title}' milik {$pamphlet->student?->name} berhasil disetujui dan kini tayang di Dashboard!");
    }

    /**
     * Tolak pamflet dengan alasan penolakan
     */
    public function reject(Request $request, StudentPamphlet $pamphlet): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Wajib menyertakan alasan penolakan agar siswa dapat memperbaikinya.',
        ]);

        $pamphlet->update([
            'status' => 'ditolak',
            'is_active' => false,
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by_user_id' => Auth::id(),
            'approved_at' => null,
        ]);

        return redirect()->back()->with('warning', "Karya pamflet ditolak dengan catatan: {$validated['rejection_reason']}");
    }

    /**
     * Toggle tayang / sembunyikan pamflet yang sudah disetujui
     */
    public function togglePublish(StudentPamphlet $pamphlet): RedirectResponse
    {
        $newStatus = !$pamphlet->is_active;
        $pamphlet->update(['is_active' => $newStatus]);

        $msg = $newStatus ? 'Karya kembali ditayangkan di Dashboard.' : 'Karya sementara dinonaktifkan dari tayangan Dashboard.';
        return redirect()->back()->with('info', $msg);
    }

    /**
     * Hapus pamflet
     */
    public function destroy(StudentPamphlet $pamphlet): RedirectResponse
    {
        if ($pamphlet->image_path && File::exists(public_path($pamphlet->image_path))) {
            File::delete(public_path($pamphlet->image_path));
        }

        $pamphlet->delete();

        return redirect()->back()->with('success', 'Karya pamflet kreativitas berhasil dihapus.');
    }
}
