<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Counselor;
use App\Models\GroupCounseling;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupCounselingController extends Controller
{
    /**
     * Daftar Layanan Bimbingan & Konseling Kelompok (Panel Admin & Guru BK)
     */
    public function index(Request $request): View
    {
        $query = GroupCounseling::with([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user'
        ])->latest();

        // Filter Jenis Layanan
        if ($request->filled('type')) {
            $query->where('service_type', $request->input('type'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Pencarian
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhereHas('leader', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        $counters = [
            'total' => GroupCounseling::count(),
            'bimbingan' => GroupCounseling::where('service_type', 'bimbingan_kelompok')->count(),
            'konseling' => GroupCounseling::where('service_type', 'konseling_kelompok')->count(),
            'pending' => GroupCounseling::where('status', 'menunggu_persetujuan')->count(),
            'scheduled' => GroupCounseling::where('status', 'dijadwalkan')->count(),
            'completed' => GroupCounseling::where('status', 'selesai')->count(),
        ];

        $groups = $query->paginate(15)->withQueryString();

        return view('panel.layanan-kelompok.index', compact('groups', 'counters'));
    }

    /**
     * Detail Sesi Kelompok & Formulir Penjadwalan / Respon
     */
    public function show(GroupCounseling $layananKelompok): View
    {
        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester'
        ]);

        $counselors = Counselor::with('user')->get();

        return view('panel.layanan-kelompok.show', compact('group', 'counselors'));
    }

    /**
     * Konfirmasi & Tetapkan Jadwal Sesi Kelompok
     */
    public function schedule(Request $request, GroupCounseling $layananKelompok): RedirectResponse
    {
        $request->validate([
            'counselor_id' => 'required|exists:counselors,id',
            'scheduled_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'location' => 'required|string|max:255',
            'meeting_link' => 'nullable|url|max:255',
            'counselor_notes' => 'nullable|string',
        ], [
            'counselor_id.required' => 'Pilih Guru BK / Konselor pembimbing.',
            'scheduled_date.required' => 'Tentukan tanggal pelaksanaan sesi kelompok.',
            'start_time.required' => 'Tentukan jam mulai.',
            'end_time.required' => 'Tentukan jam selesai.',
            'location.required' => 'Tentukan tempat / ruangan pelaksanaan.',
        ]);

        $layananKelompok->update([
            'counselor_id' => $request->input('counselor_id'),
            'scheduled_date' => $request->input('scheduled_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'location' => $request->input('location'),
            'meeting_link' => $request->input('meeting_link'),
            'counselor_notes' => $request->input('counselor_notes'),
            'status' => 'dijadwalkan',
        ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', 'Jadwal sesi ' . $layananKelompok->service_type_label . ' berhasil ditetapkan dan diinformasikan ke siswa.');
    }

    /**
     * Selesaikan Sesi Kelompok & Simpan Laporan Hasil / Evaluasi
     */
    public function complete(Request $request, GroupCounseling $layananKelompok): RedirectResponse
    {
        $request->validate([
            'duration_minutes' => 'required|integer|min:10|max:300',
            'counselor_notes' => 'required|string',
            'evaluation_result' => 'required|string',
            'follow_up_plan' => 'nullable|string',
        ], [
            'duration_minutes.required' => 'Durasi sesi kelompok harus diisi.',
            'counselor_notes.required' => 'Ringkasan dinamika kelompok harus diisi.',
            'evaluation_result.required' => 'Hasil evaluasi dan komitmen kelompok harus diisi.',
        ]);

        $layananKelompok->update([
            'duration_minutes' => $request->input('duration_minutes'),
            'counselor_notes' => $request->input('counselor_notes'),
            'evaluation_result' => $request->input('evaluation_result'),
            'follow_up_plan' => $request->input('follow_up_plan'),
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        return redirect()->route('panel.layanan-kelompok.show', $layananKelompok)
            ->with('success', 'Sesi ' . $layananKelompok->service_type_label . ' berhasil diselesaikan dan laporan evaluasi telah tersimpan.');
    }

    /**
     * Hapus Data Pengajuan Kelompok
     */
    public function destroy(GroupCounseling $layananKelompok): RedirectResponse
    {
        $ticket = $layananKelompok->ticket_number;
        $layananKelompok->delete();

        return redirect()->route('panel.layanan-kelompok.index')
            ->with('success', 'Data sesi kelompok tiket ' . $ticket . ' berhasil dihapus.');
    }

    /**
     * Cetak Berita Acara / Laporan Hasil Layanan Kelompok PDF
     */
    public function printReport(GroupCounseling $layananKelompok): View
    {
        $group = $layananKelompok->load([
            'leader.classrooms',
            'members.classrooms',
            'counselor.user',
            'academicYear',
            'semester'
        ]);

        $counselor = $group->counselor ?? Counselor::first();

        return view('panel.layanan-kelompok.laporan-kelompok-pdf', compact('group', 'counselor'));
    }
}

