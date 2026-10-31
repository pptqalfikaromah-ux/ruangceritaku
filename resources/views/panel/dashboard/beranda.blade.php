@extends('layouts.panel')

@section('title', 'Beranda')

@section('breadcrumb')
    <span class="current">Beranda</span>
@endsection

@section('content')
@php
    $counselorName = $counselor?->name ?? $user->name;
    $schoolName = app_setting('school_name', 'Sekolah');
@endphp

{{-- 1. WELCOME PROFILE BANNER (Android App Style) --}}
<div class="rounded-4 text-white position-relative overflow-hidden mb-3 mb-md-4 shadow-sm"
     style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); padding: clamp(16px, 3.5vw, 24px); box-shadow: 0 4px 16px rgba(13, 148, 136, 0.2);">
    <!-- Decorative background ambient circles -->
    <div style="position: absolute; width: 220px; height: 220px; background: rgba(255, 255, 255, 0.07); border-radius: 50%; top: -70px; right: 80px; pointer-events: none;"></div>
    <div style="position: absolute; width: 140px; height: 140px; background: rgba(245, 158, 11, 0.14); border-radius: 50%; bottom: -40px; right: 20px; pointer-events: none;"></div>

    <div class="row align-items-center g-3 position-relative z-1">
        <!-- User Avatar & Identity -->
        <div class="col-auto">
            <img src="{{ auth()->user()->avatar_url }}"
                 alt="{{ $counselorName }}"
                 class="rounded-circle shadow-sm"
                 style="width: clamp(52px, 8vw, 68px); height: clamp(52px, 8vw, 68px); object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.7);">
        </div>

        <div class="col min-w-0">
            <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                <span class="badge rounded-pill fw-bold" style="background: rgba(255, 255, 255, 0.22); color: #ffffff; font-size: 0.72rem; letter-spacing: 0.03em;">
                    <i class="bi bi-shield-check me-1"></i>{{ $isAdmin ? 'Administrator' : 'Guru BK' }}
                </span>
                <span class="text-white opacity-75 small text-truncate d-none d-sm-inline" style="font-size: 0.76rem;">
                    &bull; {{ $schoolName }}
                </span>
            </div>

            <h3 class="fw-extrabold mb-1 text-truncate" style="font-size: clamp(1.15rem, 3.8vw, 1.45rem); line-height: 1.25;">
                Selamat Datang, {{ $counselorName }}
            </h3>

            <div class="small opacity-90 d-flex flex-wrap align-items-center gap-1.5" style="font-size: 0.78rem;">
                <span class="badge bg-black bg-opacity-20 rounded-pill px-2.5 py-1">
                    <i class="bi bi-calendar3 me-1"></i>TA: {{ $activeYear?->name ?? '2025/2026' }} (Semester {{ $activeSemester?->name ?? 'Ganjil' }})
                </span>
            </div>
        </div>

        <!-- Quick Top Actions (Mobile 2-Button Grid) -->
        <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
            <div class="d-flex gap-2">
                <a href="{{ route('panel.konseling.index') }}"
                   class="btn btn-gold btn-sm flex-fill flex-md-grow-0 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm"
                   style="min-height: 40px; font-size: 0.8rem;">
                    <i class="bi bi-chat-heart"></i>
                    <span>Agenda Konseling</span>
                </a>
                <a href="{{ route('panel.ims.master') }}"
                   class="btn btn-outline-light btn-sm flex-fill flex-md-grow-0 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-1.5"
                   style="min-height: 40px; background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.35); font-size: 0.8rem;">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Data IMS</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Etalase Pamflet Kreativitas Siswa & Pengumuman Sekolah --}}
<x-pamphlet-carousel />
<x-announcements-widget />

{{-- Mode Guru BK Notification if Not Admin --}}
@if(!$isAdmin)
    <div class="alert border-0 mb-3 mb-md-4 d-flex align-items-center py-2.5 px-3 rounded-3" style="background: #f0fdfa; border: 1px solid #ccfbf1 !important; color: #0f766e;">
        <i class="bi bi-info-circle-fill fs-5 me-2.5 text-mint"></i>
        <div class="small">
            <strong>Mode Guru BK: Menampilkan Data Rombel Binaan:</strong> Anda sedang melihat data murid dan rombel binaan Anda pada semester aktif.
        </div>
    </div>
