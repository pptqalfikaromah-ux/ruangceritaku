@extends('layouts.guest')

@section('title', 'Masuk ke ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
@php
    $currentRole = request('role', $selectedRole ?? 'murid');
    if (!in_array($currentRole, ['murid', 'bk', 'wali_kelas'])) {
        $currentRole = 'murid';
    }
@endphp

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-3 py-sm-4 px-3" style="background: radial-gradient(circle at 50% 10%, rgba(204, 251, 241, 0.45) 0%, rgba(240, 253, 250, 0.7) 40%, rgba(241, 245, 249, 1) 100%);">
    
    <!-- Tombol Kembali ke Landing Page -->
    <div class="mb-2.5 text-start" style="max-width: 460px; width: 100%;">
        <a href="{{ route('landing') }}" class="text-decoration-none text-muted small fw-bold d-inline-flex align-items-center gap-1.5 hover-teal">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
        </a>
    </div>

    <div class="card card-app border-0 shadow-lg overflow-hidden position-relative" style="max-width: 460px; width: 100%; border-radius: 1.25rem; border: 1px solid var(--border-color) !important; background: #ffffff;">
        <!-- Top Gradient Band -->
        <div style="height: 5px; background: linear-gradient(90deg, #0d9488 0%, #10b981 50%, #f59e0b 100%);"></div>

        <div class="card-body p-4 p-sm-4.5">
            <!-- App Logo & Title -->
            <div class="text-center mb-3.5">
                <a href="{{ route('landing') }}" class="text-decoration-none d-inline-flex flex-column align-items-center mb-1.5">
                    <div class="p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-xs mb-1" style="background: #ffffff; border: 1.5px solid #ccfbf1;">
                        <x-app-logo height="64" />
                    </div>
                </a>
                <h4 class="fw-extrabold mb-1" style="color: var(--primary-dark); letter-spacing: 0.01em; font-size: 1.28rem;">
                    {{ app_setting('app_name', 'Ruang Ceritaku') }}
                </h4>
                <p class="text-muted small mb-0 fs-xs" style="line-height: 1.45; letter-spacing: 0.015em;">{{ app_setting('school_name', 'Layanan Bimbingan & Konseling Terpadu') }}</p>
            </div>

            <!-- Role Selector Tabs (Mobile Touch Friendly & Spacious) -->
            <div class="role-selector-wrap p-2 rounded-pill bg-light border d-flex mb-3.5" style="gap: 6px;">
                <button type="button" 
                        class="btn flex-fill rounded-pill py-2.5 px-3 fw-bold role-tab-btn {{ $currentRole === 'murid' ? 'active' : '' }}" 
                        onclick="setRoleTab('murid')">
                    <i class="bi bi-person-heart me-1"></i> Murid
                </button>
                <button type="button" 
                        class="btn flex-fill rounded-pill py-2.5 px-3 fw-bold role-tab-btn {{ $currentRole === 'bk' ? 'active' : '' }}" 
                        onclick="setRoleTab('bk')">
                    <i class="bi bi-shield-check me-1"></i> Guru BK
                </button>
                <button type="button" 
                        class="btn flex-fill rounded-pill py-2.5 px-3 fw-bold role-tab-btn {{ $currentRole === 'wali_kelas' ? 'active' : '' }}" 
                        onclick="setRoleTab('wali_kelas')">
                    <i class="bi bi-mortarboard-fill me-1"></i> Wali Kelas
                </button>
            </div>

            <!-- Active Portal Indicator Box -->
            <div class="role-info-banner p-3.5 rounded-4 mb-3.5 border d-flex align-items-center gap-3" id="roleBanner" style="background: #f0fdfa; border-color: #ccfbf1 !important;">
                <div class="role-banner-icon d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 44px; height: 44px; background: #ffffff; color: var(--primary);">
                    <i class="bi bi-person-heart fs-5" id="bannerIcon"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" id="bannerTitle" style="font-size: 0.92rem; letter-spacing: 0.015em;">Portal Masuk Siswa</div>
                    <div class="text-muted" style="font-size: 0.8rem; line-height: 1.5;" id="bannerDesc">Silakan masuk menggunakan NISN resmi Anda.</div>
                </div>
            </div>

            <!-- Alerts -->
            <x-alert />

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="formLogin">
                @csrf
                <input type="hidden" name="active_portal" id="inputActivePortal" value="{{ $currentRole }}">

                <div class="mb-3.5">
                    <label for="login" class="form-label fw-bold small text-slate-700 mb-1.5" id="labelLogin">
                        Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3.5 pe-2.5" style="padding-top: 12px; padding-bottom: 12px;">
                            <i class="bi bi-person text-mint fs-5" id="inputIcon"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 ps-2 pe-3.5 rounded-end-pill @error('login') is-invalid @enderror"
                               id="login"
                               name="login"
                               value="{{ old('login') }}"
                               placeholder="Masukkan username"
                               style="padding-top: 12px; padding-bottom: 12px; min-height: 52px; font-size: 0.96rem; letter-spacing: 0.02em;"
                               required
                               autofocus>
                    </div>
                    <div class="form-text text-muted fs-xs mt-1.5 ps-1" id="loginHelpText" style="line-height: 1.5;">
                        <i class="bi bi-info-circle me-1"></i>Masuk dengan username terdaftar (atau NISN bagi siswa).
                    </div>
                    @error('login')
                        <div class="text-danger small mt-1.5 ps-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <label for="password" class="form-label fw-bold small text-slate-700 mb-0">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3.5 pe-2.5" style="padding-top: 12px; padding-bottom: 12px;">
                            <i class="bi bi-shield-lock text-mint fs-5"></i>
                        </span>
                        <input type="password"
                               class="form-control border-start-0 border-end-0 ps-2 @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password Anda"
                               style="padding-top: 12px; padding-bottom: 12px; min-height: 52px; font-size: 0.96rem; letter-spacing: 0.02em;"
                               required>
                        <button class="btn btn-light border border-start-0 rounded-end-pill pe-3.5 text-muted" type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()" style="padding-top: 12px; padding-bottom: 12px;">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <div class="form-text text-muted fs-xs mt-1.5 ps-1" id="passwordHelpText" style="line-height: 1.5;">
                        Password bawaan akun sama dengan username masing-masing.
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1.5 ps-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4 ps-1">
                    <div class="form-check d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input mt-0 cursor-pointer" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="width: 17px; height: 17px;">
                        <label class="form-check-label small text-muted cursor-pointer user-select-none" for="remember">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <div class="d-grid mb-3.5">
                    <button type="submit" class="btn btn-mint py-3 rounded-pill fw-bold shadow-sm" id="btnSubmitLogin" style="font-size: 0.98rem; letter-spacing: 0.03em;">
                        <i class="bi bi-box-arrow-in-right me-1.5"></i> MASUK SEKARANG
                    </button>
                </div>
            </form>

            <!-- Student Registration Link Box (Khusus Murid) -->
            <div class="p-3 rounded-3 mb-3 border text-center" id="registerMuridContainer" style="background: #f0fdfa; border-color: #ccfbf1 !important;">
                <span class="text-muted small">Belum memiliki akun siswa?</span>
                <div class="mt-1.5">
                    <a href="{{ route('register.murid') }}" class="btn btn-sm btn-outline-success rounded-pill px-3.5 py-1.5 fw-bold" style="font-size: 0.82rem; letter-spacing: 0.015em;">
                        <i class="bi bi-person-plus-fill me-1"></i> Daftar Akun
                    </a>
                </div>
            </div>

            <div class="text-center mt-3 pt-3 border-top border-light">
                <div class="text-muted small mb-2.5">Pilih Pintu Masuk Peran Lainnya:</div>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('login.murid') }}" class="btn btn-light btn-sm border rounded-pill px-3 py-1.5 text-muted fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-person-heart text-mint me-1"></i> Murid
                    </a>
                    <a href="{{ route('login.admin') }}" class="btn btn-light btn-sm border rounded-pill px-3 py-1.5 text-muted fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-shield-check text-primary me-1"></i> Admin / BK
                    </a>
                    <a href="{{ route('login.wali-kelas') }}" class="btn btn-light btn-sm border rounded-pill px-3 py-1.5 text-muted fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-mortarboard-fill text-warning me-1"></i> Wali Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="text-center text-muted small mt-4" style="letter-spacing: 0.015em;">
        &copy; {{ date('Y') }} {{ app_setting('app_name', 'Ruang Ceritaku') }} &bull; {{ app_setting('school_name', 'Sekolah') }}
    </div>
