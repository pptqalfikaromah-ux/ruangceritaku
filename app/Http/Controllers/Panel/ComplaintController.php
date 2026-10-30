<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\StudentComplaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Daftar Pengaduan Murid & Perlindungan Anak
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $urgency = $request->input('urgency');
        $category = $request->input('category');
        $riskOnly = $request->boolean('risk');
        $search = $request->input('q');

        $query = StudentComplaint::with(['student.classrooms', 'handledBy']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($urgency) {
            $query->where('urgency_level', $urgency);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($riskOnly) {
            $query->where('safety_risk_flag', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('reporter_name', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        // Counters
        $counters = [
            'all' => StudentComplaint::count(),
            'risk' => StudentComplaint::where('safety_risk_flag', true)->count(),
            'pending' => StudentComplaint::where('status', 'pending')->count(),
            'investigating' => StudentComplaint::where('status', 'investigating')->count(),
            'resolved' => StudentComplaint::where('status', 'resolved')->count(),
        ];

        $categories = [
            'Perundungan / Bullying',
            'Kekerasan Fisik / Verbal',
            'Pelecehan / Keamanan Diri',
            'Keluarga & Pengasuhan',
            'Pemerasan / Keuangan',
            'Tekanan Belajar / Akademik',
            'Lainnya',
        ];

        return view('panel.pengaduan.index', compact(
            'complaints',
            'counters',
            'categories',
            'status',
            'urgency',
            'category',
            'riskOnly',
            'search'
        ));
    }

    /**
     * Detail Pengaduan & Catatan Penanganan Konselor
     */
    public function show(StudentComplaint $pengaduan): View
    {
        if (!$pengaduan->read_at) {
            $pengaduan->update(['read_at' => now()]);
        }

        $pengaduan->load(['student.classrooms', 'handledBy.user']);
        $counselors = Counselor::with('user')->get();

        return view('panel.pengaduan.show', compact('pengaduan', 'counselors'));
    }

    /**
     * Tindak Lanjut & Update Status Pengaduan
     */
    public function updateStatus(Request $request, StudentComplaint $pengaduan): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,investigating,resolved,closed'],
            'handling_notes' => ['required', 'string'],
            'handled_by_counselor_id' => ['nullable', 'exists:counselors,id'],
            'safety_risk_flag' => ['nullable', 'boolean'],
        ]);

        $pengaduan->update([
            'status' => $request->input('status'),
            'handling_notes' => $request->input('handling_notes'),
            'handled_by_counselor_id' => $request->input('handled_by_counselor_id') ?: $pengaduan->handled_by_counselor_id,
            'safety_risk_flag' => $request->boolean('safety_risk_flag'),
        ]);

        return back()->with('success', 'Catatan tindak lanjut dan status pengaduan berhasil diperbarui.');
    }

    /**
     * Hapus Data Pengaduan Siswa
     */
    public function destroy(StudentComplaint $pengaduan): RedirectResponse
    {
        $pengaduan->delete();

        return back()->with('success', 'Data pengaduan ' . $pengaduan->ticket_number . ' berhasil dihapus.');
    }
}
