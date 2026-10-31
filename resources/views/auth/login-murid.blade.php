@extends('layouts.guest')

@section('title', 'Masuk Portal Murid - ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
<div class="auth-page-wrapper min-vh-100 d-flex flex-column justify-content-center align-items-center py-3 py-sm-4 px-2.5 px-sm-3">
    
    <!-- Tombol Navigasi Kembali ke Pemilihan Pintu Masuk -->
    <div class="auth-top-nav mb-2.5 text-start w-100" style="max-width: 460px;">
        <a href="{{ route('login') }}" class="auth-back-btn text-decoration-none d-inline-flex align-items-center gap-1.5">
            <i class="bi bi-arrow-left"></i>
            <span>Pilih Pintu Masuk Lain</span>
        </a>
    </div>

    <div class="card auth-card border-0 shadow-lg position-relative overflow-hidden w-100" style="max-width: 460px;">
        <!-- Top Gradient Band Khusus Murid (Teal/Emerald) -->
        <div class="auth-top-bar" style="background: linear-gradient(90deg, #0d9488 0%, #10b981 100%);"></div>

        <div class="card-body p-4 p-sm-4.5">
            <!-- App Logo & Title -->
            <div class="text-center mb-3.5">
                <a href="{{ route('landing') }}" class="text-decoration-none d-inline-flex flex-column align-items-center mb-1.5">
                    <div class="auth-logo-box p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-xs mb-1">
                        <x-app-logo height="58" />
                    </div>
                </a>
                <h4 class="auth-app-title fw-extrabold mb-1">
                    {{ app_setting('app_name', 'Ruang Ceritaku') }}
                </h4>
                <p class="auth-app-sub text-muted small mb-0 fs-xs" style="line-height: 1.45; letter-spacing: 0.015em;">{{ app_setting('school_name', 'Layanan Bimbingan & Konseling Terpadu') }}</p>
            </div>

            <!-- Role Header Banner (Khusus Murid) -->
            <div class="auth-role-banner p-3 px-3.5 rounded-4 mb-3.5 border d-flex align-items-center gap-3">
                <div class="auth-role-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 text-white">
                    <i class="bi bi-person-heart fs-5"></i>
                </div>
                <div class="min-w-0">
                    <div class="fw-bold text-dark fs-sm" style="line-height: 1.3; letter-spacing: 0.01em;">Portal Masuk Siswa</div>
                    <div class="text-muted fs-xs mt-0.5" style="line-height: 1.4;">Silakan masuk menggunakan NISN resmi Anda.</div>
                </div>
            </div>

            <!-- Alerts -->
            <x-alert />

            <!-- Login Form Murid -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="active_portal" value="murid">

                <!-- Input NISN -->
                <div class="mb-3.5">
                    <label for="login" class="form-label fw-bold small text-slate-700 mb-1.5">
                        NISN / Nomor Induk Siswa Nasional
                    </label>
                    <div class="auth-field-group @error('login') has-error @enderror">
                        <span class="auth-field-icon">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <input type="text"
                               class="auth-field-input"
                               id="login"
                               name="login"
                               value="{{ old('login') }}"
                               placeholder="Contoh: 0081234567"
                               required
                               autofocus>
                    </div>
                    <div class="form-text text-muted fs-xs mt-1.5 ps-1" style="line-height: 1.45;">
                        <i class="bi bi-info-circle me-1"></i>Masukkan NISN 10 digit terdaftar di sekolah.
                    </div>
                    @error('login')
                        <div class="text-danger small mt-1.5 ps-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="mb-3.5">
                    <label for="password" class="form-label fw-bold small text-slate-700 mb-1.5">
                        Kata Sandi (Password)
                    </label>
                    <div class="auth-field-group @error('password') has-error @enderror">
                        <span class="auth-field-icon">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password"
                               class="auth-field-input"
                               id="password"
                               name="password"
                               placeholder="Masukkan kata sandi"
                               required>
                        <button class="auth-field-toggle"
                                type="button"
                                id="togglePassword"
                                onclick="togglePasswordVisibility()"
                                title="Lihat/Sembunyikan Kata Sandi">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1.5 ps-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-4 ps-1">
                    <div class="form-check d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input mt-0 cursor-pointer" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="cursor: pointer; width: 16px; height: 16px;">
                        <label class="form-check-label fs-xs text-muted mb-0 user-select-none cursor-pointer" for="remember" style="cursor: pointer;">
                            Ingat Saya di perangkat ini
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-portal-submit btn-portal-murid w-100 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span>Masuk ke Akun Murid</span>
                </button>
            </form>

            <!-- Tautan Pendaftaran Murid Baru -->
            <div class="mt-4 pt-3.5 border-top text-center">
                <span class="fs-xs text-muted">Belum punya akun murid?</span>
                <a href="{{ route('register.murid') }}" class="fs-xs fw-bold text-teal text-decoration-none ms-1 link-register">
                    Daftar Akun
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }
</script>

