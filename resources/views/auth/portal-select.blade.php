@extends('layouts.guest')

@section('title', 'Pilih Pintu Masuk - ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
<div class="portal-wrapper min-vh-100 d-flex flex-column justify-content-center align-items-center py-4 px-3">
    
    <!-- Top Navigation / Back to Home -->
    <div class="portal-container mb-3 text-start w-100">
        <a href="{{ route('landing') }}" class="portal-back-link text-decoration-none d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Halaman Utama</span>
        </a>
    </div>

    <!-- Main Card Box -->
    <div class="portal-card-box card border-0 shadow-lg position-relative overflow-hidden w-100">
        <!-- Top Vibrant Accent Strip -->
        <div class="portal-top-bar"></div>

        <div class="card-body p-4 p-sm-4.5">
            <!-- App Brand & Header -->
            <div class="text-center mb-4">
                <div class="portal-logo-wrap d-inline-flex align-items-center justify-content-center mb-2.5">
                    <x-app-logo height="62" />
                </div>
                <h4 class="portal-app-title fw-extrabold mb-1">
                    {{ app_setting('app_name', 'Ruang Ceritaku') }}
                </h4>
                <div class="portal-school-subtitle text-muted">
                    {{ app_setting('school_name', 'Layanan Bimbingan & Konseling Sekolah') }}
                </div>
                <div class="mt-2.5">
                    <span class="portal-hint-badge d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-door-open-fill text-mint"></i>
                        <span>Pilih Pintu Masuk Aplikasi</span>
                    </span>
                </div>
            </div>

            <!-- 3 Pintu Masuk Utama (Touch-Friendly, Bernafas, dan Lega) -->
            <div class="portal-grid d-flex flex-column gap-3 mb-4">
                
                <!-- 1. Murid / Siswa -->
                <a href="{{ route('login.murid') }}" class="portal-card-item card-murid text-decoration-none">
                    <div class="portal-card-icon icon-murid">
                        <i class="bi bi-person-heart"></i>
                    </div>
                    <div class="portal-card-info flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                            <span class="portal-role-title">Portal Murid</span>
                            <span class="portal-role-tag tag-murid">Siswa</span>
                        </div>
                        <div class="portal-role-desc">
                            Konseling pribadi, curhat & aduan siswa
                        </div>
                    </div>
                    <div class="portal-card-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </a>

                <!-- 2. Wali Kelas -->
                <a href="{{ route('login.wali-kelas') }}" class="portal-card-item card-walikelas text-decoration-none">
                    <div class="portal-card-icon icon-walikelas">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="portal-card-info flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                            <span class="portal-role-title">Wali Kelas</span>
                            <span class="portal-role-tag tag-walikelas">Pendidik</span>
                        </div>
                        <div class="portal-role-desc">
                            Pantau catatan perwalian & aduan kelas
                        </div>
                    </div>
                    <div class="portal-card-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </a>

                <!-- 3. Guru BK / Admin -->
                <a href="{{ route('login.admin') }}" class="portal-card-item card-admin text-decoration-none">
                    <div class="portal-card-icon icon-admin">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="portal-card-info flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                            <span class="portal-role-title">Guru BK & Admin</span>
                            <span class="portal-role-tag tag-admin">Konselor</span>
                        </div>
                        <div class="portal-role-desc">
                            Panel tindak lanjut konseling & tata kelola
                        </div>
                    </div>
                    <div class="portal-card-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </a>

            </div>

            <!-- Bagian Bawah: Registrasi Murid Baru -->
            <div class="portal-footer pt-3.5 border-top text-center">
                <p class="portal-footer-text mb-2.5">
                    Murid baru dan belum memiliki akun terdaftar?
                </p>
                <a href="{{ route('register.murid') }}" class="btn portal-register-btn w-100 d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-person-plus-fill fs-5"></i>
                    <span>Daftar Akun Murid Baru</span>
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    /* WRAPPER & BACKGROUND */
    .portal-wrapper {
        background: radial-gradient(circle at 50% 8%, rgba(204, 251, 241, 0.6) 0%, rgba(240, 253, 250, 0.8) 35%, #f1f5f9 100%);
        min-height: 100vh;
        letter-spacing: 0.012em;
    }
    .portal-container {
        max-width: 460px;
    }
    .portal-back-link {
        font-size: 0.86rem;
        font-weight: 700;
        color: #475569;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border: 1px solid #cbd5e1;
        border-radius: 50rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        letter-spacing: 0.015em;
    }
    .portal-back-link:hover {
        color: var(--primary, #0d9488);
        background: #ffffff;
        border-color: #0d9488;
        transform: translateX(-2px);
    }

    /* CARD BOX */
    .portal-card-box {
        max-width: 460px;
        background: #ffffff;
        border-radius: 1.35rem !important;
        border: 1px solid rgba(203, 213, 225, 0.8) !important;
        box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.08), 0 4px 14px -2px rgba(15, 23, 42, 0.04) !important;
    }
    .portal-top-bar {
        height: 6px;
        background: linear-gradient(90deg, #0d9488 0%, #3b82f6 50%, #f59e0b 100%);
    }

    /* BRAND & LOGO */
    .portal-logo-wrap {
        padding: 8px;
        border-radius: 18px;
        background: #ffffff;
        border: 1.5px solid #ccfbf1;
        box-shadow: 0 2px 10px rgba(13, 148, 136, 0.08);
    }
    .portal-app-title {
        color: var(--primary-dark, #0f766e);
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.25;
        letter-spacing: 0.015em;
    }
    .portal-school-subtitle {
        font-size: 0.84rem;
        line-height: 1.45;
        letter-spacing: 0.01em;
        color: #64748b;
    }
    .portal-hint-badge {
        font-size: 0.78rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 50rem;
        background: #f0fdfa;
        color: #0f766e;
        border: 1.5px solid #ccfbf1;
        letter-spacing: 0.02em;
    }

    /* PORTAL ENTRY CARDS (TOUCH-FRIENDLY & BERNAFAS) */
    .portal-card-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
    }
    .portal-card-item:hover,
    .portal-card-item:active {
        transform: translateY(-2px);
    }

    /* ICON */
    .portal-card-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        color: #ffffff;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .portal-card-item:hover .portal-card-icon {
        transform: scale(1.05);
    }

    /* TEXT INFO */
    .portal-card-info {
        min-width: 0;
    }
    .portal-role-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 0.02em;
        line-height: 1.35;
    }
    .portal-role-tag {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 50rem;
        line-height: 1.35;
        letter-spacing: 0.025em;
        flex-shrink: 0;
    }
    .portal-role-desc {
        font-size: 0.84rem;
        color: #64748b;
        margin-top: 4px;
        line-height: 1.5;
        letter-spacing: 0.015em;
    }

    /* ARROW */
    .portal-card-arrow {
        color: #94a3b8;
        font-size: 1.25rem;
        flex-shrink: 0;
        width: 22px;
        text-align: right;
        transition: all 0.2s ease;
    }
    .portal-card-item:hover .portal-card-arrow {
        transform: translateX(3px);
    }

    /* SPECIFIC ROLE COLORS */
    /* Murid */
    .icon-murid {
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.25);
    }
    .tag-murid {
        background: #ccfbf1;
        color: #0f766e;
    }
    .card-murid:hover {
        border-color: #0d9488;
        background: #f0fdfa;
        box-shadow: 0 6px 18px rgba(13, 148, 136, 0.12);
    }
    .card-murid:hover .portal-card-arrow { color: #0d9488; }

    /* Wali Kelas */
    .icon-walikelas {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.25);
    }
    .tag-walikelas {
        background: #fef3c7;
        color: #92400e;
    }
    .card-walikelas:hover {
        border-color: #d97706;
        background: #fffbeb;
        box-shadow: 0 6px 18px rgba(217, 119, 6, 0.12);
    }
    .card-walikelas:hover .portal-card-arrow { color: #d97706; }

    /* Admin / BK */
    .icon-admin {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        box-shadow: 0 4px 10px rgba(30, 64, 175, 0.25);
    }
    .tag-admin {
        background: #dbeafe;
        color: #1e40af;
    }
    .card-admin:hover {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.12);
    }
    .card-admin:hover .portal-card-arrow { color: #2563eb; }

    /* FOOTER REGISTER BUTTON */
    .portal-footer-text {
        font-size: 0.82rem;
        color: #64748b;
        letter-spacing: 0.01em;
    }
    .portal-register-btn {
        background: #ffffff;
        border: 1.5px solid var(--primary, #0d9488);
        color: var(--primary, #0d9488);
        border-radius: 50rem;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 14px 22px;
        transition: all 0.2s ease;
        letter-spacing: 0.025em;
    }
    .portal-register-btn:hover {
        background: var(--primary, #0d9488);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
    }
</style>
@endsection
