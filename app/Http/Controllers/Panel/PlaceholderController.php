<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    public function pengaduan(): View
    {
        return view('panel.placeholder', [
            'title' => 'Layanan Pengaduan Anak',
            'badge' => 'Tahap 2',
            'icon' => 'bi-megaphone',
            'description' => 'Modul penerimaan dan penanganan laporan pengaduan kekerasan, perundungan (bullying), dan masalah siswa secara aman dan konfidensial.',
        ]);
    }

    public function konseling(): View
    {
        return view('panel.placeholder', [
            'title' => 'Konsultasi & Konseling BK',
            'badge' => 'Tahap 2',
            'icon' => 'bi-chat-heart',
            'description' => 'Modul penjadwalan sesi konseling individual, konseling kelompok, catatan kasus (anecdotal record), serta notulen layanan BK.',
        ]);
    }

    public function angket(): View
    {
        return view('panel.placeholder', [
            'title' => 'Instrumen & Angket Siswa (IMS)',
            'badge' => 'Tahap 3',
            'icon' => 'bi-clipboard2-check',
            'description' => 'Modul asesmen kebutuhan konseling (AUM / DCM / Sosiometri) dan angket psikososial siswa secara daring.',
        ]);
    }

    public function programBk(): View
    {
        return view('panel.placeholder', [
            'title' => 'Program & Rencana Layanan BK (RPL)',
            'badge' => 'Tahap 3',
            'icon' => 'bi-calendar-week',
            'description' => 'Modul penyusunan Program Tahunan (Prota), Program Semester (Promes), dan Rencana Pelaksanaan Layanan BK terstruktur.',
        ]);
    }
}