</div>

<style>
    .role-tab-btn {
        color: var(--text-mid);
        border: none;
        transition: all 0.2s ease;
        font-size: 0.84rem;
        letter-spacing: 0.015em;
    }
    .role-tab-btn:hover {
        color: var(--primary);
    }
    .role-tab-btn.active {
        background: #ffffff !important;
        color: var(--primary-dark) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .hover-teal:hover {
        color: var(--primary) !important;
    }
</style>

<script>
    const roleConfigs = {
        'murid': {
            routePath: '{{ route("login.murid") }}',
            bannerTitle: 'Portal Masuk Siswa',
            bannerDesc: 'Masuk dengan username atau NISN Anda.',
            bannerBg: '#f0fdfa',
            bannerBorder: '#ccfbf1',
            iconClass: 'bi-person-heart text-mint',
            labelLogin: 'Username / NISN',
            placeholder: 'Masukkan username atau NISN',
            helpLogin: '<i class="bi bi-info-circle me-1"></i>Masukkan username atau nomor induk siswa Anda yang terdaftar.',
            helpPassword: 'Gunakan password akun siswa Anda.',
            submitText: '<i class="bi bi-box-arrow-in-right me-1.5"></i> MASUK KE RUANG CERITAKU',
            btnClass: 'btn-mint'
        },
        'bk': {
            routePath: '{{ route("login.admin") }}',
            bannerTitle: 'Portal Masuk Guru BK & Admin',
            bannerDesc: 'Masuk dengan akun konselor / administrator sekolah.',
            bannerBg: '#eff6ff',
            bannerBorder: '#bfdbfe',
            iconClass: 'bi-shield-check text-primary',
            labelLogin: 'Username Admin / BK',
            placeholder: 'Masukkan username konselor / admin',
            helpLogin: '<i class="bi bi-info-circle me-1"></i>Masukkan kredensial akun konselor atau administrator.',
            helpPassword: 'Demi keamanan, jangan bagikan password akun konselor kepada siapa pun.',
            submitText: '<i class="bi bi-box-arrow-in-right me-1.5"></i> MASUK SEBAGAI ADMIN / BK',
            btnClass: 'btn-primary'
        },
        'wali_kelas': {
            routePath: '{{ route("login.wali-kelas") }}',
            bannerTitle: 'Portal Masuk Wali Kelas & Pendidik',
            bannerDesc: 'Masuk untuk pemantauan perkembangan rombel binaan Anda.',
            bannerBg: '#fefce8',
            bannerBorder: '#fef08a',
            iconClass: 'bi-mortarboard-fill text-warning-emphasis',
            labelLogin: 'Username Wali Kelas',
            placeholder: 'Masukkan username wali kelas',
            helpLogin: '<i class="bi bi-info-circle me-1"></i>Masukkan username pendidik / wali kelas terdaftar.',
            helpPassword: 'Gunakan password akun pendidik yang telah diberikan pihak sekolah.',
            submitText: '<i class="bi bi-box-arrow-in-right me-1.5"></i> MASUK SEBAGAI WALI KELAS',
            btnClass: 'btn-gold'
        }
    };

    function setRoleTab(role) {
        if (!roleConfigs[role]) role = 'murid';

        // Update active tab buttons
        document.querySelectorAll('.role-tab-btn').forEach(btn => btn.classList.remove('active'));
        const activeBtn = Array.from(document.querySelectorAll('.role-tab-btn')).find(b => b.getAttribute('onclick').includes(role));
        if (activeBtn) activeBtn.classList.add('active');

        // Update hidden field
        const inputPortal = document.getElementById('inputActivePortal');
        if (inputPortal) inputPortal.value = role;

        // Update UI content
        const conf = roleConfigs[role];
        const banner = document.getElementById('roleBanner');
        const bannerTitle = document.getElementById('bannerTitle');
        const bannerDesc = document.getElementById('bannerDesc');
        const bannerIcon = document.getElementById('bannerIcon');
        const labelLogin = document.getElementById('labelLogin');
        const inputLogin = document.getElementById('login');
        const helpLogin = document.getElementById('loginHelpText');
        const helpPassword = document.getElementById('passwordHelpText');
        const btnSubmit = document.getElementById('btnSubmitLogin');

        if (banner) {
            banner.style.background = conf.bannerBg;
            banner.style.borderColor = conf.bannerBorder;
        }
        if (bannerTitle) bannerTitle.textContent = conf.bannerTitle;
        if (bannerDesc) bannerDesc.textContent = conf.bannerDesc;
        if (bannerIcon) bannerIcon.className = 'bi ' + conf.iconClass + ' fs-5';
        if (labelLogin) labelLogin.textContent = conf.labelLogin;
        if (inputLogin) inputLogin.setAttribute('placeholder', conf.placeholder);
        if (helpLogin) helpLogin.innerHTML = conf.helpLogin;
        if (helpPassword) helpPassword.innerHTML = conf.helpPassword;
        if (btnSubmit) {
            btnSubmit.innerHTML = conf.submitText;
            btnSubmit.className = 'btn ' + conf.btnClass + ' py-2.5 rounded-pill fw-bold shadow-sm';
        }

        const regBox = document.getElementById('registerMuridContainer');
        if (regBox) {
            regBox.style.display = (role === 'murid') ? 'block' : 'none';
        }

        // Sync browser URL smoothly to corresponding dedicated login route
        if (window.history.replaceState && conf.routePath) {
            window.history.replaceState({}, '', conf.routePath);
        }
    }

    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (!passInput || !icon) return;

        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Initialize state on page load
    document.addEventListener('DOMContentLoaded', function () {
        const initialRole = '{{ $currentRole }}';
        setRoleTab(initialRole);
    });
</script>
@endsection
