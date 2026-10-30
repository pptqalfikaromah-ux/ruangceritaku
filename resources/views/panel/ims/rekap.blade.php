@extends('layouts.panel')

@section('title', 'Rekapitulasi Analisis Kebutuhan IMS')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                        <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Asesmen Kebutuhan</span>
                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Need Assessment BK</span>
                    </div>
                    <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Rekapitulasi Instrumen Masalah Siswa</h1>
                    <p class="text-muted fs-xs mb-0">Pemetaan kebutuhan konseling komprehensif berdasarkan 180 butir pernyataan masalah.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-bold fs-xs shadow-xs">
                        <i class="bi bi-people me-1"></i> Monitoring Murid
                    </a>
                    <button onclick="window.print()" class="btn btn-light border btn-sm rounded-pill px-3 py-1.5 fw-bold fs-xs shadow-xs">
                        <i class="bi bi-printer me-1"></i> Cetak Ringkasan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.ims.rekap') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <select name="classroom_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Seluruh Kelas / Rombel (Agregat) --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                Kelas {{ $cls->name }} (Tingkat {{ $cls->level }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                TP {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="semester_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                                Semester {{ $sem->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['classroom_id', 'academic_year_id', 'semester_id']))
                        <a href="{{ route('panel.ims.rekap') }}" class="btn btn-sm btn-light border rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Stats (Compact on Mobile) -->
    <div class="row g-2 g-md-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Jumlah Responden Valid</span>
                        <h4 class="fw-extrabold text-dark mt-1 mb-0">{{ $respondentCount }} Murid</h4>
                        <span class="text-muted fs-xxs">Telah mengirimkan angket</span>
                    </div>
                    <div class="p-2.5 bg-teal-subtle rounded-3 text-mint">
                        <i class="bi bi-person-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Basis Asesmen</span>
                        <h4 class="fw-extrabold text-dark mt-1 mb-0">180 Butir</h4>
                        <span class="text-muted fs-xxs">9 Layanan & 4 Bidang</span>
                    </div>
                    <div class="p-2.5 bg-primary-subtle rounded-3 text-primary">
                        <i class="bi bi-clipboard2-data fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Status Pengolahan</span>
                        <h5 class="fw-extrabold text-success mt-1 mb-0">Terkini</h5>
                        <span class="text-muted fs-xxs">Real-time otomatis</span>
                    </div>
                    <div class="p-2.5 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-lightning-charge fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($respondentCount === 0)
        <!-- Empty State -->
        <div class="card card-app border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <div class="py-4">
                <i class="bi bi-clipboard-x fs-1 text-secondary mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Belum Ada Data Responden Terkirim</h5>
                <p class="text-muted small max-w-md mx-auto mb-3">
                    Belum ada murid yang mengirimkan lembar instrumen masalah untuk filter terpilih. Silakan periksa halaman Monitoring untuk melihat progres pengisian murid.
                </p>
                <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-2 fw-bold shadow-xs">
                    Buka Monitoring Pengisian Murid
                </a>
            </div>
        </div>
    @else
        <!-- 1. Ranking 15 Masalah Terbanyak -->
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">Ranking Masalah Terbanyak (Top 15 Butir Kebutuhan)</h5>
                    <span class="text-muted fs-xxs">Prioritas utama untuk intervensi bimbingan klasikal maupun kelompok</span>
                </div>
                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill fs-xxs fw-bold">Prioritas</span>
            </div>

            <!-- TAMPILAN MOBILE: CARD LIST (< 768px) -->
            <div class="d-md-none p-3">
                @foreach($topProblems as $rank => $item)
                    @php
                        $pct = round(($item->total_selected / $respondentCount) * 100, 1);
                    @endphp
                    <div class="mobile-card mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                @if($rank === 0)
                                    <span class="badge bg-danger text-white rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.82rem;">1</span>
                                @elseif($rank === 1)
                                    <span class="badge bg-warning text-dark rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.82rem;">2</span>
                                @elseif($rank === 2)
                                    <span class="badge bg-info text-white rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.82rem;">3</span>
                                @else
                                    <span class="badge bg-light text-muted border rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.82rem;">{{ $rank + 1 }}</span>
                                @endif
                                <span class="fw-bold text-mint font-monospace fs-6">#{{ $item->statement_number }}</span>
                            </div>
                            <span class="badge bg-danger-subtle text-danger badge-pill-custom">
                                {{ $item->total_selected }} Siswa ({{ $pct }}%)
                            </span>
                        </div>

                        <div class="card-name mb-3" style="font-size: 0.98rem !important; line-height: 1.55;">
                            {{ $item->statement_text }}
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark border badge-pill-custom">
                                Bidang {{ $item->field_name }}
                            </span>
                            <span class="badge bg-teal-subtle text-mint badge-pill-custom">
                                {{ $item->service_name }}
                            </span>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Persentase Frekuensi</span>
                                <span class="fw-bold text-danger">{{ $pct }}%</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 4px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- TAMPILAN DESKTOP: TABEL (>= 768px) -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 60px;" class="text-center ps-3.5">Rank</th>
                            <th style="width: 80px;" class="text-center">No Butir</th>
                            <th>Pernyataan Masalah</th>
                            <th style="width: 140px;">Bidang</th>
                            <th style="width: 190px;">Rekomendasi Layanan</th>
                            <th style="width: 130px;" class="text-center">Frekuensi</th>
                            <th style="width: 150px;" class="pe-3.5">Persentase Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @foreach($topProblems as $rank => $item)
                            @php
                                $pct = round(($item->total_selected / $respondentCount) * 100, 1);
                            @endphp
                            <tr>
                                <td class="text-center fw-bold ps-3.5">
                                    @if($rank === 0)
                                        <span class="badge bg-danger rounded-circle p-1">1</span>
                                    @elseif($rank === 1)
                                        <span class="badge bg-warning text-dark rounded-circle p-1">2</span>
                                    @elseif($rank === 2)
                                        <span class="badge bg-info rounded-circle p-1">3</span>
                                    @else
                                        <span class="text-muted">{{ $rank + 1 }}</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-muted">
                                    #{{ $item->statement_number }}
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->statement_text }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs">
                                        {{ $item->field_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-teal-subtle text-mint px-2 py-0.5 rounded fs-xxs">
                                        {{ $item->service_name }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-danger">
                                    {{ $item->total_selected }} Siswa
                                </td>
                                <td class="pe-3.5">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="fs-xs fw-bold text-dark">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-3">
            <!-- 2. Rekapitulasi per Bidang Bimbingan (4) -->
            <div class="col-12 col-lg-6">
                <div class="card card-app border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 px-3.5 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 fs-6">Distribusi Kebutuhan Per Bidang Bimbingan</h5>
                        <span class="text-muted fs-xxs">4 Bidang Bimbingan (45 Butir Soal per Bidang)</span>
                    </div>
                    <div class="card-body p-3.5 space-y-3">
                        @foreach($fieldBreakdown as $field)
                            @php
                                $colorMap = [
                                    'PRIBADI' => ['badge' => 'bg-primary-subtle text-primary', 'bar' => 'bg-primary'],
                                    'SOSIAL' => ['badge' => 'bg-info-subtle text-info', 'bar' => 'bg-info'],
                                    'BELAJAR' => ['badge' => 'bg-warning-subtle text-warning-emphasis', 'bar' => 'bg-warning'],
                                    'KARIR' => ['badge' => 'bg-purple-subtle text-purple', 'bar' => 'bg-purple'],
                                ];
                                $fName = strtoupper($field['name']);
                                $colors = $colorMap[$fName] ?? ['badge' => 'bg-secondary-subtle text-secondary', 'bar' => 'bg-secondary'];
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge {{ $colors['badge'] }} px-2 py-0.5 rounded-pill fs-xxs fw-semibold">
                                        Bidang {{ $field['name'] }}
                                    </span>
                                    <span class="fs-xs fw-bold text-dark">
                                        {{ $field['total_selected'] }} Masalah ({{ $field['percentage'] }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $colors['bar'] }}" role="progressbar" style="width: {{ $field['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. Rekapitulasi per Layanan BK (9) -->
            <div class="col-12 col-lg-6">
                <div class="card card-app border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white py-3 px-3.5 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 fs-6">Distribusi Rekomendasi Layanan BK</h5>
                        <span class="text-muted fs-xxs">9 Jenis Layanan Bimbingan & Konseling</span>
                    </div>
                    <div class="card-body p-3.5 space-y-2.5">
                        @foreach($serviceBreakdown as $srv)
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark fw-semibold fs-xs">
                                        {{ $srv['code'] }} &bull; {{ $srv['name'] }}
                                    </span>
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs">
                                        {{ $srv['total_selected'] }} Masalah ({{ $srv['percentage'] }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-teal" role="progressbar" style="width: {{ $srv['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
