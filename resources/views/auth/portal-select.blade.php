@extends('layouts.guest')

@section('title', 'Pilih Pintu Masuk - ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4 px-3" style="background: radial-gradient(circle at 50% 10%, rgba(204, 251, 241, 0.5) 0%, rgba(240, 253, 250, 0.75) 40%, rgba(241, 245, 249, 1) 100%);">
    
    <!-- Tombol Kembali ke Beranda -->
    <div class="mb-3 text-start" style="max-width: 480px; width: 100%;">
        <a href="{{ route('landing') }}" class="text-decoration-none text-muted small fw-bold d-inline-flex align-items-center gap-1.5 hover-teal">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda Utama
        </a>
    </div>

    <div class="card border-0 shadow-lg overflow-hidden position-relative" style="max-width: 480px; width: 100%; border-radius: 1.5rem; border: 1px solid var(--border-color) !important; background: #ffffff;">
        <!-- Top Gradient Band -->
        <div style="height: 6px; background: linear-gradient(90deg, #0d9488 0%, #3b82f6 50%, #f59e0b 100%);"></div>

        <div class="card-body p-4 p-sm-4.5">
            <!-- App Logo & Title -->
            <div class="text-center mb-4">
                <div class="p-2.5 rounded-4 d-inline-flex align-items-center justify-content-center shadow-xs mb-2" style="background: #ffffff; border: 1.5px solid #ccfbf1;">
                    <x-app-logo height="68" />
                </div>
                <h4 class="fw-extrabold mb-1" style="color: var(--primary-dark); letter-spacing: -0.02em; font-size: 1.35rem;">
                    {{ app_setting('app_name', 'Ruang Ceritaku') }}
                </h4>
                <p class="text-muted small mb-0">{{ app_setting('school_name', 'Layanan Bimbingan & Konseling Terpadu') }}</p>
                <div class="mt-2.5">
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem;">
                        <i class="bi bi-door-open me-1"></i> Silakan Pilih Jalur Masuk Anda
                    </span>
                </div>
            </div>

            <!-- 3 Pintu Masuk Aplikasi (Mobile-Friendly Touch Cards) -->
            <div class="d-flex flex-column gap-3 mb-4">
                
                <!-- Jalur 1: Murid / Siswa -->
                <a href="{{ route('login.murid') }}" class="portal-entry-card d-flex align-items-center gap-3 p-3.5 rounded-4 text-decoration-none border shadow-xs transition-all card-portal-murid">
                    <div class="portal-icon-wrapper rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: #ffffff; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);">
                        <i class="bi bi-person-heart fs-4"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                            <span class="fw-extrabold text-dark" style="font-size: 1rem;">Masuk sebagai Murid</span>
                            <span class="badge rounded-pill px-2 py-0.5 fs-xxs fw-bold" style="background: #ccfbf1; color: #0f766e;">Siswa</span>
                        </div>
                        <div class="text-muted fs-xs text-truncate-2" style="line-height: 1.4;">
                            Akses konseling pribadi, pengaduan aman, dan asesmen mandiri dengan NISN.
                        </div>
                    </div>
                    <div class="portal-arrow text-muted opacity-50 ps-1">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </div>
                </a>

                <!-- Jalur 2: Wali Kelas -->
                <a href="{{ route('login.wali-kelas') }}" class="portal-entry-card d-flex align-items-center gap-3 p-3.5 rounded-4 text-decoration-none border shadow-xs transition-all card-portal-walikelas">
                    <div class="portal-icon-wrapper rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: #ffffff; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                            <span class="fw-extrabold text-dark" style="font-size: 1rem;">Masuk sebagai Wali Kelas</span>
                            <span class="badge rounded-pill px-2 py-0.5 fs-xxs fw-bold" style="background: #fef3c7; color: #92400e;">Pendidik</span>
                        </div>
                        <div class="text-muted fs-xs text-truncate-2" style="line-height: 1.4;">
                            Pantau catatan perwalian, laporan aduan kelas, dan koordinasi Guru BK.
                        </div>
                    </div>
                    <div class="portal-arrow text-muted opacity-50 ps-1">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </div>
                </a>

                <!-- Jalur 3: Guru BK / Admin -->
                <a href="{{ route('login.admin') }}" class="portal-entry-card d-flex align-items-center gap-3 p-3.5 rounded-4 text-decoration-none border shadow-xs transition-all card-portal-admin">
                    <div class="portal-icon-wrapper rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: #ffffff; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between mb-0.5">
                            <span class="fw-extrabold text-dark" style="font-size: 1rem;">Masuk sebagai Guru BK / Admin</span>
                            <span class="badge rounded-pill px-2 py-0.5 fs-xxs fw-bold" style="background: #dbeafe; color: #1e40af;">Konselor</span>
                        </div>
                        <div class="text-muted fs-xs text-truncate-2" style="line-height: 1.4;">
                            Panel tindak lanjut konseling, instrumen asesmen, dan tata kelola sekolah.
                        </div>
                    </div>
                    <div class="portal-arrow text-muted opacity-50 ps-1">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </div>
                </a>

            </div>

            <!-- Registrasi Murid Baru & Info Footer -->
            <div class="pt-3 border-top text-center space-y-2">
                <div class="fs-xs text-muted">
                    Murid baru dan belum memiliki akun terdaftar?
                </div>
                <a href="{{ route('register.murid') }}" class="btn btn-outline-teal btn-sm rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-xs w-100" style="border: 1.5px solid var(--primary); color: var(--primary);">
                    <i class="bi bi-person-plus-fill"></i> Daftar Akun Murid Baru
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    .portal-entry-card {
        background: #ffffff;
        border-color: #e2e8f0 !important;
        transition: all 0.22s ease-in-out;
        cursor: pointer;
    }
    .portal-entry-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .card-portal-murid:hover {
        border-color: #0d9488 !important;
        background: #f0fdfa !important;
    }
    .card-portal-murid:hover .portal-arrow {
        color: #0d9488 !important;
        opacity: 1 !important;
        transform: translateX(3px);
    }
    .card-portal-walikelas:hover {
        border-color: #d97706 !important;
        background: #fffbeb !important;
    }
    .card-portal-walikelas:hover .portal-arrow {
        color: #d97706 !important;
        opacity: 1 !important;
        transform: translateX(3px);
    }
    .card-portal-admin:hover {
        border-color: #2563eb !important;
        background: #eff6ff !important;
    }
    .card-portal-admin:hover .portal-arrow {
        color: #2563eb !important;
        opacity: 1 !important;
        transform: translateX(3px);
    }
    .portal-arrow {
        transition: all 0.2s ease;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hover-teal:hover {
        color: var(--primary) !important;
    }
</style>
@endsection