@endif

{{-- 2. CORE METRICS KPI CARDS (2 Kolom di HP / 4 Kolom di Desktop) --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    <!-- Card 1: Siswa Aktif -->
    <div class="col-6 col-md-3">
        <div class="h-100 rounded-4 bg-white border p-3 p-md-3.5 d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2.5 gap-md-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 42px; height: 42px; background: #f0fdfa; color: #0d9488; font-size: 1.35rem;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden min-w-0">
                <div class="text-uppercase tracking-wider fw-bold text-muted text-truncate" style="font-size: 0.65rem;">
                    {{ $isAdmin ? 'TOTAL MURID' : 'MURID BINAAN' }}
                </div>
                <div class="fw-extrabold text-mint my-0.5" style="font-size: clamp(1.2rem, 3.5vw, 1.45rem); line-height: 1.1;">
                    {{ number_format($totalStudents, 0, ',', '.') }}
                </div>
                <div class="text-muted text-truncate" style="font-size: 0.68rem;">
                    {{ $maleCount }} L &bull; {{ $femaleCount }} P
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Rombel / Kelas -->
    <div class="col-6 col-md-3">
        <div class="h-100 rounded-4 bg-white border p-3 p-md-3.5 d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2.5 gap-md-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 42px; height: 42px; background: #fef3c7; color: #d97706; font-size: 1.35rem;">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden min-w-0">
                <div class="text-uppercase tracking-wider fw-bold text-muted text-truncate" style="font-size: 0.65rem;">
                    ROMBEL / KELAS
                </div>
                <div class="fw-extrabold my-0.5" style="color: #d97706; font-size: clamp(1.2rem, 3.5vw, 1.45rem); line-height: 1.1;">
                    {{ $totalClassrooms }} Kelas
                </div>
                <div class="text-muted text-truncate" style="font-size: 0.68rem;">
                    {{ $isAdmin ? 'Seluruh rombel' : 'Rombel binaan' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Aduan & Kasus Siswa -->
    <div class="col-6 col-md-3">
        <div class="h-100 rounded-4 bg-white border p-3 p-md-3.5 d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2.5 gap-md-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 42px; height: 42px; background: #f0fdf4; color: #16a34a; font-size: 1.35rem;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden min-w-0">
                <div class="text-uppercase tracking-wider fw-bold text-muted text-truncate" style="font-size: 0.65rem;">
                    ADUAN KASUS
                </div>
                <div class="fw-extrabold my-0.5" style="color: #16a34a; font-size: clamp(1.15rem, 3.5vw, 1.4rem); line-height: 1.1;">
                    Kondusif
                </div>
                <div class="text-muted text-truncate" style="font-size: 0.68rem;">
                    Aman terlindungi
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Layanan & Asesmen BK -->
    <div class="col-6 col-md-3">
        <div class="h-100 rounded-4 bg-white border p-3 p-md-3.5 d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2.5 gap-md-3 shadow-xs">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 42px; height: 42px; background: #eff6ff; color: #2563eb; font-size: 1.35rem;">
                <i class="bi bi-clipboard2-data-fill"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden min-w-0">
                <div class="text-uppercase tracking-wider fw-bold text-muted text-truncate" style="font-size: 0.65rem;">
                    LAYANAN BK
                </div>
                <div class="fw-extrabold my-0.5" style="color: #2563eb; font-size: clamp(1.2rem, 3.5vw, 1.45rem); line-height: 1.1;">
                    {{ $totalServiceTypes ?? 15 }} Jenis
                </div>
                <div class="text-muted text-truncate" style="font-size: 0.68rem;">
                    4 Bidang Aktif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. AKSI CEPAT / SHORTCUTS MODUL --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('panel.murid.index') }}" class="card card-app text-decoration-none h-100 p-3 d-flex flex-row align-items-center gap-3 transition-lift" style="border-radius: 10px;">
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="background: #f0fdfa; color: #0d9488; width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-dark small text-truncate">Data Siswa Binaan</div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">Profil & asuhan murid</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('panel.rombel.index') }}" class="card card-app text-decoration-none h-100 p-3 d-flex flex-row align-items-center gap-3 transition-lift" style="border-radius: 10px;">
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="background: #fef3c7; color: #d97706; width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="bi bi-door-open"></i>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-dark small text-truncate">Data Rombel</div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">Daftar kelas asuhan</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('panel.ims.master') }}" class="card card-app text-decoration-none h-100 p-3 d-flex flex-row align-items-center gap-3 transition-lift" style="border-radius: 10px;">
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="background: #e0f2fe; color: #0284c7; width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="bi bi-clipboard2-check"></i>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-dark small text-truncate">Instrumen IMS</div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">Pemetaan masalah siswa</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('panel.layanan.index') }}" class="card card-app text-decoration-none h-100 p-3 d-flex flex-row align-items-center gap-3 transition-lift" style="border-radius: 10px;">
            <div class="rounded-3 p-2.5 d-flex align-items-center justify-content-center" style="background: #f0fdf4; color: #16a34a; width: 44px; height: 44px; font-size: 1.3rem;">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-dark small text-truncate">Master Layanan BK</div>
                <div class="text-muted text-truncate" style="font-size: 0.72rem;">15 Layanan & 4 Bidang</div>
            </div>
        </a>
    </div>
</div>

{{-- 4. ANALITIK: DISTRIBUSI ROMBEL & KARAKTERISTIK BIDANG BK --}}
<div class="row g-3 mb-4">
    <!-- Kolom 1: Distribusi Siswa per Rombel -->
    <div class="col-lg-7">
        <div class="card card-app h-100" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-line text-mint me-1.5"></i> Distribusi Siswa per Rombel Binaan
                    </h6>
                    <div class="text-muted small" style="font-size: 0.74rem;">Sebaran jumlah siswa di rombel asuhan semester aktif</div>
                </div>
                <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.74rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-3.5">
                @if(isset($classroomsData) && $classroomsData->isNotEmpty())
                    <div class="d-flex flex-column gap-3">
                        @php
                            $maxStudent = $classroomsData->max('students_count') ?: 1;
                        @endphp
                        @foreach($classroomsData as $rombel)
                            @php
                                $percent = round(($rombel->students_count / $maxStudent) * 100);
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">
                                        <i class="bi bi-door-closed text-muted me-1"></i> Kelas {{ $rombel->name }}
                                        <span class="text-muted fw-normal" style="font-size: 0.74rem;">(Tingkat {{ $rombel->level }})</span>
                                    </span>
                                    <span class="badge bg-teal-subtle text-mint fw-bold" style="font-size: 0.72rem; border-radius: 6px;">
                                        {{ $rombel->students_count }} Siswa
                                    </span>
                                </div>
                                <div style="height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ max($percent, 6) }}%; background: linear-gradient(90deg, #0d9488 0%, #10b981 100%); border-radius: 999px; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-inbox fs-2 d-block mb-1 opacity-50"></i>
                        Belum ada data rombel aktif pada semester ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom 2: Proporsi Gender & 4 Bidang BK -->
    <div class="col-lg-5">
        <div class="card card-app h-100" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 px-3.5 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pie-chart text-mint me-1.5"></i> Karakteristik & 4 Bidang BK
                </h6>
                <div class="text-muted small" style="font-size: 0.74rem;">Proporsi gender siswa & cakupan bimbingan konseling</div>
            </div>
            <div class="card-body p-3.5">
                <!-- Gender Ratio Progress Bar -->
                <div class="mb-3.5 p-3 rounded-3" style="background: #f8fafc; border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center small mb-1.5 fw-bold">
                        <span style="color: #0d9488;"><i class="bi bi-gender-male me-1"></i> Laki-laki: {{ $maleCount }} ({{ $malePercentage }}%)</span>
                        <span style="color: #e11d48;"><i class="bi bi-gender-female me-1"></i> Perempuan: {{ $femaleCount }} ({{ $femalePercentage }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 999px; background: #e2e8f0;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $malePercentage }}%; background-color: #0d9488;" aria-valuenow="{{ $malePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar" role="progressbar" style="width: {{ $femalePercentage }}%; background-color: #f43f5e;" aria-valuenow="{{ $femalePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- 4 Bidang Bimbingan Konseling List -->
                <div class="small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                    4 Bidang Bimbingan & Konseling
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="p-2.5 border rounded-3 d-flex align-items-center justify-content-between bg-white shadow-xs">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-heart text-mint fs-5"></i>
                            <span class="small fw-bold text-dark">Bimbingan Pribadi</span>
                        </div>
                        <span class="badge bg-light text-muted border small">Karakter & Potensi Diri</span>
                    </div>

                    <div class="p-2.5 border rounded-3 d-flex align-items-center justify-content-between bg-white shadow-xs">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people text-primary fs-5"></i>
                            <span class="small fw-bold text-dark">Bimbingan Sosial</span>
                        </div>
                        <span class="badge bg-light text-muted border small">Sosial & Anti-Bullying</span>
                    </div>

                    <div class="p-2.5 border rounded-3 d-flex align-items-center justify-content-between bg-white shadow-xs">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-book text-warning fs-5"></i>
                            <span class="small fw-bold text-dark">Bimbingan Belajar</span>
                        </div>
                        <span class="badge bg-light text-muted border small">Motivasi & Prestasi</span>
                    </div>

                    <div class="p-2.5 border rounded-3 d-flex align-items-center justify-content-between bg-white shadow-xs">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-compass text-info fs-5"></i>
                            <span class="small fw-bold text-dark">Bimbingan Karir</span>
                        </div>
                        <span class="badge bg-light text-muted border small">Cita-cita & Studi Lanjut</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 5. TABEL PENUGASAN GURU BK SEMESTER AKTIF --}}
<div class="card card-app mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-badge text-mint me-1.5"></i> Penugasan Guru BK & Konselor Semester Aktif
            </h6>
            <div class="text-muted small" style="font-size: 0.74rem;">Daftar pembagian tugas bimbingan konseling dan rombel asuhan</div>
        </div>
        <span class="badge rounded-pill bg-light text-dark border px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
            <i class="bi bi-calendar3 me-1 text-mint"></i> {{ $activeYear?->name ?? '2025/2026' }} - {{ $activeSemester?->name ?? 'Ganjil' }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3.5" style="width: 50px;">NO</th>
                    <th>GURU BK / KONSELOR</th>
                    <th>ROMBEL ASISTENSI</th>
                    <th>TAHUN & SEMESTER</th>
                    <th style="width: 150px;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAssignments as $index => $assign)
                    <tr>
                        <td class="ps-3.5 text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; background: #f0fdfa; color: #0d9488; font-size: 0.85rem; border: 1.5px solid #ccfbf1;">
                                    {{ substr($assign->counselor?->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $assign->counselor?->name_with_title ?? $assign->counselor?->name ?? 'Guru BK' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">NIP: {{ $assign->counselor?->nip_nuptk ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light border text-dark fw-semibold px-2.5 py-1.5 rounded-pill">
                                <i class="bi bi-door-closed me-1 text-mint"></i> Kelas {{ $assign->classroom?->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="small text-muted">
                                {{ $assign->semester?->academicYear?->name }} ({{ $assign->semester?->name }})
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background-color: #dcfce7; color: #15803d; font-weight: 700; font-size: 0.72rem; padding: 5px 12px;">
                                <i class="bi bi-check-circle me-1"></i> Aktif Membina
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">
                            <i class="bi bi-inbox fs-2 d-block mb-1 opacity-50"></i>
                            Belum ada penugasan guru BK yang tercatat pada semester aktif ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .transition-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .transition-lift:hover {
        transform: translateY(-3px);
        border-color: var(--primary) !important;
        box-shadow: 0 8px 18px rgba(13, 148, 136, 0.1) !important;
    }
</style>
@endsection
