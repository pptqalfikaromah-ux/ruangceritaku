@extends('layouts.panel')

@section('title', 'Rekapitulasi Analisis Kebutuhan IMS')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Asesmen Kebutuhan</span>
                <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs fw-medium rounded-pill">Need Assessment BK</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Rekapitulasi Instrumen Masalah Siswa</h1>
            <p class="text-muted small mb-0">Pemetaan kebutuhan konseling komprehensif berdasarkan 180 butir pernyataan masalah.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i> Monitoring Murid
            </a>
            <button onclick="window.print()" class="btn btn-light border btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Ringkasan
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.ims.rekap') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <select name="classroom_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Seluruh Kelas / Rombel (Agregat Sekolah) --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                Kelas {{ $cls->name }} (Tingkat {{ $cls->level }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                                TP {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                                Semester {{ $sem->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary bg-emerald border-0 w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['classroom_id', 'academic_year_id', 'semester_id']))
                        <a href="{{ route('panel.ims.rekap') }}" class="btn btn-sm btn-light border" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Jumlah Responden Valid</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $respondentCount }} Murid</h3>
                        <span class="text-muted fs-xs">Telah mengirimkan angket</span>
                    </div>
                    <div class="p-3 bg-emerald-subtle rounded-3 text-emerald">
                        <i class="bi bi-person-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Basis Asesmen</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">160 Butir</h4>
                        <span class="text-muted fs-xs">Komprehensif 8 Layanan BK & 4 Bidang</span>
                    </div>
                    <div class="p-3 bg-primary-subtle rounded-3 text-primary">
                        <i class="bi bi-clipboard2-data fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Status Pengolahan</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">Data Terkini</h4>
                        <span class="text-muted fs-xs">Diperbarui real-time otomatis</span>
                    </div>
                    <div class="p-3 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-lightning-charge fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($respondentCount === 0)
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-3 p-5 text-center bg-white">
            <div class="py-4">
                <i class="bi bi-clipboard-x fs-1 text-secondary mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Belum Ada Data Responden Terkirim</h5>
                <p class="text-muted small max-w-md mx-auto mb-3">
                    Belum ada murid yang mengirimkan lembar instrumen masalah untuk filter terpilih. Silakan periksa halaman Monitoring untuk melihat progres pengisian murid.
                </p>
                <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-sm btn-primary bg-emerald border-0">
                    Buka Monitoring Pengisian Murid
                </a>
            </div>
        </div>
    @else
        <!-- 1. Ranking 15 Masalah Terbanyak -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-sm">Ranking Masalah Terbanyak (Top 15 Butir Kebutuhan)</h5>
                    <span class="text-muted fs-xs">Prioritas utama untuk intervensi bimbingan klasikal maupun kelompok</span>
                </div>
                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill fs-xs">Prioritas Intervensi</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light fs-xs text-uppercase text-muted">
                        <tr>
                            <th style="width: 60px;" class="text-center">Rank</th>
                            <th style="width: 70px;" class="text-center">No Butir</th>
                            <th>Pernyataan Masalah</th>
                            <th style="width: 130px;">Bidang</th>
                            <th style="width: 180px;">Rekomendasi Layanan</th>
                            <th style="width: 140px;" class="text-center">Frekuensi</th>
                            <th style="width: 150px;">Persentase Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="fs-sm">
                        @foreach($topProblems as $rank => $item)
                            @php
                                $pct = round(($item->total_selected / $respondentCount) * 100, 1);
                            @endphp
                            <tr>
                                <td class="text-center fw-bold">
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
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill fs-xs">
                                        {{ $item->field_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-emerald-subtle text-emerald px-2 py-1 rounded-2 fs-xs">
                                        {{ $item->service_name }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-danger">
                                    {{ $item->total_selected }} Siswa
                                </td>
                                <td>
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

        <div class="row g-4">
            <!-- 2. Rekapitulasi per Bidang Bimbingan (4) -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 fs-sm">Distribusi Kebutuhan Per Bidang Bimbingan</h5>
                        <span class="text-muted fs-xs">4 Bidang Bimbingan (45 Butir Soal per Bidang)</span>
                    </div>
                    <div class="card-body p-4 space-y-4">
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
                                    <span class="badge {{ $colors['badge'] }} px-2 py-1 rounded-pill fs-xs fw-semibold">
                                        Bidang {{ $field['name'] }}
                                    </span>
                                    <span class="fs-xs fw-bold text-dark">
                                        {{ $field['total_selected'] }} Masalah ({{ $field['percentage'] }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar {{ $colors['bar'] }}" role="progressbar" style="width: {{ $field['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. Rekapitulasi per 8 Layanan BK -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 fs-sm">Distribusi Kebutuhan Per 8 Layanan BK</h5>
                        <span class="text-muted fs-xs">20 Butir Soal per Layanan BK</span>
                    </div>
                    <div class="card-body p-4 space-y-3">
                        @foreach($serviceBreakdown as $srv)
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-dark fs-xs">{{ $srv['name'] }}</span>
                                    <span class="fs-xs text-muted">
                                        <span class="fw-bold text-dark">{{ $srv['total_selected'] }}</span> kali dipilih ({{ $srv['percentage'] }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-emerald" role="progressbar" style="width: {{ $srv['percentage'] }}%"></div>
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
