<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Wali Kelas') - {{ app_setting('app_name', 'Ruang Ceritaku') }}</title>
    <link rel="icon" href="{{ app_logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --primary-bg: #f0fdfa;
            --gold: #d97706;
            --gold-light: #fef3c7;
            --gold-dark: #b45309;
            --sidebar-w: 240px;
            --topbar-h: 60px;
            --font-main: 'Nunito', 'Inter', system-ui, sans-serif;
            --text-dark: #0f172a;
            --text-mid: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: var(--font-main);
            background-color: var(--bg-page);
            color: var(--text-dark);
            margin: 0; padding: 0;
            font-size: .88rem;
        }

        /* TOPBAR */
        .sk-topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: var(--topbar-h); background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 16px 0 12px; z-index: 1035;
        }
        .sk-burger {
            background: none; border: none; padding: 6px; cursor: pointer;
            border-radius: 6px; color: var(--text-mid); display: flex; align-items: center; justify-content: center;
        }
        .sk-burger:hover { background: var(--bg-page); color: var(--gold); }
        .sk-brand { display: flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; }
        .sk-brand-name { font-size: .98rem; font-weight: 800; color: var(--primary-dark); line-height: 1.1; }
        .sk-brand-sub { font-size: .67rem; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: .04em; }

        .sk-period-badge {
            background: var(--gold-light); border: 1px solid #fde68a;
            border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 700; color: var(--gold-dark);
            display: flex; align-items: center; gap: 6px;
        }
        .sk-user-info { display: flex; align-items: center; gap: 10px; }
        .sk-user-text { text-align: right; }
        .sk-user-name { font-size: .84rem; font-weight: 700; color: var(--text-dark); line-height: 1.2; }
        .sk-user-role { font-size: .7rem; font-weight: 700; color: var(--gold); }
        .sk-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-light); cursor: pointer; }

        /* SIDEBAR */
        .sk-sidebar {
            position: fixed; top: var(--topbar-h); left: 0; bottom: 0;
            width: var(--sidebar-w); background: #ffffff;
            border-right: 1px solid var(--border-color);
            z-index: 1030; display: flex; flex-direction: column;
            overflow-y: auto; overflow-x: hidden;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sk-sidebar-head { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); padding: 12px 16px; flex-shrink: 0; color: #fff; }
        .sk-sidebar-head .yr-label { font-size: .68rem; font-weight: 800; color: rgba(255,255,255,.8); text-transform: uppercase; letter-spacing: .06em; }
        .sk-sidebar-head .yr-val { font-size: .95rem; font-weight: 800; margin-top: 1px; }

        .sk-nav-section { padding: 10px 10px 0; }
        .sk-nav-heading {
            font-size: .63rem !important; font-weight: 800 !important; text-transform: uppercase !important;
            letter-spacing: .08em !important; color: var(--text-muted) !important; padding: 10px 10px 4px !important; margin: 0 !important;
        }
        .sk-nav-item {
            display: flex !important; align-items: center !important; gap: 10px !important;
            padding: 8px 12px !important; border-radius: 8px !important;
            font-size: .84rem !important; font-weight: 600 !important; color: var(--text-mid) !important;
            text-decoration: none !important; transition: all .18s ease !important; margin-bottom: 2px !important;
        }
        .sk-nav-item i { font-size: 1.15rem !important; flex-shrink: 0 !important; color: var(--text-muted) !important; }
        .sk-nav-item:hover { background: var(--gold-light) !important; color: var(--gold-dark) !important; }
        .sk-nav-item:hover i { color: var(--gold) !important; }
        .sk-nav-item.active {
            background: linear-gradient(90deg, #fef3c7 0%, #fffbeb 100%) !important;
            color: var(--gold-dark) !important; font-weight: 700 !important;
            border-left: 3px solid var(--gold) !important; padding-left: 9px !important;
        }
        .sk-nav-item.active i { color: var(--gold) !important; }

        /* LAYOUT & MAIN */
        .sk-main { margin-left: var(--sidebar-w); min-width: 0; padding: 18px 22px 40px; margin-top: var(--topbar-h); }
        .sk-breadbar {
            background: #ffffff; border: 1px solid var(--border-color); border-radius: 8px;
            padding: 8px 16px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,.03);
        }

        .card-app {
            background: #ffffff; border: 1px solid var(--border-color); border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }

        /* OVERLAY (MOBILE) */
        .sk-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 1025; backdrop-filter: blur(2px); }
        .sk-overlay.show { display: block; }

        @media (max-width: 991.98px) {
            .sk-sidebar { transform: translateX(-100%); }
            .sk-sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,.15); }
            .sk-main { margin-left: 0 !important; padding: 14px 12px 60px !important; }
            .sk-period-badge, .sk-user-text { display: none !important; }
        }
        @media (min-width: 992px) { .sk-burger { display: none !important; } }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $currentUser = auth()->user();
        $teacher = $currentUser?->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;
        $activeYear = active_academic_year();
        $activeSem = active_semester();
        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($currentUser?->name ?? 'Wali Kelas') . '&background=d97706&color=ffffff&bold=true';
    @endphp

    <!-- TOPBAR -->
    <header class="sk-topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sk-burger" id="sidebarToggle" type="button" aria-label="Buka Menu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a href="{{ route('walikelas.dashboard') }}" class="sk-brand">
                <img src="{{ app_logo() }}" alt="Logo" style="height: 38px; width: auto; max-width: 48px; object-fit: contain;">
                <div>
                    <div class="sk-brand-name">{{ app_setting('app_name', 'Ruang Ceritaku') }}</div>
                    <div class="sk-brand-sub">{{ $classroom ? 'Wali Kelas ' . $classroom->name : 'Portal Wali Kelas' }}</div>
                </div>
            </a>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="sk-period-badge d-none d-md-flex">
                <i class="bi bi-calendar3"></i>
                <span>{{ $activeYear?->name ?? '2025/2026' }}</span>
                <span>&bull;</span>
                <span>Semester {{ $activeSem?->name ?? 'Ganjil' }}</span>
            </div>

            <div class="sk-user-info">
                <div class="sk-user-text">
                    <div class="sk-user-name">{{ Str::words($currentUser?->name, 3, '') }}</div>
                    <div class="sk-user-role">{{ $classroom ? 'Rombel ' . $classroom->name : 'Wali Kelas' }}</div>
                </div>
                <div class="dropdown">
                    <img src="{{ $avatarUrl }}" alt="{{ $currentUser?->name }}" class="sk-avatar" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2 mt-2" style="min-width: 220px; border: 1px solid var(--border-color) !important;">
                        <li class="px-3 py-2 bg-light border-bottom">
                            <div class="fw-bold text-dark" style="font-size: .84rem;">{{ $currentUser?->name }}</div>
                            <div class="text-muted" style="font-size: .72rem;">{{ $currentUser?->username }} &bull; Wali Kelas</div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('walikelas.profile.index') }}" style="font-size: .83rem;">
                                <i class="bi bi-person-gear me-2 text-primary"></i> Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger" style="font-size: .83rem;">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside class="sk-sidebar" id="sidebar">
        <div class="sk-sidebar-head">
            <div class="yr-label">Rombel Binaan Aktif</div>
            <div class="yr-val">{{ $classroom ? 'Kelas ' . $classroom->name : 'Belum Ditugaskan' }}</div>
            <div class="small opacity-75 mt-1 fs-xs">
                {{ app_setting('school_name', 'Madrasah / Sekolah') }}
            </div>
        </div>

        <nav class="sk-nav-section">
            <div class="sk-nav-heading">MENU UTAMA</div>
            <a href="{{ route('walikelas.dashboard') }}" class="sk-nav-item {{ request()->routeIs('walikelas.dashboard*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Beranda & Siswa</span>
            </a>

            <div class="sk-nav-heading">LAYANAN & ADUAN</div>
            <a href="{{ route('walikelas.aduan.index') }}" class="sk-nav-item {{ request()->routeIs('walikelas.aduan.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span>Aduan Kasus ke BK</span>
            </a>

            <div class="sk-nav-heading">AKUN & SISTEM</div>
            <form action="{{ route('logout') }}" method="POST" class="px-1 mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center gap-1.5">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </nav>
    </aside>

    <div class="sk-overlay" id="sidebarOverlay"></div>

    <!-- MAIN CONTENT -->
    <main class="sk-main">
        <!-- Breadcrumb & Flash Notification -->
        <div class="sk-breadbar">
            <div>
                <span class="fw-bold text-dark fs-6">@yield('page_title', 'Portal Wali Kelas')</span>
                <span class="text-muted fs-xs ms-2">@yield('page_subtitle', '')</span>
            </div>
            <div>
                <a href="{{ route('landing') }}" class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-house me-1"></i> Ruang Ceritaku
                </a>
            </div>
        </div>

        <x-alert />

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
