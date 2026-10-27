@extends('layouts.walikelas')

@section('title', 'Beranda Wali Kelas')
@section('page_title', 'Beranda Wali Kelas')
@section('page_subtitle', $classroom ? 'Rombel ' . $classroom->name : 'Menunggu Penugasan')

@section('content')
{{-- 1. WELCOME PROFILE BANNER --}}
<div style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); border-radius: 14px; padding: 22px 26px; margin-bottom: 22px; color: #ffffff; position: relative; overflow: hidden; box-shadow: 0 4px 14px rgba(217, 119, 6, 0.25);">
    <div style="position: absolute; width: 220px; height: 220px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; top: -60px; right: 60px;"></div>
    <div style="position: absolute; width: 140px; height: 140px; background: rgba(13, 148, 136, 0.25); border-radius: 50%; bottom: -40px; right: 20px;"></div>

    <div class="row align-items-center g-3 position-relative z-1">
        <div class="col-auto">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: #ffffff; color: #d97706; font-size: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        </div>
        <div class="col">
            <div class="badge rounded-pill bg-white text-warning-emphasis fw-extrabold px-3 py-1 mb-2 shadow-xs">
                PORTAL WALI KELAS &bull; {{ $classroom ? 'KELAS ' . $classroom->name : 'BELUM ADA ROMBEL' }}
            </div>
            <h3 class="fw-extrabold mb-1" style="font-size: 1.45rem;">
                Selamat Datang, {{ $teacher?->name ?? $user->name }}!
            </h3>
            <div class="small opacity-90">
                <i class="bi bi-building me-1"></i>{{ app_setting('school_name', 'Madrasah / Sekolah') }}
                @if($classroom)
                    &bull; {{ $totalStudents }} Murid Terdaftar
                @endif
            </div>
        </div>
        <div class="col-md-auto text-md-end">
            <a href="{{ route('walikelas.aduan.create') }}" class="btn btn-light rounded-pill px-3.5 py-2 fw-bold text-warning-emphasis shadow-sm">
                <i class="bi bi-plus-circle-fill text-warning me-1.5"></i> Lapor Kasus Murid ke BK
            </a>
        </div>
    </div>
</div>

{{-- Etalase Pamflet Kreativitas Siswa & Pengumuman Madrasah --}}
<x-pamphlet-carousel />
<x-announcements-widget />

{{-- 2. STATS CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-app p-3 d-flex align-items-center gap-3">
            <div class="p-3 rounded-3 bg-teal-subtle text-mint fs-3">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="text-muted fs-xs fw-bold text-uppercase">Total Murid Rombel</div>
                <div class="fs-4 fw-extrabold text-dark">{{ $totalStudents }}</div>
                <div class="text-muted fs-xxs">Peserta Didik Aktif</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-app p-3 d-flex align-items-center gap-3">
            <div class="p-3 rounded-3 bg-primary-subtle text-primary fs-3">
                <i class="bi bi-chat-heart-fill"></i>
            </div>
            <div>
                <div class="text-muted fs-xs fw-bold text-uppercase">Total Konseling</div>
                <div class="fs-4 fw-extrabold text-dark">{{ $totalCounseling }}</div>
                <div class="text-muted fs-xxs">Pengajuan Siswa</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-app p-3 d-flex align-items-center gap-3">
            <div class="p-3 rounded-3 bg-warning-subtle text-warning-emphasis fs-3">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="text-muted fs-xs fw-bold text-uppercase">Konseling Berjalan</div>
                <div class="fs-4 fw-extrabold text-warning-emphasis">{{ $activeCounselingCount }}</div>
                <div class="text-muted fs-xxs">Perlu Pemantauan</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-app p-3 d-flex align-items-center gap-3">
            <div class="p-3 rounded-3 bg-danger-subtle text-danger fs-3">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div>
                <div class="text-muted fs-xs fw-bold text-uppercase">Aduan Kasus Diajukan</div>
                <div class="fs-4 fw-extrabold text-danger">{{ $totalComplaints }}</div>
                <div class="text-muted fs-xxs">Ke Guru BK</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- 3. DAFTAR MURID ROMBEL BINAAN --}}
    <div class="col-lg-7">
        <div class="card card-app h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-people me-1.5 text-warning"></i> Daftar Murid di Rombel {{ $classroom?->name ?? '' }}
                </h6>
                <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1">{{ $totalStudents }} Siswa</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-xs text-muted">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th class="text-center">L/P</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $stu)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $stu->name }}</div>
                                    </td>
                                    <td class="font-monospace small text-muted">{{ $stu->nisn }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $stu->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill">
                                            {{ $stu->gender }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('walikelas.aduan.create', ['student_id' => $stu->id]) }}" class="btn btn-outline-warning btn-sm rounded-pill px-2.5 py-1 fw-bold" title="Lapor Kasus Anak Ini">
                                            <i class="bi bi-flag-fill me-1"></i> Lapor Kasus
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada data murid di rombel binaan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. SESI KONSELING TERAKHIR MURID & ADUAN KASUS --}}
    <div class="col-lg-5">
        {{-- Konseling Terakhir --}}
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-chat-heart text-primary me-1.5"></i> Sesi Konseling Siswa Rombel
                </h6>
                <span class="badge bg-light text-muted border">Info Rombel</span>
            </div>
            <div class="card-body p-3">
                @forelse($recentCounselings as $counseling)
                    <div class="p-2.5 rounded-3 border bg-light mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold text-dark small">{{ $counseling->student?->name }}</span>
                            <span class="badge bg-teal-subtle text-mint small rounded-pill">{{ ucfirst($counseling->status) }}</span>
                        </div>
                        <div class="text-muted fs-xs mb-1">{{ Str::limit($counseling->purpose, 55) }}</div>
                        <div class="text-muted fs-xxs">
                            <i class="bi bi-person-badge me-1"></i>Konselor: {{ $counseling->assignedCounselor?->name ?? 'Belum Ditentukan' }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted small">
                        Belum ada pengajuan konseling dari murid di rombel Anda.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Aduan Kasus Terakhir --}}
        <div class="card card-app">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-flag text-danger me-1.5"></i> Aduan Kasus yang Anda Ajukan
                </h6>
                <a href="{{ route('walikelas.aduan.index') }}" class="small text-warning-emphasis fw-bold text-decoration-none">Semua Aduan &rarr;</a>
            </div>
            <div class="card-body p-3">
                @forelse($recentComplaints as $comp)
                    <div class="p-2.5 rounded-3 border mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fw-bold text-dark small">{{ $comp->student?->name }}</span>
                            <span class="badge {{ $comp->status_badge_class }} small rounded-pill">{{ $comp->status_label }}</span>
                        </div>
                        <div class="small fw-semibold text-muted">{{ $comp->title }}</div>
                        <div class="text-muted fs-xxs mt-1">
                            <i class="bi bi-calendar-event me-1"></i>{{ $comp->incident_date->format('d M Y') }} &bull; {{ $comp->category_label }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted small">
                        Belum ada aduan kasus yang Anda laporkan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
