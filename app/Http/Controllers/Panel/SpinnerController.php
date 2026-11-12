<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\SpinnerParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpinnerController extends Controller
{
    /**
     * Tampilkan Halaman Alat Spinner / Roda Undian
     */
    public function index(): View
    {
        $participants = SpinnerParticipant::orderBy('id')->get();

        if ($participants->isEmpty()) {
            foreach (SpinnerParticipant::defaultNames() as $name) {
                SpinnerParticipant::create([
                    'name' => $name,
                    'is_active' => true,
                    'win_count' => 0,
                ]);
            }
            $participants = SpinnerParticipant::orderBy('id')->get();
        }

        $namesList = $participants->pluck('name')->implode("\n");

        return view('panel.spinner.index', compact('participants', 'namesList'));
    }

    /**
     * Simpan Perubahan Daftar Peserta Manual
     */
    public function updateParticipants(Request $request): RedirectResponse
    {
        $request->validate([
            'names_text' => 'required|string',
        ], [
            'names_text.required' => 'Daftar nama peserta tidak boleh kosong.',
        ]);

        $rawLines = explode("\n", $request->input('names_text'));
        $names = [];

        foreach ($rawLines as $line) {
            $trimmed = trim($line);
            if (!empty($trimmed)) {
                $names[] = $trimmed;
            }
        }

        if (count($names) < 2) {
            return back()->with('error', 'Masukkan minimal 2 nama peserta untuk memutar roda undian.');
        }

        // Hapus data lama dan masukkan data baru
        SpinnerParticipant::truncate();

        foreach ($names as $name) {
            SpinnerParticipant::create([
                'name' => $name,
                'is_active' => true,
                'win_count' => 0,
            ]);
        }

        return redirect()->route('panel.spinner.index')->with('success', 'Daftar peserta undian berhasil diperbarui (' . count($names) . ' nama).');
    }

    /**
     * Reset Daftar Peserta ke 48 Nama Bawaan
     */
    public function resetToDefault(): RedirectResponse
    {
        SpinnerParticipant::truncate();

        foreach (SpinnerParticipant::defaultNames() as $name) {
            SpinnerParticipant::create([
                'name' => $name,
                'is_active' => true,
                'win_count' => 0,
            ]);
        }

        return redirect()->route('panel.spinner.index')->with('success', 'Daftar peserta berhasil direset ke 48 nama bawaan.');
    }

    /**
     * Rekam Pemenang (Ajax)
     */
    public function recordWinner(Request $request): JsonResponse
    {
        $name = trim($request->input('name'));

        if (!empty($name)) {
            $participant = SpinnerParticipant::where('name', $name)->first();
            if ($participant) {
                $participant->increment('win_count');
                $participant->update(['last_won_at' => now()]);
            }
        }

        return response()->json(['status' => 'success', 'winner' => $name]);
    }
}

