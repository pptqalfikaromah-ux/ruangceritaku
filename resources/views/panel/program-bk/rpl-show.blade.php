@extends('layouts.panel')

@section('title', 'Dokumen RPL - ' . $rpl->topic)

@section('content')
<div class="space-y-6">
    <!-- Header Back & Print Action -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('panel.program-bk.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Dokumen RPL</span>
                    <span class="badge bg-light text-dark border px-2 py-1 fs-xs">{{ $rpl->serviceType?->name }}</span>
                </div>
                <h1 class="h5 fw-bold text-dark mb-0">{{ $rpl->topic }}</h1>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                <i class="bi bi-printer me-1"></i> Cetak Dokumen RPL
            </button>
            <form method="POST" action="{{ route('panel.program-bk.rpl.destroy', $rpl) }}" class="d-inline" onsubmit="return confirm('Hapus dokumen RPL ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Official RPL Paper Sheet Layout -->
    <div class="card border-0 shadow-sm rounded-3 p-5 bg-white">
        <!-- Document Kop -->
        <div class="text-center pb-4 border-bottom mb-4">
            <h5 class="fw-bold text-dark text-uppercase mb-1">Rencana Pelaksanaan Layanan (RPL) Bimbingan Dan Konseling</h5>
            <h6 class="fw-semibold text-muted text-uppercase mb-0">Format Kurikulum Merdeka / Standar Operasional BK Madrasah</h6>
        </div>

        <!-- Section A: Identitas -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark border-start border-4 border-emerald ps-2 mb-3">A. IDENTITAS LAYANAN</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm fs-sm">
                    <tbody>
                        <tr>
                            <td style="width: 200px;" class="bg-light fw-semibold text-dark">Topik / Materi Layanan</td>
                            <td class="fw-bold text-dark">{{ $rpl->topic }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold text-dark">Bidang Bimbingan</td>
                            <td>Bidang {{ $rpl->guidanceField?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold text-dark">Jenis Layanan BK</td>
                            <td>{{ $rpl->serviceType?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold text-dark">Sasaran Kelas / Semester</td>
                            <td>{{ $rpl->target_class }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold text-dark">Alokasi Waktu</td>
                            <td>{{ $rpl->allocation_time }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-semibold text-dark">Guru BK / Konselor Pelaksana</td>
                            <td>{{ $rpl->counselor?->name ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section B: Tujuan Layanan -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark border-start border-4 border-emerald ps-2 mb-3">B. TUJUAN LAYANAN</h6>
            <div class="p-3 bg-light rounded-3 border fs-sm space-y-2">
                <div>
                    <span class="fw-bold text-dark d-block">1. Tujuan Umum:</span>
                    <p class="text-dark mb-0">{{ $rpl->general_goals }}</p>
                </div>
                @if($rpl->specific_goals)
                    <div class="pt-2 border-top">
                        <span class="fw-bold text-dark d-block">2. Tujuan Khusus:</span>
                        <div class="text-dark" style="white-space: pre-line;">{{ $rpl->specific_goals }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section C: Komponen Pelaksanaan -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark border-start border-4 border-emerald ps-2 mb-3">C. KOMPONEN PELAKSANAAN</h6>
            <div class="row g-3 fs-sm">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="fw-bold text-dark d-block mb-1">Materi Layanan:</span>
                        <div class="text-muted" style="white-space: pre-line;">{{ $rpl->materials ?: 'Materi terlampir pada modul bimbingan.' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="fw-bold text-dark d-block mb-1">Metode & Pendekatan:</span>
                        <div class="text-muted" style="white-space: pre-line;">{{ $rpl->methods ?: 'Diskusi interaktif, ceramah reflektif, curah pendapat.' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="fw-bold text-dark d-block mb-1">Media & Alat Bantu:</span>
                        <div class="text-muted" style="white-space: pre-line;">{{ $rpl->media ?: 'LCD proyektor, lembar refleksi murid (LKPD).' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section D: Langkah Kegiatan -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark border-start border-4 border-emerald ps-2 mb-3">D. LANGKAH-LANGKAH KEGIATAN</h6>
            <div class="p-3 bg-light rounded-3 border fs-sm text-dark" style="white-space: pre-line;">
                {{ $rpl->steps ?: "A. Tahap Awal / Pendahuluan\n1. Mengucapkan salam dan menyapa peserta didik.\n2. Membina hubungan baik (rapport) dan menyampaikan tujuan layanan.\n\nB. Tahap Inti\n1. Pemaparan konsep materi bimbingan.\n2. Curah pendapat dan pendalaman materi.\n3. Diskusi reflektif dan pemecahan masalah.\n\nC. Tahap Penutup\n1. Merangkum poin utama bersama peserta didik.\n2. Memberikan penguatan dan apresiasi.\n3. Menutup pertemuan dengan doa." }}
            </div>
        </div>

        <!-- Section E: Evaluasi -->
        <div class="mb-5">
            <h6 class="fw-bold text-dark border-start border-4 border-emerald ps-2 mb-3">E. EVALUASI</h6>
            <div class="p-3 bg-light rounded-3 border fs-sm text-dark" style="white-space: pre-line;">
                {{ $rpl->evaluation ?: "1. Evaluasi Proses: Mengamati keaktifan, antusiasme, dan kenyamanan murid dalam mengikuti layanan.\n2. Evaluasi Hasil: Memberikan angket penilaian kepuasan konseli dan lembar refleksi diri." }}
            </div>
        </div>

        <!-- Tanda Tangan Pengesahan -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 10pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    Mengetahui,<br>
                    Kepala {{ app_setting('school_name', 'Madrasah / Sekolah') }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    {{ app_setting('school_city', 'Sumpiuh') }}, {{ now()->translatedFormat('d F Y') }}<br>
                    Guru Bimbingan dan Konseling
                </td>
            </tr>
            <tr>
                <td style="height: 65px;"></td>
                <td style="height: 65px;"></td>
            </tr>
            <tr>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ app_setting('headmaster_name', '(...................................................)') }}</u></strong><br>
                    <span class="text-muted fs-xs">NIP. {{ app_setting('headmaster_nip', '...................................................') }}</span>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ $rpl->counselor?->name ?? '(...................................................)' }}</u></strong><br>
                    <span class="text-muted fs-xs">NIP. {{ $rpl->counselor?->nip ?? '...................................................' }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
