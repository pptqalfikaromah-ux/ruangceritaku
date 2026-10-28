<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ruang Ceritaku - Layanan Bimbingan & Konseling Sekolah Terpadu</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ app_logo() }}">

    <!-- Google Fonts Nunito & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --primary-bg: #f0fdfa;
            --gold: #f59e0b;
            --gold-light: #fef3c7;
            --font-main: 'Nunito', 'Inter', system-ui, sans-serif;
            --text-dark: #1e293b;
            --text-mid: #475569;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-main);
            color: var(--text-dark);
            background-color: var(--bg-page);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-size: 15px;
            line-height: 1.6;
        }

        /* NAVBAR */
        .landing-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1050;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
            height: 70px;
        }

        .brand-icon-box {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.35);
            color: #ffffff;
            font-size: 1.25rem;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* HERO SECTION */
        .hero-section {
            padding-top: 130px;
            padding-bottom: 80px;
            position: relative;
            background: radial-gradient(circle at 80% 20%, rgba(204, 251, 241, 0.5) 0%, rgba(240, 253, 250, 0.7) 40%, rgba(248, 250, 252, 1) 100%);
            overflow: hidden;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--primary-light);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.08);
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: clamp(2.1rem, 4.5vw, 3.4rem);
            font-weight: 900;
            color: var(--text-dark);
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin-bottom: 18px;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: clamp(1rem, 1.8vw, 1.15rem);
            color: var(--text-mid);
            max-width: 620px;
            margin-bottom: 30px;
        }

        /* PORTAL SELECTION CARDS */
        .portal-card {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 18px;
            padding: 28px 24px;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .portal-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(13, 148, 136, 0.12);
        }

        .portal-card.card-murid:hover { border-color: var(--primary); }
        .portal-card.card-bk:hover { border-color: var(--primary-dark); }
        .portal-card.card-walikelas:hover { border-color: var(--gold); }

        .portal-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 20px;
            transition: transform 0.25s ease;
        }

        .portal-card:hover .portal-icon-wrap {
            transform: scale(1.08);
        }

        .icon-murid { background: var(--primary-bg); color: var(--primary); border: 1.5px solid var(--primary-light); }
        .icon-bk { background: #e0f2fe; color: #0284c7; border: 1.5px solid #bae6fd; }
        .icon-walikelas { background: var(--gold-light); color: #b45309; border: 1.5px solid #fde68a; }

        .portal-badge {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 3px 10px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* BUTTONS */
        .btn-mint {
            background: var(--primary);
            border: none;
            color: #ffffff;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-mint:hover {
            background: var(--primary-dark);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
        }

        .btn-gold {
            background: var(--gold);
            border: none;
            color: #1e293b;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-gold:hover {
            background: #d97706;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
        }

        .btn-outline-mint {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            background: transparent;
            font-weight: 700;
        }
        .btn-outline-mint:hover {
            background: var(--primary);
            color: #ffffff;
        }

        /* HERO ROLE BUTTONS (3 TOMBOL LOGIN) */
        .hero-login-box {
            background: #ffffff;
            border: 1.5px solid #ccfbf1 !important;
            box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.1), 0 8px 10px -6px rgba(13, 148, 136, 0.05) !important;
        }

        .btn-role-action {
            border: 1.5px solid transparent;
            transition: all 0.22s ease-in-out;
            cursor: pointer;
        }

        .btn-role-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.08);
        }

        .btn-role-murid {
            background: #f0fdfa;
            border-color: #99f6e4;
            color: #0f766e;
        }
        .btn-role-murid:hover {
            background: #0d9488;
            border-color: #0d9488;
            color: #ffffff !important;
        }
        .btn-role-murid:hover .role-action-icon {
            background: #ffffff;
            color: #0d9488;
        }
        .btn-role-murid:hover .role-action-title,
        .btn-role-murid:hover .role-action-desc {
            color: #ffffff !important;
        }

        .btn-role-admin {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }
        .btn-role-admin:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff !important;
        }
        .btn-role-admin:hover .role-action-icon {
            background: #ffffff;
            color: #2563eb;
        }
        .btn-role-admin:hover .role-action-title,
        .btn-role-admin:hover .role-action-desc {
            color: #ffffff !important;
        }

        .btn-role-walikelas {
            background: #fefce8;
            border-color: #fef08a;
            color: #a16207;
        }
        .btn-role-walikelas:hover {
            background: #d97706;
            border-color: #d97706;
            color: #ffffff !important;
        }
        .btn-role-walikelas:hover .role-action-icon {
            background: #ffffff;
            color: #d97706;
        }
        .btn-role-walikelas:hover .role-action-title,
        .btn-role-walikelas:hover .role-action-desc {
            color: #ffffff !important;
        }

        .role-action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .role-action-title {
            font-size: 0.92rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .role-action-desc {
            font-size: 0.72rem;
            font-weight: 600;
            opacity: 0.85;
        }

        /* FEATURE BOXES */
        .feature-box {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.2s ease;
            height: 100%;
        }

        .feature-box:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 24px rgba(13, 148, 136, 0.08);
            transform: translateY(-3px);
        }

        .feature-icon-sm {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 16px;
        }

        /* STEP CARDS */
        .step-num {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--primary);
            color: #ffffff;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 14px;
        }

        /* MOBILE FLOATING BAR */
        .mobile-quick-bar {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid var(--border-color);
            padding: 8px 12px;
            z-index: 1040;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.08);
            transform: translateY(105%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mobile-quick-bar.show {
            transform: translateY(0);
        }

        @media (max-width: 991.98px) {
            .hero-section { padding-top: 85px; padding-bottom: 40px; }
            .mobile-quick-bar { display: flex; gap: 8px; }
            .landing-nav { height: 62px; }
            .brand-title { font-size: 1.05rem; }
            .brand-subtitle { font-size: 0.65rem; }
        }
        @media (max-width: 575.98px) {
            .hero-section { padding-top: 75px !important; padding-bottom: 25px !important; }
            .hero-title { font-size: 1.6rem !important; line-height: 1.22 !important; margin-bottom: 12px !important; }
            .hero-desc { font-size: 0.86rem !important; line-height: 1.48 !important; margin-bottom: 18px !important; }
            .hero-badge { font-size: 0.72rem !important; padding: 4px 12px !important; margin-bottom: 12px !important; }
            .role-action-icon { width: 38px !important; height: 38px !important; font-size: 1.15rem !important; border-radius: 10px !important; }
            .role-action-title { font-size: 0.76rem !important; font-weight: 800 !important; }
            .role-action-desc { display: none !important; }
            .hero-login-box { padding: 12px !important; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="landing-nav d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('landing') }}" class="d-flex align-items-center gap-2.5 text-decoration-none">
                <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 44px; width: auto; max-width: 50px; object-fit: contain;">
                <div>
                    <div class="brand-title">{{ app_setting('app_name', 'Ruang Ceritaku') }}</div>
                    <div class="brand-subtitle">{{ app_setting('school_name', 'Layanan Bimbingan & Konseling Sekolah') }}</div>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="d-none d-lg-flex align-items-center gap-4">
                <a href="#beranda" class="text-decoration-none text-dark fw-bold small">Beranda</a>
                <a href="#pilih-portal" class="text-decoration-none text-muted fw-bold small">Pilihan Portal</a>
                <a href="#fitur" class="text-decoration-none text-muted fw-bold small">Layanan BK</a>
                <a href="#alur" class="text-decoration-none text-muted fw-bold small">Alur Konseling</a>
            </nav>

            <!-- Action Button -->
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->role === 'murid' ? route('murid.dashboard') : route('panel.dashboard') }}" class="btn btn-mint btn-sm rounded-pill px-3 py-2 fw-bold">
                        <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard
                    </a>
                @else
                    <div class="dropdown">
                        <button class="btn btn-mint rounded-pill px-3.5 py-2 fw-bold dropdown-toggle d-flex align-items-center gap-1.5 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk Login
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 mt-2" style="min-width: 210px; border: 1px solid var(--border-color) !important;">
                            <li><h6 class="dropdown-header text-uppercase text-muted fw-extrabold fs-xs">Pilih Akses Masuk</h6></li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('login.murid') }}">
                                    <div class="p-1 rounded bg-teal-subtle text-mint"><i class="bi bi-person-heart"></i></div>
                                    <div>
                                        <div class="fw-bold small text-dark">Portal Murid</div>
                                        <div class="text-muted fs-xs">Konseling & Masalah Siswa</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('login.admin') }}">
                                    <div class="p-1 rounded bg-primary-subtle text-primary"><i class="bi bi-shield-check"></i></div>
                                    <div>
                                        <div class="fw-bold small text-dark">Admin / Guru BK</div>
                                        <div class="text-muted fs-xs">Konselor & Manajemen</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2" href="{{ route('login.wali-kelas') }}">
                                    <div class="p-1 rounded bg-warning-subtle text-warning-emphasis"><i class="bi bi-mortarboard-fill"></i></div>
                                    <div>
                                        <div class="fw-bold small text-dark">Wali Kelas</div>
                                        <div class="text-muted fs-xs">Pendidik & Monitoring Rombel</div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-2 py-2 d-flex align-items-center gap-2 text-mint fw-bold" href="{{ route('register.murid') }}">
                                    <div class="p-1 rounded bg-teal-subtle text-mint"><i class="bi bi-person-plus-fill"></i></div>
                                    <div>
                                        <div class="small">Daftar Akun Siswa Baru</div>
                                        <div class="text-muted fs-xs fw-normal">Registrasi mandiri khusus murid</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section" id="beranda">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 d-flex align-items-center" role="alert" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0 !important;">
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                    <div class="flex-grow-1 fw-bold">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div class="flex-grow-1 fw-bold">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="hero-badge">
                        <i class="bi bi-sparkles text-warning"></i>
                        <span>Sahabat Konseling & Ruang Aman Siswa</span>
                    </div>
                    
                    <h1 class="hero-title">
                        Tempat Aman Berbagi Cerita, Tumbuh Nyaman & <span>Penuh Percaya Diri</span>
                    </h1>

                    <p class="hero-desc mx-auto mx-lg-0">
                        Aplikasi resmi layanan <strong>Bimbingan dan Konseling</strong> sekolah untuk pengisian instrumen masalah siswa (IMS 180), konsultasi privat rahasia, pencegahan perundungan, dan pendampingan tumbuh kembang peserta didik.
                    </p>

                    <!-- 3 TOMBOL LOGIN UTAMA (SESUAI PERMINTAAN USER) -->
                    <div class="hero-login-box p-3 p-sm-4 rounded-4 bg-white border shadow-sm mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <span class="small fw-extrabold text-dark text-uppercase tracking-wide">
                                <i class="bi bi-box-arrow-in-right text-mint me-1.5"></i> Pilih Akses Masuk Login
                            </span>
                            <span class="badge rounded-pill bg-teal-subtle text-mint px-2.5 py-1 small fw-bold">3 Pilihan Peran</span>
                        </div>

                        <div class="row g-2">
                            <!-- Tombol 1: Login Murid -->
                            <div class="col-4">
                                <a href="{{ route('login.murid') }}" class="btn-role-action btn-role-murid w-100 d-flex flex-column align-items-center justify-content-center text-center p-2 p-sm-3 rounded-3 text-decoration-none h-100">
                                    <div class="role-action-icon mb-1.5">
                                        <i class="bi bi-person-heart"></i>
                                    </div>
                                    <div class="role-action-title">Murid</div>
                                    <div class="role-action-desc d-none d-md-block">Akses Siswa & Konseling</div>
                                </a>
                            </div>

                            <!-- Tombol 2: Login Admin / Guru BK -->
                            <div class="col-4">
                                <a href="{{ route('login.admin') }}" class="btn-role-action btn-role-admin w-100 d-flex flex-column align-items-center justify-content-center text-center p-2 p-sm-3 rounded-3 text-decoration-none h-100">
                                    <div class="role-action-icon mb-1.5">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="role-action-title">Admin / BK</div>
                                    <div class="role-action-desc d-none d-md-block">Konselor & Manajemen</div>
                                </a>
                            </div>

                            <!-- Tombol 3: Login Wali Kelas -->
                            <div class="col-4">
                                <a href="{{ route('login.wali-kelas') }}" class="btn-role-action btn-role-walikelas w-100 d-flex flex-column align-items-center justify-content-center text-center p-2 p-sm-3 rounded-3 text-decoration-none h-100">
                                    <div class="role-action-icon mb-1.5">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div class="role-action-title">Wali Kelas</div>
                                    <div class="role-action-desc d-none d-md-block">Pendidik & Monitoring</div>
                                </a>
                            </div>
                        </div>

                        <!-- Registration Prompt for Students -->
                        <div class="mt-3 pt-2.5 text-center border-top border-light-subtle d-flex flex-wrap align-items-center justify-content-center gap-1.5">
                            <span class="text-muted small">Murid baru belum memiliki akun?</span>
                            <a href="{{ route('register.murid') }}" class="small fw-bold text-mint text-decoration-none hover-teal">
                                <i class="bi bi-person-plus-fill me-0.5"></i> Daftar Akun Siswa Baru
                            </a>
                        </div>
                    </div>

                    <!-- Trust indicators -->
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3 pt-2 text-muted small fw-semibold">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-shield-check text-mint fs-5"></i>
                            <span>100% Rahasia & Aman</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-clipboard2-check text-mint fs-5"></i>
                            <span>Instrumen 180 Masalah</span>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-heart-pulse text-danger fs-5"></i>
                            <span>Konselor Responsif</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="position-relative">
                        <!-- Decorative glow -->
                        <div style="position: absolute; width: 280px; height: 280px; background: rgba(13, 148, 136, 0.15); border-radius: 50%; filter: blur(40px); top: 10%; right: 10%; z-index: 0;"></div>
                        
                        <!-- Floating Hero Card -->
                        <div class="card border-0 shadow-lg p-4 p-md-4 rounded-4 position-relative z-1" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid var(--border-color) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 48px; width: auto; max-width: 55px; object-fit: contain;">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ app_setting('app_name', 'Ruang Ceritaku') }} Online</h6>
                                    <span class="badge bg-teal-subtle text-mint fw-bold">{{ app_setting('school_name', 'Layanan Konseling Sekolah') }}</span>
                                </div>
                            </div>

                            <p class="small text-muted mb-3">
                                "Tidak ada masalah yang terlalu sepele atau terlalu rumit untuk diceritakan. Kamu berhak merasa aman, nyaman, dan didampingi."
                            </p>

                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small fw-bold text-dark"><i class="bi bi-clock-history me-1 text-mint"></i> Layanan Konseling</span>
                                    <span class="badge rounded-pill bg-success text-white small">Aktif</span>
                                </div>
                                <div class="small text-muted">Bimbingan Pribadi, Sosial, Belajar & Masa Depan.</div>
                            </div>

                            <a href="#pilih-portal" class="btn btn-mint w-100 py-2.5 rounded-pill fw-bold">
                                Buka Pintu Masuk Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: PILIH PORTAL MASUK (PINTU MASUK UTAMA) -->
    <section class="py-5" id="pilih-portal" style="background: #ffffff; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2" style="background: var(--primary-bg); color: var(--primary-dark); border: 1px solid var(--primary-light);">
                    <i class="bi bi-door-open me-1"></i> Pintu Masuk Portal
                </span>
                <h2 class="fw-extrabold text-dark" style="font-size: clamp(1.8rem, 3vw, 2.4rem); letter-spacing: -0.02em;">
                    Pilih Portal Akses Sesuai Peran Anda
                </h2>
                <p class="text-muted">
                    Masuk ke ruang kerja atau ruang cerita Anda dengan satu sentuhan mudah.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- 1. PORTAL MURID -->
                <div class="col-md-6 col-lg-4">
                    <div class="portal-card card-murid">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="portal-icon-wrap icon-murid">
                                <i class="bi bi-person-heart"></i>
                            </div>
                            <span class="portal-badge" style="background: var(--primary-bg); color: var(--primary-dark); border: 1px solid var(--primary-light);">
                                Siswa / Murid
                            </span>
                        </div>

                        <h4 class="fw-extrabold text-dark mb-1">Portal Siswa</h4>
                        <div class="small fw-semibold text-mint mb-2.5">Ruang Curahan Hati & Konseli</div>
                        
                        <p class="text-muted small mb-4 flex-grow-1">
                            Akses pengisian Instrumen Masalah (IMS 180 butir), ajukan jadwal bimbingan konseling privat, dan kirim pengaduan aman terlindungi.
                        </p>

                        <div class="p-2.5 rounded-3 mb-3 bg-light border small text-muted">
                            <i class="bi bi-key me-1 text-mint"></i> Masuk menggunakan <strong>NISN</strong> siswa
                        </div>

                        <a href="{{ route('login.murid') }}" class="btn btn-mint w-100 py-2.5 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1.5"></i> Masuk Portal Siswa
                        </a>
                    </div>
                </div>

                <!-- 2. PORTAL GURU BK / ADMIN -->
                <div class="col-md-6 col-lg-4">
                    <div class="portal-card card-bk">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="portal-icon-wrap icon-bk">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <span class="portal-badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                Guru BK & Admin
                            </span>
                        </div>

                        <h4 class="fw-extrabold text-dark mb-1">Portal Guru BK / Admin</h4>
                        <div class="small fw-semibold text-primary mb-2.5">Konselor & Manajemen Sekolah</div>
                        
                        <p class="text-muted small mb-4 flex-grow-1">
                            Kelola pemetaan masalah siswa, agenda sesi konseling, tindak lanjut pengaduan, program RPL BK, dan rekapitulasi laporan resmi.
                        </p>

                        <div class="p-2.5 rounded-3 mb-3 bg-light border small text-muted">
                            <i class="bi bi-person-badge me-1 text-primary"></i> Masuk dengan <strong>Username / Email</strong>
                        </div>

                        <a href="{{ route('login.admin') }}" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1.5"></i> Masuk Portal Guru BK / Admin
                        </a>
                    </div>
                </div>

                <!-- 3. PORTAL WALI KELAS -->
                <div class="col-md-6 col-lg-4">
                    <div class="portal-card card-walikelas">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="portal-icon-wrap icon-walikelas">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <span class="portal-badge" style="background: var(--gold-light); color: #92400e; border: 1px solid #fde68a;">
                                Wali Kelas / Guru
                            </span>
                        </div>

                        <h4 class="fw-extrabold text-dark mb-1">Portal Wali Kelas</h4>
                        <div class="small fw-semibold text-warning-emphasis mb-2.5">Pemantauan & Kolaborasi Kelas</div>
                        
                        <p class="text-muted small mb-4 flex-grow-1">
                            Kolaborasi bimbingan perkembangan siswa di rombel binaan Anda, informasi pendampingan, serta sinergi bersama konselor sekolah.
                        </p>

                        <div class="p-2.5 rounded-3 mb-3 bg-light border small text-muted">
                            <i class="bi bi-person-workspace me-1 text-warning"></i> Masuk dengan <strong>Akun Pendidik</strong>
                        </div>

                        <a href="{{ route('login.wali-kelas') }}" class="btn btn-gold w-100 py-2.5 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1.5"></i> Masuk Portal Wali Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: FITUR & LAYANAN UNGGULAN -->
    <section class="py-5" id="fitur">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2" style="background: var(--primary-bg); color: var(--primary-dark); border: 1px solid var(--primary-light);">
                    <i class="bi bi-stars me-1"></i> Layanan Unggulan
                </span>
                <h2 class="fw-extrabold text-dark" style="font-size: clamp(1.8rem, 3vw, 2.3rem); letter-spacing: -0.02em;">
                    Fitur Lengkap untuk Kenyamanan Konseling
                </h2>
                <p class="text-muted">
                    Dirancang dengan pendekatan ramah anak, rahasia, dan terintegrasi dengan kebutuhan sekolah modern.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon-sm bg-teal-subtle text-mint">
                            <i class="bi bi-clipboard2-check-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Asesmen IMS (180 Butir)</h5>
                        <p class="text-muted small mb-0">
                            Pemetaan komprehensif 180 butir masalah mencakup aspek pribadi, sosial, belajar, dan karir secara otomatis.
                        </p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon-sm" style="background: #fff1f2; color: #e11d48;">
                            <i class="bi bi-chat-heart-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Konseling Privat Aman</h5>
                        <p class="text-muted small mb-0">
                            Pengajuan jadwal konseling mudah, langsung tersambung ke Guru BK pembina rombel tanpa rasa canggung.
                        </p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon-sm" style="background: #fef2f2; color: #ef4444;">
                            <i class="bi bi-shield-shaded"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Pengaduan & Anti-Bullying</h5>
                        <p class="text-muted small mb-0">
                            Kanal perlindungan siswa untuk melaporkan insiden perundungan atau kekerasan dengan jaminan kerahasiaan.
                        </p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon-sm" style="background: var(--gold-light); color: #d97706;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Program RPL & Laporan</h5>
                        <p class="text-muted small mb-0">
                            Perumusan Rencana Pelaksanaan Layanan BK (RPL) dan ekspor laporan rekapitulasi data terstandar Kementerian.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: ALUR MUDAH -->
    <section class="py-5" id="alur" style="background: #ffffff; border-top: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge rounded-pill px-3 py-1.5 fw-bold mb-2" style="background: var(--primary-bg); color: var(--primary-dark); border: 1px solid var(--primary-light);">
                    <i class="bi bi-bezier2 me-1"></i> Praktis & Sederhana
                </span>
                <h2 class="fw-extrabold text-dark" style="font-size: clamp(1.8rem, 3vw, 2.3rem); letter-spacing: -0.02em;">
                    3 Langkah Mudah Menggunakan Ruang Ceritaku
                </h2>
            </div>

            <div class="row g-4 text-center text-md-start">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="step-num mx-auto mx-md-0">1</div>
                        <h5 class="fw-bold text-dark mb-2">Pilih Pintu Masuk</h5>
                        <p class="text-muted small mb-0">
                            Tentukan portal yang sesuai: Murid, Guru BK / Konselor, atau Wali Kelas.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="step-num mx-auto mx-md-0">2</div>
                        <h5 class="fw-bold text-dark mb-2">Masukkan Identitas Akun</h5>
                        <p class="text-muted small mb-0">
                            Siswa masuk menggunakan NISN resmi. Pendidik dan Admin menggunakan username yang telah terdaftar.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="step-num mx-auto mx-md-0">3</div>
                        <h5 class="fw-bold text-dark mb-2">Bercerita & Berdiskusi</h5>
                        <p class="text-muted small mb-0">
                            Mulai isi instrumen IMS, ajukan bimbingan, atau kelola sesi konseling secara nyaman dan bermartabat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CALL TO ACTION BANNER -->
    <section class="py-5">
        <div class="container py-3">
            <div class="p-4 p-md-5 rounded-4 text-white position-relative overflow-hidden shadow-lg" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
                <!-- Deco circle -->
                <div style="position: absolute; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; top: -100px; right: -50px;"></div>
                
                <div class="row align-items-center position-relative z-1 g-4">
                    <div class="col-lg-8 text-center text-lg-start">
                        <h3 class="fw-extrabold mb-2" style="font-size: clamp(1.6rem, 2.8vw, 2.2rem);">
                            Ada yang Mengganjal di Pikiran atau Hatimu?
                        </h3>
                        <p class="mb-0 text-white opacity-90" style="font-size: 1.05rem;">
                            Jangan disimpan sendirian. Masuk ke Ruang Ceritaku dan temukan pendampingan terbaik dari Guru BK sekolahmu.
                        </p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="#pilih-portal" class="btn btn-gold btn-lg px-4 py-3 rounded-pill fw-bold shadow">
                            <i class="bi bi-box-arrow-in-right me-1.5"></i> Masuk Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-top py-4 text-center text-md-start">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                        <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 32px; width: auto; max-width: 40px; object-fit: contain;">
                        <span class="fw-bold text-dark">{{ app_setting('app_name', 'Ruang Ceritaku') }}</span>
                    </div>
                    <div class="text-muted small">
                        Layanan Bimbingan & Konseling Sekolah Terpadu &bull; {{ app_setting('school_name', 'Madrasah / Sekolah') }}
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    &copy; {{ date('Y') }} {{ app_setting('app_name', 'Ruang Ceritaku') }}. Seluruh hak cipta dilindungi.
                </div>
            </div>
        </div>
    </footer>

    <!-- MOBILE QUICK ACCESS BOTTOM BAR -->
    <div class="mobile-quick-bar">
        <a href="{{ route('login.murid') }}" class="btn btn-mint btn-sm flex-grow-1 rounded-pill py-2 fw-bold text-truncate">
            <i class="bi bi-person-heart me-1"></i> Murid
        </a>
        <a href="{{ route('login.admin') }}" class="btn btn-outline-primary btn-sm flex-grow-1 rounded-pill py-2 fw-bold text-truncate">
            <i class="bi bi-shield-check me-1"></i> Admin / BK
        </a>
        <a href="{{ route('login.wali-kelas') }}" class="btn btn-gold btn-sm flex-grow-1 rounded-pill py-2 fw-bold text-truncate">
            <i class="bi bi-mortarboard-fill me-1"></i> Wali Kelas
        </a>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Script Kontrol Floating Mobile Bar (Hanya Muncul Saat Scroll Lewat Hero) -->
    <script>
        (function() {
            var bar = document.querySelector('.mobile-quick-bar');
            var heroBox = document.querySelector('.hero-login-box');
            if (bar && heroBox) {
                function checkBarVisibility() {
                    var rect = heroBox.getBoundingClientRect();
                    if (rect.bottom < 80) {
                        bar.classList.add('show');
                    } else {
                        bar.classList.remove('show');
                    }
                }
                window.addEventListener('scroll', checkBarVisibility, { passive: true });
                window.addEventListener('resize', checkBarVisibility);
                checkBarVisibility();
            }
        })();
    </script>
</body>
</html>
