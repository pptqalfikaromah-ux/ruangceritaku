@extends('layouts.panel')

@section('title', 'Peta Situs')

@section('breadcrumb')
    <span class="current">Peta Situs</span>
@endsection

@section('content')
{{-- Profile Banner (Identik dengan Portal Murid) --}}
<div style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);border-radius:10px;padding:20px 24px;margin-bottom:20px;color:#fff;position:relative;overflow:hidden;">
    <div style="position:absolute;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%;top:-60px;right:60px;"></div>
    <div style="position:absolute;width:120px;height:120px;background:rgba(245,158,11,.12);border-radius:50%;bottom:-30px;right:20px;"></div>
    <div class="row g-3 align-items-center position-relative">
        <div class="col-auto">
            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=f0fdfa&color=0d9488&bold=true&size=80' }}"
                 alt="{{ auth()->user()->name }}"
                 style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.5);box-shadow:0 4px 12px rgba(0,0,0,.15);">
        </div>
        <div class="col">
            <div style="font-size:.72rem;font-weight:700;opacity:.8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;">
                {{ $isAdmin ? 'Portal Administrator' : 'Portal Guru Bimbingan & Konseling' }}
            </div>
            <div style="font-size:1.35rem;font-weight:800;line-height:1.2;margin-bottom:4px;">
                Selamat Datang, {{ auth()->user()->name }}
            </div>
            <div style="font-size:.82rem;opacity:.9;">
                <i class="bi bi-building me-1"></i>{{ app_setting('school_name', 'Madrasah / Sekolah') }}
                &nbsp;&bull;&nbsp;
                <i class="bi bi-calendar3 me-1"></i>Tahun Ajaran {{ $activeYear?->name ?? '2025/2026' }} &bull; Semester {{ $activeSemester?->name ?? 'Ganjil' }}
            </div>
        </div>
        <div class="col-md-auto text-md-end mt-2 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="{{ route('panel.konseling.index') }}"
               style="display:inline-flex;align-items:center;gap:6px;background:#f59e0b;color:#1e293b;border:none;border-radius:6px;padding:9px 18px;font-weight:800;font-size:.83rem;text-decoration:none;box-shadow:0 2px 8px rgba(245,158,11,.4);transition:background .2s;">
                <i class="bi bi-chat-heart"></i> Agenda Konseling
            </a>
            <a href="{{ route('panel.ims.master') }}"
               style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:6px;padding:9px 18px;font-weight:700;font-size:.83rem;text-decoration:none;transition:background .2s;">
                <i class="bi bi-clipboard2-check"></i> Data IMS
            </a>
        </div>
    </div>
</div>

