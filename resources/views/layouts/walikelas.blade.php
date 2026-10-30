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

        /* MOBILE BOTTOM NAV */
        .sk-bottom-nav {
            display: none; position: fixed; bottom: 0; left: 0; right: 0;
            height: 64px; background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 -4px 18px rgba(15, 23, 42, 0.05); z-index: 1040;
            align-items: center; justify-content: space-around;
            padding: 0 10px;
        }
        .sk-btab {
            flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-decoration: none; color: #64748b;
            font-size: .68rem; font-weight: 700; gap: 4px; height: 100%;
            transition: all .18s ease; position: relative;
        }
        .sk-btab i {
            font-size: 1.35rem; line-height: 1; transition: transform .18s ease, color .18s ease;
        }
        .sk-btab:hover { color: #d97706; }
        .sk-btab.active { color: #d97706; font-weight: 800; }
        .sk-btab.active i { transform: translateY(-2px); color: #d97706; }
        .sk-btab.active::after {
            content: ''; position: absolute; top: 0; width: 32px; height: 3px;
            background: linear-gradient(90deg, #d97706, #b45309); border-radius: 0 0 4px 4px;
        }

        /* UTILITY HELPERS & BREATHING TYPOGRAPHY */
        .fs-xs { font-size: .83rem !important; letter-spacing: 0.01em; }
        .fs-xxs { font-size: .76rem !important; letter-spacing: 0.015em; }
        .fs-sm { font-size: .92rem !important; }
        .fw-black { font-weight: 900 !important; }
        .fw-extrabold { font-weight: 800 !important; }
        .bg-teal-subtle { background-color: #ccfbf1 !important; color: #0f766e !important; }
        .text-mint { color: #0f766e !important; }
        .shadow-xs { box-shadow: 0 1px 3px rgba(15,23,42,0.06) !important; }

        .space-y-1.5 > * + * { margin-top: 0.4rem !important; }
        .space-y-2 > * + * { margin-top: 0.5rem !important; }
        .space-y-2.5 > * + * { margin-top: 0.65rem !important; }
        .space-y-3 > * + * { margin-top: 1rem !important; }
        .space-y-4 > * + * { margin-top: 1.5rem !important; }

        /* RESPONSIVE MOBILE FEED CARDS POLISH (Breathing Room, Clear Hierarchy) */
        .mobile-card {
            border-radius: 1.15rem !important;
            padding: 1.15rem !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            box-shadow: 0 2px 10px rgba(15,23,42,0.04) !important;
            transition: all 0.2s ease;
        }
        .mobile-card:active {
            transform: scale(0.995);
        }
        .mobile-card .card-name {
            font-size: 1.02rem !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            line-height: 1.35 !important;
            letter-spacing: -0.01em;
        }
        .mobile-card .card-sub {
            font-size: 0.83rem !important;
            color: #64748b !important;
            line-height: 1.45 !important;
        }
        .mobile-card .card-info-box {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.85rem !important;
            padding: 0.75rem 0.95rem !important;
            font-size: 0.84rem !important;
            color: #334155 !important;
            line-height: 1.6 !important;
        }
        .mobile-card .btn-card-action {
            min-height: 42px !important;
            font-size: 0.86rem !important;
            font-weight: 700 !important;
            border-radius: 50rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.4rem !important;
            padding: 0.55rem 1.15rem !important;
            letter-spacing: 0.01em !important;
        }
        .mobile-card .btn-card-icon {
            width: 42px !important;
            height: 42px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50rem !important;
            flex-shrink: 0 !important;
        }
        .badge-pill-custom {
            font-size: 0.76rem !important;
            font-weight: 700 !important;
            padding: 0.35rem 0.75rem !important;
            letter-spacing: 0.02em !important;
            border-radius: 50rem !important;
        }

        @media (max-width: 991.98px) {
            .sk-sidebar { transform: translateX(-100%); }
            .sk-sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,.15); }
            .sk-main { margin-left: 0 !important; padding: 12px 14px 84px !important; }
            .sk-bottom-nav { display: flex; }
            .sk-period-badge, .sk-user-text { display: none !important; }
            .sk-breadbar { display: none !important; }
            .sk-brand-name { font-size: .88rem; max-width: 170px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .sk-brand-sub { font-size: .63rem; max-width: 170px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
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
        $avatarUrl = $currentUser?->avatar_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($currentUser?->name ?? 'Wali Kelas') . '&background=d97706&color=ffffff&bold=true');
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
            <a href="{{ route('walikelas.profile.index') }}" class="sk-nav-item {{ request()->routeIs('walikelas.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>Profil Saya</span>
            </a>
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

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="sk-bottom-nav d-lg-none">
        <a href="{{ route('walikelas.dashboard') }}" class="sk-btab {{ request()->routeIs('walikelas.dashboard*') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('walikelas.dashboard*') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('walikelas.aduan.index') }}" class="sk-btab {{ request()->routeIs('walikelas.aduan.*') ? 'active' : '' }}">
            <i class="bi bi-shield-exclamation"></i>
            <span>Aduan BK</span>
        </a>
        <a href="{{ route('walikelas.profile.index') }}" class="sk-btab {{ request()->routeIs('walikelas.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
    </nav>

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