<style>
    /* PAGE WRAPPER */
    .auth-page-wrapper {
        background: radial-gradient(circle at 50% 8%, rgba(204, 251, 241, 0.55) 0%, rgba(240, 253, 250, 0.75) 35%, #f1f5f9 100%);
        min-height: 100vh;
    }

    /* TOP BACK BUTTON */
    .auth-back-btn {
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
        padding: 7px 16px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        border: 1px solid #e2e8f0;
        border-radius: 50rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        letter-spacing: 0.015em;
    }
    .auth-back-btn:hover {
        color: #0f766e;
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(-2px);
    }

    /* CARD STYLING */
    .auth-card {
        border-radius: 1.25rem !important;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
        background: #ffffff;
        box-shadow: 0 12px 32px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04) !important;
    }
    .auth-top-bar {
        height: 5px;
        width: 100%;
    }
    .auth-logo-box {
        background: #ffffff;
        border: 1.5px solid #ccfbf1;
    }
    .auth-app-title {
        color: var(--primary-dark, #0f766e);
        letter-spacing: 0.01em;
        font-size: 1.28rem;
    }

    /* ROLE BANNER */
    .auth-role-banner {
        background: #f0fdfa;
        border-color: #ccfbf1 !important;
    }
    .auth-role-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0d9488, #14b8a6);
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.25);
    }

    /* CUSTOM INPUT GROUP (BREATHING ROOM, PREVENT CLIPPING) */
    .auth-field-group {
        display: flex;
        align-items: center;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 50rem;
        padding: 8px 18px;
        min-height: 54px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .auth-field-group:focus-within {
        background-color: #ffffff;
        border-color: #0d9488;
        box-shadow: 0 0 0 3.5px rgba(13, 148, 136, 0.16);
    }
    .auth-field-group.has-error {
        border-color: #ef4444 !important;
        background-color: #fffaf0;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    }
    .auth-field-icon {
        color: #0d9488;
        font-size: 1.3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 12px;
    }
    .auth-field-input {
        flex-grow: 1;
        min-width: 0;
        border: none !important;
        outline: none !important;
        background: transparent !important;
        box-shadow: none !important;
        font-size: 0.98rem;
        color: #1e293b;
        padding: 8px 10px;
        font-family: inherit;
        letter-spacing: 0.025em;
        line-height: 1.55;
    }
    .auth-field-input::placeholder {
        color: #94a3b8;
        font-size: 0.92rem;
    }
    .auth-field-toggle {
        background: transparent;
        border: none;
        color: #94a3b8;
        padding: 8px 12px;
        font-size: 1.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50rem;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .auth-field-toggle:hover {
        color: #334155;
        background: rgba(148, 163, 184, 0.12);
    }

    /* SUBMIT BUTTON */
    .btn-portal-submit {
        height: 54px;
        font-size: 0.98rem;
        letter-spacing: 0.03em;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
    }
    .btn-portal-murid {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.3);
    }
    .btn-portal-murid:hover {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(13, 148, 136, 0.38);
    }
    .btn-portal-murid:active {
        transform: translateY(0);
    }

    /* LINK */
    .text-teal {
        color: #0d9488 !important;
    }
    .link-register:hover {
        color: #0f766e !important;
        text-decoration: underline !important;
    }
</style>
@endsection