<!-- Stats Row (Identik dengan format card di Portal Murid) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#f0fdfa;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-people" style="font-size:1.4rem;color:#0d9488;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">
                    {{ $isAdmin ? 'Total Murid' : 'Murid Binaan' }}
                </div>
                <div style="font-size:1.3rem;font-weight:800;color:#0d9488;line-height:1.1;">{{ $totalStudents }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">{{ $isAdmin ? 'Seluruh madrasah' : 'Rombel aktif' }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-door-open" style="font-size:1.4rem;color:#d97706;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">
                    {{ $isAdmin ? 'Total Rombel' : 'Rombel Binaan' }}
                </div>
                <div style="font-size:1.3rem;font-weight:800;color:#d97706;line-height:1.1;">{{ $totalClassrooms }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">Kelas terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-person-badge" style="font-size:1.4rem;color:#16a34a;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">
                    Guru BK
                </div>
                <div style="font-size:1.3rem;font-weight:800;color:#16a34a;line-height:1.1;">{{ $totalCounselors }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">Konselor aktif</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-calendar-check" style="font-size:1.4rem;color:#2563eb;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">
                    Semester Aktif
                </div>
                <div style="font-size:1.1rem;font-weight:800;color:#2563eb;line-height:1.2;">{{ $activeSemester ? $activeSemester->name : 'Nonaktif' }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">TA {{ $activeYear?->name ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Mode Guru BK Indicator if not admin -->
@if(!$isAdmin)
    <div class="alert bg-teal-subtle border-0 mb-4 d-flex align-items-center py-2 px-3 rounded-3">
        <i class="bi bi-info-circle-fill fs-5 me-2 text-teal"></i>
        <div class="small">
            <strong>Mode Guru BK: Menampilkan Data Rombel Binaan:</strong> Anda sedang melihat data murid dan rombel binaan Anda pada semester aktif.
        </div>
    </div>
@endif

<!-- EMIS GTK Pill Navigation Buttons -->
<div class="d-flex align-items-center gap-2 mb-4">
    <button class="btn btn-emis-pill btn-emis-pill-active" id="btnTabMenu" type="button" onclick="showDashboardTab('menu')">
        <i class="bi bi-grid-fill"></i> Daftar Menu Portal
    </button>
    <button class="btn btn-emis-pill btn-emis-pill-outline" id="btnTabStat" type="button" onclick="showDashboardTab('stat')">
        <i class="bi bi-pie-chart-fill"></i> Statistik & Grafik Rombel
    </button>
</div>

<!-- ========================================================
     TAB 1: DAFTAR MENU (EXACT REPLICA OF EMIS GTK SITEMAP)
     ======================================================== -->
<div id="tabContentMenu">
    <!-- Section 1: Beranda -->
    <div class="emis-section-title">
        <i class="bi bi-house"></i> Beranda
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.dashboard') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-house"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Beranda</div>
                        <p class="emis-feature-desc">Halaman utama dashboard konseling</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ $isAdmin ? route('panel.setting.index') : route('panel.setting.profile') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Madrasah Saya</div>
                        <p class="emis-feature-desc">Lihat madrasah/sekolah tempat bertugas</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.setting.profile') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Data Profil Saya</div>
                        <p class="emis-feature-desc">Perbarui data diri, kontak, dan foto profil</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
    </div>

    <!-- Section 2: Data Induk & Kesiswaan -->
    <div class="emis-section-title">
        <i class="bi bi-folder"></i> Data Induk & Kesiswaan
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.murid.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Data Diri & Siswa</div>
                        <p class="emis-feature-desc">{{ $isAdmin ? 'Perbarui data siswa, import/export CSV' : 'Daftar murid rombel binaan Anda' }}</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.rombel.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Data Rombel</div>
                        <p class="emis-feature-desc">{{ $isAdmin ? 'Kelola kelas dan rombongan belajar' : 'Lihat rombel kelas binaan Anda' }}</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.penugasan.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Penugasan Guru BK</div>
                        <p class="emis-feature-desc">Distribusi penugasan binaan rombel semester</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        @if($isAdmin)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('panel.guru-bk.index') }}" class="emis-feature-card">
                    <div class="d-flex align-items-center">
                        <div class="emis-feature-icon-box">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="emis-feature-info">
                            <div class="emis-feature-title">Data Guru BK</div>
                            <p class="emis-feature-desc">Kelola tenaga pendidik konseling & akun login</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right emis-feature-arrow"></i>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('panel.wali-kelas.index') }}" class="emis-feature-card">
                    <div class="d-flex align-items-center">
                        <div class="emis-feature-icon-box">
                            <i class="bi bi-person-video3"></i>
                        </div>
                        <div class="emis-feature-info">
                            <div class="emis-feature-title">Data Wali Kelas</div>
                            <p class="emis-feature-desc">Kelola data wali kelas & pembuatan akun login</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right emis-feature-arrow"></i>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('panel.penugasan-wali-kelas.index') }}" class="emis-feature-card">
                    <div class="d-flex align-items-center">
                        <div class="emis-feature-icon-box">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="emis-feature-info">
                            <div class="emis-feature-title">Penugasan Wali Kelas</div>
                            <p class="emis-feature-desc">Penugasan wali kelas sesuai rombel semester aktif</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right emis-feature-arrow"></i>
                </a>
            </div>
        @endif
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.layanan.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Master Layanan BK</div>
                        <p class="emis-feature-desc">9 Layanan, 6 Kegiatan, 4 Bidang, 4 Fungsi</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
    </div>

    <!-- Section 3: Layanan Konseling & Kasus -->
    <div class="emis-section-title">
        <i class="bi bi-heart-pulse"></i> Layanan Konseling & Kasus
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.pengaduan.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Layanan Pengaduan Anak</div>
                        <p class="emis-feature-desc"><span class="badge bg-danger-subtle text-danger me-1">Aktif</span> Perlindungan & penanganan perundungan</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.aduan-walikelas.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box" style="background:#fef2f2 !important; color:#dc2626 !important;">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Aduan Kasus Wali Kelas</div>
                        <p class="emis-feature-desc"><span class="badge bg-warning-subtle text-warning me-1">Wali Kelas</span> Laporan insidental murid untuk tindak lanjut BK</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.konseling.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Agenda Konseling Mandiri</div>
                        <p class="emis-feature-desc"><span class="badge bg-emerald-subtle text-emerald me-1">Aktif</span> Persetujuan & penetapan jadwal konseling</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.ims.master') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Instrumen Masalah Siswa (IMS)</div>
                        <p class="emis-feature-desc"><span class="badge bg-emerald-subtle text-emerald me-1">180 Butir</span> Asesmen kebutuhan konseling murid</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.program-bk.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Program BK & Dokumen RPL</div>
                        <p class="emis-feature-desc"><span class="badge bg-emerald-subtle text-emerald me-1">Prota/Promes</span> Rencana Pelaksanaan Layanan</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.laporan.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Laporan & Rekapitulasi</div>
                        <p class="emis-feature-desc"><span class="badge bg-emerald-subtle text-emerald me-1">Cetak Kop</span> Laporan berkala & arsip madrasah</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
    </div>

    <!-- Section 4: Administrasi -->
    <div class="emis-section-title">
        <i class="bi bi-gear"></i> Administrasi
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('panel.tahun-ajaran.index') }}" class="emis-feature-card">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="emis-feature-info">
                        <div class="emis-feature-title">Tahun Ajaran & Semester</div>
                        <p class="emis-feature-desc">Status semester aktif: {{ $activeSemester?->full_name ?? 'Ganjil' }}</p>
                    </div>
                </div>
                <i class="bi bi-arrow-right emis-feature-arrow"></i>
            </a>
        </div>
        @if($isAdmin)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('panel.akun.index') }}" class="emis-feature-card">
                    <div class="d-flex align-items-center">
                        <div class="emis-feature-icon-box">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div class="emis-feature-info">
                            <div class="emis-feature-title">Manajemen Akun Pengguna</div>
                            <p class="emis-feature-desc">Generate akun murid massal & atur hak akses</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right emis-feature-arrow"></i>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('panel.setting.index') }}" class="emis-feature-card">
                    <div class="d-flex align-items-center">
                        <div class="emis-feature-icon-box">
                            <i class="bi bi-building-gear"></i>
                        </div>
                        <div class="emis-feature-info">
                            <div class="emis-feature-title">Pengaturan Madrasah/Sekolah</div>
                            <p class="emis-feature-desc">NPSN, kepala sekolah, logo, dan branding</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right emis-feature-arrow"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- ========================================================
     TAB 2: PANDUAN FLOW & STATISTIK
     ======================================================== -->
