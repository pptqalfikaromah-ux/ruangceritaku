<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\HomeroomComplaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeroomComplaintController extends Controller
{
    /**
     * Tampilkan daftar aduan kasus murid dari Wali Kelas
     */
    public function index(Request $request): View
    {
        $query = HomeroomComplaint::with(['teacher.user', 'classroom', 'student', 'handledByCounselor']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%");
                  })
                  ->orWhereHas('teacher', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $complaints = $query->latest('incident_date')->paginate(15)->withQueryString();

        return view('panel.aduan-walikelas.index', compact('complaints'));
    }

    /**
     * Tampilkan rincian kasus aduan dari Wali Kelas
     */
    public function show(HomeroomComplaint $aduanWalikela): View
    {
        if (!$aduanWalikela->read_at) {
            $aduanWalikela->update(['read_at' => now()]);
        }

        $complaint = $aduanWalikela->load(['teacher.user', 'classroom', 'student', 'handledByCounselor']);

        return view('panel.aduan-walikelas.show', compact('complaint'));
    }

    /**
     * Tanggapi & tindak lanjuti kasus oleh Guru BK / Admin
     */
    public function update(Request $request, HomeroomComplaint $aduanWalikela): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu_tindak_lanjut,sedang_ditangani,selesai'],
            'counselor_response' => ['required', 'string', 'min:5'],
        ]);

        $counselor = Counselor::where('user_id', Auth::id())->first();

        $aduanWalikela->update([
            'status' => $validated['status'],
            'counselor_response' => $validated['counselor_response'],
            'handled_by_counselor_id' => $counselor?->id,
            'handled_at' => now(),
        ]);

        return redirect()->route('panel.aduan-walikelas.show', $aduanWalikela)->with('success', 'Tindak lanjut dan catatan balasan Guru BK berhasil disimpan.');
    }

    /**
     * Cetak Arsip PDF Laporan Penanganan Aduan Kasus Wali Kelas
     */
    public function printReport(HomeroomComplaint $aduanWalikela): View
    {
        $complaint = $aduanWalikela->load(['teacher.user', 'classroom', 'student.classrooms', 'handledByCounselor.user']);
        $student = $complaint->student;
        $teacher = $complaint->teacher;
        $counselor = $complaint->handledByCounselor ?? Counselor::first();

        return view('panel.aduan-walikelas.laporan-kasus-pdf', compact('complaint', 'student', 'teacher', 'counselor'));
    }
}
