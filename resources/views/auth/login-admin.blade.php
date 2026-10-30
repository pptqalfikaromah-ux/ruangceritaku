@extends('layouts.guest')

@section('title', 'Masuk Panel Guru BK & Admin - ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-3 py-sm-4 px-2.5 px-sm-3" style="background: radial-gradient(circle at 50% 8%, rgba(219, 234, 254, 0.55) 0%, rgba(239, 246, 255, 0.75) 35%, #f1f5f9 100%);">
    
    <!-- Tombol Kembali ke Pemilihan Pintu Masuk -->
    <div class="mb-2.5 text-start w-100" style="max-width: 440px;">
        <a href="{{ route('login') }}" class="text-decoration-none small fw-bold d-inline-flex align-items-center gap-1.5 hover-blue" style="color: #64748b; font-size: 0.8rem;">
            <i class="bi bi-arrow-left"></i>
            <span>Pilih Pintu Masuk Lain</span>
        </a>
    </div>

    <div class="card border-0 shadow-lg position-relative overflow-hidden w-100" style="max-width: 440px; border-radius: 1.25rem !important; border: 1px solid rgba(226, 232, 240, 0.9) !important; background: #ffffff;">
        <!-- Top Gradient Band Khusus Admin & Guru BK (Blue/Indigo) -->
        <div style="height: 5px; background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);"></div>

        <div class="card-body p-3.5 p-sm-4">
            <!-- App Logo & Title -->
            <div class="text-center mb-3">
                <a href="{{ route('landing') }}" class="text-decoration-none d-inline-flex flex-column align-items-center mb-1">
                    <div class="p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-xs mb-1" style="background: #ffffff; border: 1.5px solid #dbeafe;">
                        <x-app-logo height="56" />
                    </div>
                </a>
                <h4 class="fw-extrabold mb-0" style="color: #1e3a8a; letter-spacing: -0.025em; font-size: 1.22rem;">
                    {{ app_setting('app_name', 'Ruang Ceritaku') }}
                </h4>
                <p class="text-muted small mb-0 fs-xs">{{ app_setting('school_name', 'Layanan Bimbingan & Konseling Terpadu') }}</p>
            </div>

            <!-- Role Header Banner (Khusus Guru BK & Admin) -->
            <div class="p-2.5 px-3 rounded-3 mb-3 border d-flex align-items-center gap-2.5" style="background: #eff6ff; border-color: #bfdbfe !important;">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 text-white" style="width: 38px; height: 38px; background: linear-gradient(135deg, #1e40af, #3b82f6); box-shadow: 0 4px 10px rgba(30, 64, 175, 0.25);">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
                <div class="min-w-0">
                    <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.2;">Portal Guru BK & Admin</div>
                    <div class="text-muted fs-xs mt-0.5">Akses panel bimbingan konseling dan tata kelola madrasah.</div>
                </div>
            </div>

            <!-- Alerts -->
            <x-alert />

            <!-- Login Form Admin/BK -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="active_portal" value="bk">

                <div class="mb-3">
                    <label for="login" class="form-label fw-bold small text-slate-700">
                        Username atau Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                            <i class="bi bi-person text-primary fs-5"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 ps-1 rounded-end-pill @error('login') is-invalid @enderror"
                               id="login"
                               name="login"
                               value="{{ old('login') }}"
                               placeholder="Masukkan username atau email"
                               required
                               autofocus>
                    </div>
                    <div class="form-text text-muted fs-xs mt-1">
                        <i class="bi bi-info-circle me-1"></i>Gunakan username konselor atau administrator Anda.
                    </div>
                    @error('login')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold small text-slate-700">
                        Kata Sandi (Password)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                            <i class="bi bi-key text-primary fs-5"></i>
                        </span>
                        <input type="password"
                               class="form-control border-start-0 border-end-0 ps-1 @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan kata sandi"
                               required>
                        <button class="btn btn-outline-secondary border-start-0 rounded-end-pill pe-3 bg-light text-muted"
                                type="button"
                                id="togglePassword"
                                onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3.5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label fs-xs text-muted" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 text-white" style="background: linear-gradient(135deg, #1e40af, #2563eb); border: none;">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span>Masuk ke Panel BK / Admin</span>
                </button>
            </form>

            <div class="mt-3.5 pt-3 border-top text-center">
                <span class="fs-xs text-muted">Bukan akun Guru BK / Admin?</span>
                <a href="{{ route('login') }}" class="fs-xs fw-bold text-dark text-decoration-none ms-1 hover-underline">
                    Pilih Pintu Masuk Lain
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
    .hover-blue:hover { color: #2563eb !important; }
</style>
@endsection