<div id="tabContentStat" style="display: none;">
    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-app p-3">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box mb-0">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted smaller fw-semibold">{{ $isAdmin ? 'TOTAL MURID AKTIF' : 'MURID BINAAN AKTIF' }}</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalStudents }}</div>
                        <div class="text-muted smaller">{{ $isAdmin ? 'Seluruh madrasah/sekolah' : 'Rombel binaan Anda' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-app p-3">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box mb-0">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div>
                        <div class="text-muted smaller fw-semibold">{{ $isAdmin ? 'TOTAL ROMBEL / KELAS' : 'ROMBEL BINAAN' }}</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalClassrooms }}</div>
                        <div class="text-muted smaller">{{ $isAdmin ? 'Tahun ajaran aktif' : 'Kelas yang Anda ampu' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-app p-3">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box mb-0">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="text-muted smaller fw-semibold">TOTAL GURU BK</div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalCounselors }}</div>
                        <div class="text-muted smaller">Pendidik konseling aktif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-app p-3">
                <div class="d-flex align-items-center">
                    <div class="emis-feature-icon-box mb-0">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-muted smaller fw-semibold">SEMESTER AKTIF</div>
                        <div class="fs-4 fw-bold text-dark">{{ $activeSemester ? $activeSemester->name : 'Nonaktif' }}</div>
                        <div class="text-muted smaller">{{ $activeYear ? 'Tahun ' . $activeYear->name : 'Belum diatur' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML/CSS Charts -->
    <div class="row g-4 mb-4">
        <!-- Chart: Murid per Rombel -->
        <div class="col-lg-7">
            <div class="card card-app h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-slate-800">
                        <i class="bi bi-bar-chart-line me-1 text-teal"></i>
                        {{ $isAdmin ? 'Jumlah Murid per Rombel Belajar' : 'Jumlah Murid per Rombel Binaan' }}
                    </h6>
                    <span class="badge bg-light text-muted border">HTML / CSS Bar</span>
                </div>
                <div class="card-body p-4">
                    @if($classroomsData->isNotEmpty())
                        @php
                            $maxStudents = $classroomsData->max('students_count') ?: 1;
                        @endphp
                        <div class="chart-bar-container">
                            @foreach($classroomsData as $cr)
                                @php
                                    $percent = round(($cr->students_count / $maxStudents) * 100);
                                @endphp
                                <div class="chart-bar-item">
                                    <span class="chart-bar-label" title="{{ $cr->name }}">{{ $cr->name }}</span>
                                    <div class="chart-bar-track">
                                        <div class="chart-bar-fill" style="width: {{ $percent }}%;"></div>
                                    </div>
                                    <span class="chart-bar-value">{{ $cr->students_count }} <small class="text-muted fw-normal">anak</small></span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state title="Belum Ada Rombel" description="Data rombel belum tersedia atau belum ditugaskan." />
                    @endif
                </div>
            </div>
        </div>

        <!-- Chart: Komposisi Gender -->
        <div class="col-lg-5">
            <div class="card card-app h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-slate-800">
                        <i class="bi bi-pie-chart me-1 text-teal"></i> Komposisi Jenis Kelamin Siswa
                    </h6>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    @if($maleCount > 0 || $femaleCount > 0)
                        <div class="progress mb-4" style="height: 22px; border-radius: 999px;">
                            <div class="progress-bar bg-teal fw-bold" role="progressbar" style="width: {{ $malePercentage }}%; background-color: #00B894;" title="Laki-laki: {{ $malePercentage }}%">
                                L: {{ $malePercentage }}%
                            </div>
                            <div class="progress-bar bg-danger fw-bold" role="progressbar" style="width: {{ $femalePercentage }}%; background-color: #ef4444;" title="Perempuan: {{ $femalePercentage }}%">
                                P: {{ $femalePercentage }}%
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <i class="bi bi-gender-male text-teal fs-3 mb-1"></i>
                                    <div class="fs-4 fw-bold text-dark">{{ $maleCount }}</div>
                                    <div class="small text-muted">Laki-laki ({{ $malePercentage }}%)</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <i class="bi bi-gender-female text-danger fs-3 mb-1"></i>
                                    <div class="fs-4 fw-bold text-dark">{{ $femaleCount }}</div>
                                    <div class="small text-muted">Perempuan ({{ $femalePercentage }}%)</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <x-empty-state title="Belum Ada Data Murid" description="Komposisi jenis kelamin akan otomatis dikalkulasi." />
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Table: Penugasan Guru BK Terbaru -->
    <div class="card card-app mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-slate-800">
                <i class="bi bi-diagram-3 me-1 text-teal"></i>
                {{ $isAdmin ? 'Penugasan Guru BK Terbaru' : 'Penugasan Anda pada Semester Ini' }}
            </h6>
            @if($isAdmin)
                <a href="{{ route('panel.penugasan.index') }}" class="btn btn-sm btn-link text-teal text-decoration-none">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Guru BK</th>
                        <th>Rombel / Kelas</th>
                        <th>Tingkat</th>
                        <th>Semester</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAssignments as $assignment)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $assignment->counselor->photo_url }}" alt="{{ $assignment->counselor->name }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold text-slate-800">{{ $assignment->counselor->full_name_with_title }}</div>
                                        <div class="text-muted smaller">NIP: {{ $assignment->counselor->nip_nuptk ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-semibold px-2.5 py-1.5">
                                    {{ $assignment->classroom->name }}
                                </span>
                            </td>
                            <td>Kelas {{ $assignment->classroom->level }}</td>
                            <td>{{ $assignment->semester->full_name }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada penugasan untuk ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showDashboardTab(tab) {
        const tabMenu = document.getElementById('tabContentMenu');
        const tabStat = document.getElementById('tabContentStat');
        const btnMenu = document.getElementById('btnTabMenu');
        const btnStat = document.getElementById('btnTabStat');

        if (tab === 'menu') {
            tabMenu.style.display = 'block';
            tabStat.style.display = 'none';
            btnMenu.classList.add('btn-emis-pill-active');
            btnMenu.classList.remove('btn-emis-pill-outline');
            btnStat.classList.add('btn-emis-pill-outline');
            btnStat.classList.remove('btn-emis-pill-active');
        } else {
            tabMenu.style.display = 'none';
            tabStat.style.display = 'block';
            btnStat.classList.add('btn-emis-pill-active');
            btnStat.classList.remove('btn-emis-pill-outline');
            btnMenu.classList.add('btn-emis-pill-outline');
            btnMenu.classList.remove('btn-emis-pill-active');
        }
    }
</script>
@endpush
