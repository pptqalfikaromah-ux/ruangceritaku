@extends('layouts.student')

@section('title', 'Hasil Akhir Asesmen Masalah (IMS)')
@section('page_title', 'Hasil Akhir Asesmen IMS')

@section('breadcrumb')
    <a href="{{ route('murid.dashboard') }}">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('murid.ims.index') }}">Asesmen IMS</a>
    <span class="mx-1">/</span>
    <span class="text-dark fw-semibold">Hasil Akhir Pengolahan</span>
@endsection

@section('content')
<div class="space-y-4 pb-5">
    <!-- Top Result Banner -->
    <div class="card border-0 rounded-4 overflow-hidden shadow-sm position-relative text-white" 
         style="background: linear-gradient(135deg, #00B894 0%, #008F72 65%, #006955 100%);">
        <div class="p-4 p-md-5 position-relative z-1">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge px-3 py-1 rounded-pill fw-bold fs-xs text-dark" style="background: #FFD15C;">
                            <i class="bi bi-file-earmark-check-fill me-1"></i> Format AUM Seri Umum
                        </span>
                        @if($assessment->isSubmitted())
                            <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-1 fs-xs fw-semibold">
                                <i class="bi bi-check-all me-1"></i> Sudah Dikirimkan ke Guru BK
                            </span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-xs fw-bold">
                                <i class="bi bi-pencil-square me-1"></i> Pratinjau Sementara (Draft)
                            </span>
                        @endif
                    </div>
                    <h2 class="fw-extrabold mb-1 tracking-tight" style="font-size: clamp(1.4rem, 2.5vw, 1.85rem);">
                        Lembar Hasil Akhir Pengolahan Asesmen Siswa (IMS)
                    </h2>
                    <p class="text-white opacity-90 fs-xs mb-0" style="max-width: 600px; line-height: 1.6;">
                        Berikut adalah rekapitulasi data pemetaan masalah yang telah kamu isi melalui 8 Rumpun Layanan Konseling dan 4 Bidang Bimbingan.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                    <a href="{{ route('murid.ims.print') }}" target="_blank" class="btn btn-gold btn-sm rounded-pill px-4 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" style="color: #1e293b;">
                        <i class="bi bi-printer-fill"></i> Cetak Format AUM (PDF)
                    </a>
                    <a href="{{ route('murid.ims.index') }}" class="btn btn-light btn-sm rounded-pill px-3.5 py-2.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-arrow-left"></i> Menu IMS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Stats Ringkasan Cepat -->
    <div class="row g-2.5 g-md-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 bg-white p-3 shadow-xs h-100">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 44px; height: 44px; background: #eff6ff;">
                        <i class="bi bi-list-check fs-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Total Masalah Dialami</div>
                        <div class="fw-extrabold fs-4 text-dark mb-0">{{ $totalSelectedAll }} <span class="fs-xs fw-normal text-muted">/ 160</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 bg-white p-3 shadow-xs h-100">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger flex-shrink-0" style="width: 44px; height: 44px; background: #fef2f2;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Masalah Berat (NMYB)</div>
                        <div class="fw-extrabold fs-4 text-danger mb-0">{{ count($heavyStatementsAll) }} <span class="fs-xs fw-normal text-muted">Butir</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 bg-white p-3 shadow-xs h-100">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success flex-shrink-0" style="width: 44px; height: 44px; background: #ecfdf5;">
                        <i class="bi bi-percent fs-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Persentase Masalah</div>
                        <div class="fw-extrabold fs-4 text-dark mb-0">{{ number_format($overallPercentage, 2, ',', '.') }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 rounded-4 bg-white p-3 shadow-xs h-100">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-purple flex-shrink-0" style="width: 44px; height: 44px; background: #f5f3ff; color: #7c3aed;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Pihak Konsultasi</div>
                        <div class="fw-bold fs-xs text-dark text-truncate mb-0">{{ $assessment->consult_to ?? 'Guru BK / Konselor' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Tabel Rekapitulasi AUM Format 3 -->
    <div class="card border-0 rounded-4 bg-white shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 px-3 px-sm-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger px-2 py-0.5 rounded-pill fs-xxs fw-bold text-uppercase mb-1">RAHASIA</span>
                <h5 class="fw-bold text-dark mb-0 fs-sm">Tabel Rekapitulasi Hasil Pengolahan AUM / IMS</h5>
            </div>
            <span class="badge bg-light text-muted border fs-xs rounded-pill px-3 py-1">
                Total 8 Layanan Bimbingan Konseling
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-start" style="width: 25%; min-width: 150px;">LAYANAN BK</th>
                            <th colspan="3">JENIS MASALAH</th>
                            <th rowspan="2" style="width: 20%; min-width: 120px;">N.M.Y.B<br><span class="fw-normal fs-xxs text-muted">(Masalah Yang Berat)</span></th>
                        </tr>
                        <tr>
                            <th style="min-width: 200px;">NOMOR MASALAH</th>
                            <th style="width: 60px;">JML</th>
                            <th style="width: 70px;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fieldsData as $idx => $row)
                            <tr>
                                <td class="text-start fw-bold">
                                    {{ $idx + 1 }}. {{ $row['service']->name }} ({{ $row['total_statements'] }})
                                </td>
                                <td class="text-start text-break fs-xs">
                                    @if(count($row['selected_numbers']) > 0)
                                        {{ implode(', ', $row['selected_numbers']) }}
                                    @else
                                        <span class="text-muted text-center d-block">-</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">
                                    {{ $row['count'] > 0 ? $row['count'] : '-' }}
                                </td>
                                <td>
                                    {{ $row['count'] > 0 ? number_format($row['percentage'], 2, ',', '.') . '%' : '-' }}
                                </td>
                                <td class="text-center text-break fs-xs">
                                    @if(count($row['heavy_numbers']) > 0)
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fw-bold">
                                            {{ implode(', ', $row['heavy_numbers']) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold table-light">
                            <td class="text-start">KESELURUHAN ({{ $totalStatementsAll }})</td>
                            <td class="fst-italic text-muted">Sama dengan Diatas</td>
                            <td class="text-primary fs-6">{{ $totalSelectedAll }}</td>
                            <td class="text-primary fs-6">{{ number_format($overallPercentage, 2, ',', '.') }}%</td>
                            <td class="fst-italic text-muted">Sama Dengan Diatas</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-3 p-sm-4 bg-light bg-opacity-50 border-top">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 fs-xs">
                    <div>
                        <span class="text-muted">Ingin mengkonsultasikan masalah kepada:</span>
                        <strong class="text-dark ms-1"><u>{{ $assessment->consult_to ?? 'Guru BK / Konselor' }}</u></strong>
                    </div>
                    <div class="text-muted">
                        Tanggal Pengisian: <strong>{{ $assessment->submitted_at ? $assessment->submitted_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Rincian Butir Terpilih & Masalah Berat -->
    <div class="row g-3 g-md-4">
        <!-- Rincian Masalah Terpilih -->
        <div class="col-lg-7">
            <div class="card border-0 rounded-4 bg-white shadow-sm p-3.5 p-sm-4 h-100">
                <h5 class="fw-bold text-dark fs-sm mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-card-checklist text-emerald"></i>
                    <span>Rincian Butir Masalah yang Kamu Alami</span>
                </h5>

                @foreach($fieldsData as $idx => $row)
                    <div class="mb-3.5 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="fw-bold text-dark fs-xs mb-1.5 d-flex align-items-center justify-content-between">
                            <span>{{ $idx + 1 }}. {{ $row['service']->name }}</span>
                            <span class="badge bg-light text-muted border rounded-pill">{{ count($row['selected_answers']) }} butir</span>
                        </div>
                        @if(count($row['selected_answers']) > 0)
                            <div class="space-y-1.5 ps-2">
                                @foreach($row['selected_answers'] as $ans)
                                    <div class="fs-xs text-slate-700 d-flex align-items-start gap-1.5">
                                        <span class="badge bg-light text-dark border flex-shrink-0" style="font-size: 0.7rem;">#{{ sprintf('%03d', $ans->statement?->statement_number) }}</span>
                                        <div>
                                            <span>{{ $ans->statement?->statement_text }}</span>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-1.5 py-0.5 ms-1" style="font-size: 0.65rem;">
                                                {{ $ans->statement?->guidanceField?->name ?? 'Bidang' }}
                                            </span>
                                            @if($ans->is_heavy)
                                                <span class="badge bg-danger text-white rounded-pill px-1.5 py-0.5 ms-1" style="font-size: 0.65rem;">Paling Mengganggu</span>
                                            @endif
                                            @if($ans->student_note)
                                                <div class="text-muted fst-italic mt-0.5" style="font-size: 0.72rem;">"{{ $ans->student_note }}"</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="fs-xs text-muted fst-italic ps-2">- Tidak ada masalah yang dipilih pada layanan ini.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Kolom Samping: Masalah Berat & Catatan Murid -->
        <div class="col-lg-5 space-y-3">
            <!-- Box Masalah Paling Berat (NMYB) -->
            <div class="card border-0 rounded-4 bg-white shadow-sm p-3.5 p-sm-4" style="border-left: 4px solid #ef4444 !important;">
                <h5 class="fw-bold text-danger fs-sm mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-diamond-fill"></i>
                    <span>Masalah Yang Dirasakan Paling Berat (N.M.Y.B)</span>
                </h5>
                <p class="text-muted fs-xxs mb-3">Butir masalah yang kamu tandai paling membebani atau paling mendesak untuk dibantu:</p>

                @if(count($heavyStatementsAll) > 0)
                    <div class="space-y-2">
                        @foreach($heavyStatementsAll as $ha)
                            <div class="p-2.5 rounded-3 bg-danger-subtle border border-danger-subtle fs-xs">
                                <div class="fw-bold text-danger-emphasis">
                                    #{{ sprintf('%03d', $ha->statement?->statement_number) }} - {{ $ha->statement?->guidanceField?->name ?? 'Masalah' }}
                                </div>
                                <div class="text-dark mt-1">{{ $ha->statement?->statement_text }}</div>
                                @if($ha->student_note)
                                    <div class="text-muted fst-italic mt-1 ps-2 border-start border-danger">"{{ $ha->student_note }}"</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 bg-light rounded-3 text-muted fs-xs fst-italic text-center">
                        Kamu tidak menandai adanya masalah yang dirasakan sangat berat.
                    </div>
                @endif
            </div>

            <!-- Catatan Curhat / Penutup Murid -->
            <div class="card border-0 rounded-4 bg-white shadow-sm p-3.5 p-sm-4">
                <h5 class="fw-bold text-dark fs-sm mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-chat-quote-fill text-mint"></i>
                    <span>Catatan Curhat Murid</span>
                </h5>
                @if($assessment->final_notes)
                    <div class="p-3 bg-light rounded-3 text-dark italic border fs-xs">
                        "{{ $assessment->final_notes }}"
                    </div>
                @else
                    <div class="text-muted fs-xs fst-italic">Kamu tidak menyertakan catatan penutup tambahan.</div>
                @endif
            </div>

            <!-- Jaminan Etika BK -->
            <div class="card border-0 rounded-4 p-3.5 text-white" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                <div class="fw-bold fs-xs mb-1 d-flex align-items-center gap-1.5">
                    <i class="bi bi-shield-lock-fill text-warning"></i> Kerahasiaan Dilindungi
                </div>
                <div class="text-white-50" style="font-size: 0.72rem; line-height: 1.5;">
                    Hasil asesmen ini disimpan secara aman dan hanya dapat dipelajari oleh Guru BK sekolah untuk keperluan bimbingan dan pendampinganmu.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
