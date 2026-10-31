<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang Murid') - {{ app_setting('app_name', 'Ruang Ceritaku') }}</title>
    <link rel="icon" href="{{ app_logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --primary-bg: #f0fdfa;
            --gold: #f59e0b;
            --gold-light: #fef3c7;
            --sidebar-w: 230px;
            --topbar-h: 60px;
            --font-main: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --text-dark: #1e293b;
            --text-mid: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --bg-page: #f1f5f9;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            font-family: var(--font-main);
            background: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0; padding: 0;
            overflow-x: hidden;
            font-size: 14px;
            letter-spacing: 0.022em;
            word-spacing: 0.03em;
            line-height: 1.62;
            -webkit-font-smoothing: antialiased;
        }
        /* TOPBAR */
        .sk-topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            z-index: 1040;
            display: flex; align-items: center;
            padding: 0 1rem; gap: 1rem;
        }
        .sk-burger {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            background: transparent; border: none; border-radius: 8px;
            color: var(--text-mid); cursor: pointer; transition: background .2s; flex-shrink: 0;
        }
        .sk-burger:hover { background: var(--primary-light); color: var(--primary); }
        .sk-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        .sk-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(13,148,136,.3);
        }
        .sk-brand-name { font-size: 1.15rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -.01em; line-height: 1; }
        .sk-brand-sub { font-size: .65rem; font-weight: 600; color: var(--text-muted); letter-spacing: .04em; text-transform: uppercase; }
        .sk-period-badge {
            display: flex; align-items: center; gap: 6px;
            background: var(--primary-bg); border: 1px solid var(--primary-light);
            border-radius: 20px; padding: 4px 12px;
            font-size: .72rem; font-weight: 700; color: var(--primary-dark); white-space: nowrap;
        }
        .sk-user-info { margin-left: auto; display: flex; align-items: center; gap: 10px; }
        .sk-user-text { text-align: right; }
        .sk-user-name { font-size: .85rem; font-weight: 700; color: var(--text-dark); line-height: 1.2; }
        .sk-user-role {
            font-size: .7rem; font-weight: 600; color: var(--primary);
            display: inline-block; background: var(--primary-light);
            border-radius: 4px; padding: 1px 6px; margin-top: 2px;
        }
        .sk-avatar {
            width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
            border: 2px solid var(--primary-light); cursor: pointer;
        }
        /* SIDEBAR */
        .sk-sidebar {
            position: fixed; top: var(--topbar-h); left: 0; bottom: 0;
            width: var(--sidebar-w); background: #fff;
            border-right: 1px solid var(--border-color);
            z-index: 1030; display: flex; flex-direction: column;
            overflow-y: auto; overflow-x: hidden;
            scrollbar-width: thin; scrollbar-color: var(--border-color) transparent;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sk-sidebar-head { background: var(--primary); padding: 12px 16px; flex-shrink: 0; }
        .sk-sidebar-head .yr-label { font-size: .7rem; font-weight: 800; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .06em; }
        .sk-sidebar-head .yr-val { font-size: .92rem; font-weight: 700; color: #fff; margin-top: 1px; }
        .sk-sidebar-head .sm-badge {
            display: inline-block; background: rgba(255,255,255,.2); color: #fff;
            border-radius: 12px; padding: 2px 10px; font-size: .7rem; font-weight: 700; margin-top: 6px;
        }
        .sk-nav-section { padding: 6px 8px 0; }
        .sk-nav-heading { font-size: .63rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); padding: 8px 10px 4px; }
        .sk-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: .84rem; font-weight: 600; color: var(--text-mid);
            text-decoration: none; transition: all .18s ease; margin-bottom: 2px;
            position: relative; white-space: nowrap; overflow: hidden;
        }
        .sk-nav-item i { font-size: 1.1rem; flex-shrink: 0; color: var(--text-muted); transition: color .18s; }
        .sk-nav-item:hover { background: var(--primary-bg); color: var(--primary-dark); }
        .sk-nav-item:hover i { color: var(--primary); }
        .sk-nav-item.active {
            background: linear-gradient(90deg, var(--primary-bg) 0%, #e0f7f4 100%);
            color: var(--primary-dark); font-weight: 700;
            border-left: 3px solid var(--primary); padding-left: 9px;
        }
        .sk-nav-item.active i { color: var(--primary); }
        .sk-nav-item.parent-active { color: var(--primary-dark); font-weight: 700; }
        .sk-nav-item.parent-active i { color: var(--primary); }
        .sk-chevron { transition: transform .2s ease; font-size: .75rem !important; }
        .sk-nav-item[aria-expanded="true"] .sk-chevron { transform: rotate(180deg); }
        .sk-submenu-wrapper {
            margin-left: 18px;
            padding-left: 10px;
            border-left: 2px dashed #cbd5e1;
            margin-top: 2px;
            margin-bottom: 4px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sk-subnav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-mid);
            text-decoration: none;
            transition: all .15s ease;
            white-space: nowrap;
        }
        .sk-subnav-item i { font-size: .95rem; color: var(--text-muted); transition: color .15s ease; }
        .sk-subnav-item:hover { background: var(--primary-bg); color: var(--primary-dark); }
        .sk-subnav-item:hover i { color: var(--primary); }
        .sk-subnav-item.active {
            background: linear-gradient(90deg, var(--primary-bg) 0%, #e0f7f4 100%);
            color: var(--primary-dark);
            font-weight: 700;
        }
        .sk-subnav-item.active i { color: var(--primary); }
        .sk-sidebar-footer { margin-top: auto; padding: 12px 8px; border-top: 1px solid var(--border-color); flex-shrink: 0; }
        /* LAYOUT */
        .sk-layout { display: flex; min-height: 100vh; padding-top: var(--topbar-h); }
        .sk-main { margin-left: var(--sidebar-w); flex-grow: 1; min-width: 0; padding: 20px 24px 32px; transition: margin-left .3s ease; }
        /* BREADCRUMB */
        .sk-breadbar {
            background: #fff; border: 1px solid var(--border-color);
            border-radius: 6px; padding: 8px 16px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 6px;
            font-size: .8rem; color: var(--text-muted);
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .sk-breadbar .sk-bpage { font-size: 1rem; font-weight: 800; color: var(--text-dark); display: block; margin-bottom: 2px; }
        .sk-breadbar a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .sk-breadbar a:hover { text-decoration: underline; }
        /* BUTTONS */
        .btn-gold { background: var(--gold); border: none; color: var(--text-dark); font-weight: 700; }
        .btn-gold:hover { background: #d97706; color: var(--text-dark); }
        .btn-mint { background: var(--primary); border: none; color: #fff; font-weight: 700; }
        .btn-mint:hover { background: var(--primary-dark); color: #fff; }
        .btn-emerald { background: var(--primary); border: none; color: #fff; font-weight: 700; }
        .btn-emerald:hover { background: var(--primary-dark); color: #fff; }
        /* OVERLAY */
        .sk-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 1025; backdrop-filter: blur(2px); }
        .sk-overlay.show { display: block; }
        /* MOBILE BOTTOM NAV */
        .sk-bottom-nav {
            display: none; position: fixed; bottom: 0; left: 0; right: 0;
            height: 60px; background: #fff; border-top: 1px solid var(--border-color);
            box-shadow: 0 -2px 12px rgba(0,0,0,.06); z-index: 1040;
            align-items: center; justify-content: space-around;
        }
        .sk-btab {
            flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-decoration: none; color: var(--text-muted);
            font-size: .62rem; font-weight: 700; gap: 2px; height: 100%;
            transition: color .2s; position: relative;
        }
        .sk-btab i { font-size: 1.3rem; transition: transform .2s, color .2s; }
        .sk-btab.active { color: var(--primary); }
        .sk-btab.active i { transform: translateY(-2px); color: var(--primary); }
        .sk-btab.active::after { content: ''; position: absolute; top: 4px; width: 18px; height: 3px; background: var(--gold); border-radius: 3px; }
        /* RESPONSIVE */
        @media (max-width: 991.98px) {
            .sk-sidebar { transform: translateX(-100%); }
            .sk-sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,.15); }
            .sk-main { margin-left: 0 !important; width: 100%; padding: 12px 14px 76px; }
            .sk-bottom-nav { display: flex; }
            .sk-period-badge { display: none !important; }
            .sk-user-text { display: none !important; }
            .sk-breadbar { display: none !important; }
        }
        @media (min-width: 992px) { .sk-burger { display: none !important; } }
        .pagination svg, nav[role="navigation"] svg { width: .9rem !important; height: .9rem !important; }
        .pagination { gap: 2px; }
        .alert { border-radius: 8px; }
        /* Color helpers */
        .text-primary-teal { color: var(--primary) !important; }
        .bg-primary-teal { background-color: var(--primary) !important; }
        .bg-teal-subtle { background-color: var(--primary-bg) !important; color: var(--primary-dark) !important; }
        .text-emerald { color: var(--primary) !important; }
        .text-mint { color: var(--primary) !important; }
        .shadow-xs { box-shadow: 0 1px 3px rgba(15,23,42,0.06) !important; }

        /* Utility spacing shims & breathing typography */
        .fs-xs { font-size: .85rem !important; letter-spacing: 0.022em !important; line-height: 1.55 !important; }
        .fs-xxs { font-size: .78rem !important; letter-spacing: 0.026em !important; line-height: 1.5 !important; }
        .fs-sm { font-size: .94rem !important; letter-spacing: 0.015em !important; line-height: 1.58 !important; }
        .fw-extrabold { font-weight: 800 !important; }
        .tracking-tight { letter-spacing: 0.01em !important; }
        .lh-base { line-height: 1.65 !important; }

        /* GLOBAL BADGE POLISH - Mencegah Teks Menempel Garis Tepi */
        .badge {
            padding: 0.42rem 0.85rem !important;
            letter-spacing: 0.025em !important;
            line-height: 1.35 !important;
            border-radius: 50rem !important;
            font-weight: 700 !important;
        }
        .badge.fs-xxs, .fs-xxs.badge, .badge-xxs {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.025em !important;
        }
        .badge.fs-xs, .fs-xs.badge {
            padding: 0.4rem 0.82rem !important;
            font-size: 0.85rem !important;
            letter-spacing: 0.025em !important;
        }

        /* GLOBAL FORM CONTROLS POLISH - Lega & Nyaman di Android */
        .form-control, .form-select {
            padding: 10px 14px !important;
            min-height: 48px !important;
            border-radius: 12px !important;
            font-size: 0.93rem !important;
            letter-spacing: 0.02em !important;
            line-height: 1.55 !important;
            border: 1.5px solid #cbd5e1 !important;
        }
        .form-control-sm, .form-select-sm {
            padding: 8px 12px !important;
            min-height: 42px !important;
            border-radius: 10px !important;
            font-size: 0.88rem !important;
            letter-spacing: 0.02em !important;
        }
        textarea.form-control {
            min-height: 120px !important;
            padding: 12px 16px !important;
            line-height: 1.6 !important;
        }

        .space-y-1.5 > * + * { margin-top: 0.4rem !important; }
        .space-y-2 > * + * { margin-top: 0.5rem !important; }
        .space-y-2.5 > * + * { margin-top: 0.65rem !important; }
        .space-y-3 > * + * { margin-top: 1rem !important; }
        .space-y-4 > * + * { margin-top: 1.5rem !important; }
        .space-y-5 > * + * { margin-top: 2rem !important; }

        /* RESPONSIVE MOBILE FEED CARDS POLISH (Breathing Room, Clear Hierarchy) */
        .mobile-card {
            border-radius: 1.35rem !important;
            padding: 1.35rem 1.45rem !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            box-shadow: 0 4px 16px rgba(15,23,42,0.04) !important;
            transition: all 0.2s ease;
            margin-bottom: 1.25rem !important;
        }
        .mobile-card:active {
            transform: scale(0.995);
        }
        .mobile-card .card-name {
            font-size: 1.06rem !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            line-height: 1.4 !important;
            letter-spacing: 0.012em !important;
        }
        .mobile-card .card-sub {
            font-size: 0.86rem !important;
            color: #64748b !important;
            line-height: 1.55 !important;
            letter-spacing: 0.015em !important;
        }
        .mobile-card .card-info-box {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 0.86rem !important;
            color: #334155 !important;
            line-height: 1.65 !important;
            letter-spacing: 0.018em !important;
        }
        .mobile-card .btn-card-action {
            min-height: 46px !important;
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            border-radius: 50rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            padding: 0.65rem 1.35rem !important;
            letter-spacing: 0.025em !important;
        }
        .mobile-card .btn-card-icon {
            width: 46px !important;
            height: 46px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50rem !important;
            flex-shrink: 0 !important;
        }
        .badge-pill-custom {
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            padding: 0.4rem 0.9rem !important;
            letter-spacing: 0.025em !important;
            border-radius: 50rem !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $currentUser    = auth()->user();
        $currentStudent = $currentUser?->student;
        $classroomName  = $currentStudent?->classrooms->first()?->name ?? 'Siswa';
        $activeYear     = active_academic_year();
        $activeSem      = active_semester();
    @endphp

    <!-- TOPBAR -->
    <header class="sk-topbar">
        <button class="sk-burger" id="sidebarToggle" aria-label="Buka Menu">
            <i class="bi bi-list fs-4"></i>
        </button>
        <a href="{{ route('murid.dashboard') }}" class="sk-brand text-decoration-none">
            <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 36px; width: auto; max-width: 44px; object-fit: contain; margin-right: 6px;">
            <div class="d-flex flex-column justify-content-center">
                <div class="sk-brand-name">{{ app_setting('app_name', 'Ruang Ceritaku') }}</div>
                <div class="sk-brand-sub d-none d-sm-block">{{ app_setting('school_name', 'Portal Siswa') }}</div>
            </div>
        </a>
        <div class="sk-period-badge d-none d-lg-flex">
            <i class="bi bi-calendar3"></i>
            <span>{{ $activeYear?->name ?? '2025/2026' }}</span>
            <span class="mx-1" style="color:var(--text-muted)">•</span>
            <span>Semester {{ $activeSem?->name ?? 'Ganjil' }}</span>
        </div>
        <div class="sk-user-info">
            <div class="sk-user-text">
                <div class="sk-user-name">{{ Str::words($currentStudent?->name ?? $currentUser?->name, 3, '') }}</div>
                <div class="sk-user-role">{{ $classroomName }}</div>
            </div>
            <div class="dropdown">
                <img src="{{ $currentStudent?->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($currentStudent?->name ?? $currentUser?->name) . '&background=0d9488&color=ffffff&bold=true' }}"
                     alt="{{ $currentStudent?->name }}"
                     class="sk-avatar"
                     data-bs-toggle="dropdown"
                     aria-expanded="false">
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2 mt-2" style="min-width:220px;">
                    <li class="px-3 py-2" style="border-bottom:1px solid #f1f5f9;">
                        <div class="fw-bold text-dark" style="font-size:.85rem;">{{ $currentStudent?->name ?? $currentUser?->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;">NISN: {{ $currentStudent?->nisn ?? $currentUser?->username }}</div>
                    </li>
                    <li><a class="dropdown-item py-2" href="{{ route('murid.dashboard') }}" style="font-size:.83rem;"><i class="bi bi-house-door me-2 text-primary"></i>Beranda</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('murid.profile.index') }}" style="font-size:.83rem;"><i class="bi bi-person-gear me-2 text-primary"></i>Profil Saya</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('murid.konseling.pribadi') }}" style="font-size:.83rem;"><i class="bi bi-person-heart me-2 text-primary"></i>Konseling Pribadi</a></li>
                    @if(app_setting('ims_enabled', '1') === '1')
                        <li><a class="dropdown-item py-2" href="{{ route('murid.ims.index') }}" style="font-size:.83rem;"><i class="bi bi-clipboard2-check me-2" style="color:#7c3aed;"></i>Asesmen Masalah (IMS)</a></li>
                    @else
                        <li><a class="dropdown-item py-2 text-muted" href="javascript:void(0)" onclick="alert('Layanan Asesmen IMS sedang dinonaktifkan sementara oleh Guru BK.')" style="font-size:.83rem;opacity:0.65;cursor:not-allowed;" title="Asesmen dinonaktifkan"><i class="bi bi-lock me-2 text-secondary"></i>Asesmen Masalah (IMS) <span class="badge bg-secondary ms-1" style="font-size:.65rem;">Terkunci</span></a></li>
                    @endif
                    <li><a class="dropdown-item py-2" href="{{ route('murid.konseling.index') }}" style="font-size:.83rem;"><i class="bi bi-chat-heart me-2" style="color:#e11d48;"></i>Riwayat Konseling</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('murid.pengaduan.index') }}" style="font-size:.83rem;"><i class="bi bi-shield-fill-exclamation me-2 text-danger"></i>Pelayanan Darurat</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('murid.pamflet.index') }}" style="font-size:.83rem;"><i class="bi bi-images me-2 text-info"></i>Pamflet Kreativitas</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger" style="font-size:.83rem;">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar Portal
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- OVERLAY (mobile) -->
    <div class="sk-overlay" id="sidebarOverlay"></div>

    <!-- LAYOUT -->
    <div class="sk-layout">
        <!-- SIDEBAR -->
        <aside class="sk-sidebar" id="sidebarMenu">
            <div class="sk-sidebar-head">
                <div class="yr-label">Tahun Ajaran</div>
                <div class="yr-val">{{ $activeYear?->name ?? '2025/2026' }}</div>
                <div class="sm-badge"><i class="bi bi-calendar2-week me-1"></i>Semester {{ $activeSem?->name ?? 'Ganjil' }}</div>
            </div>
            <div class="sk-nav-section">
                <div class="sk-nav-heading">Menu Utama</div>
                <a href="{{ route('murid.dashboard') }}" class="sk-nav-item {{ request()->routeIs('murid.dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i><span>Beranda</span>
                </a>
                <a href="{{ route('murid.profile.index') }}" class="sk-nav-item {{ request()->routeIs('murid.profile*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i><span>Profil Saya</span>
                </a>
                <div class="sk-nav-heading mt-2">Layanan Konseling</div>
                <a href="{{ route('murid.konseling.create') }}" class="sk-nav-item {{ request()->routeIs('murid.konseling.create*') || request()->routeIs('murid.konseling.pribadi*') || request()->routeIs('murid.konseling.ajukan*') ? 'active' : '' }}">
                    <i class="bi bi-send-check"></i><span>Ajukan Konseling</span>
                </a>
                <a href="{{ route('murid.konseling.index') }}" class="sk-nav-item {{ request()->routeIs('murid.konseling.index*') || request()->routeIs('murid.konseling.show*') ? 'active' : '' }}">
                    <i class="bi bi-chat-heart"></i><span>Riwayat Konseling</span>
                </a>
                <a href="{{ route('murid.pengaduan.index') }}" class="sk-nav-item {{ request()->routeIs('murid.pengaduan.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-fill-exclamation text-danger"></i><span>Pelayanan Darurat</span>
                </a>
                <a href="{{ route('murid.pamflet.index') }}" class="sk-nav-item {{ request()->routeIs('murid.pamflet.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i><span>Pamflet Kreativitas</span>
                </a>

                <div class="sk-nav-heading mt-3">Asesmen Siswa</div>
                @if(app_setting('ims_enabled', '1') === '1')
                    <a href="{{ route('murid.ims.index') }}" class="sk-nav-item {{ request()->routeIs('murid.ims.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check"></i><span>Asesmen Masalah (IMS)</span>
                    </a>
                @else
                    <a href="javascript:void(0)" onclick="alert('Layanan Asesmen Masalah (IMS) saat ini sedang dinonaktifkan oleh pihak madrasah / Guru BK.')" class="sk-nav-item" style="opacity:0.6;cursor:not-allowed;" title="Layanan dinonaktifkan oleh Guru BK">
                        <i class="bi bi-lock"></i><span>Asesmen Masalah (IMS)</span>
                        <span class="badge bg-secondary ms-auto" style="font-size:.65rem;font-weight:600;">Terkunci</span>
                    </a>
                @endif
            </div>
            <div class="sk-sidebar-footer">
                <div style="background:var(--primary-bg);border:1px solid var(--primary-light);border-radius:8px;padding:10px 12px;margin-bottom:10px;">
                    <div style="font-size:.7rem;font-weight:700;color:var(--primary-dark);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">
                        <i class="bi bi-person-badge me-1"></i>Data Siswa
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:var(--text-dark);">{{ Str::limit($currentStudent?->name ?? $currentUser?->name, 22) }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted);">{{ $classroomName }} &bull; {{ $currentStudent?->nisn ?? '-' }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sk-nav-item w-100 border-0 bg-transparent text-danger" style="cursor:pointer;text-align:left;">
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span class="fw-bold">Keluar Portal</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="sk-main">
            <div class="sk-breadbar">
                <div style="flex:1;min-width:0;">
                    <div class="sk-bpage">@yield('title', 'Ruang Murid')</div>
                    <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                        <a href="{{ route('murid.dashboard') }}"><i class="bi bi-house-door me-1"></i>Beranda</a>
                        @hasSection('breadcrumb')
                            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
                            @yield('breadcrumb')
                        @endif
                    </div>
                </div>
            </div>
            <x-alert />
            @yield('content')
        </main>
    </div>

    <!-- MOBILE BOTTOM NAV -->
    <nav class="sk-bottom-nav">
        <a href="{{ route('murid.dashboard') }}" class="sk-btab {{ request()->routeIs('murid.dashboard*') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('murid.dashboard*') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('murid.konseling.create') }}" class="sk-btab {{ request()->routeIs('murid.konseling.create*') || request()->routeIs('murid.konseling.pribadi*') ? 'active' : '' }}">
            <i class="bi bi-send-check{{ request()->routeIs('murid.konseling.create*') ? '-fill' : '' }}"></i>
            <span>Konseling</span>
        </a>
        @if(app_setting('ims_enabled', '1') === '1')
            <a href="{{ route('murid.ims.index') }}" class="sk-btab {{ request()->routeIs('murid.ims.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check{{ request()->routeIs('murid.ims.*') ? '-fill' : '' }}"></i>
                <span>Asesmen</span>
            </a>
        @else
            <a href="javascript:void(0)" onclick="alert('Layanan Asesmen Masalah (IMS) saat ini sedang dinonaktifkan oleh pihak madrasah / Guru BK.')" class="sk-btab" style="opacity:0.5;cursor:not-allowed;" title="Asesmen dinonaktifkan">
                <i class="bi bi-lock"></i>
                <span>Asesmen</span>
            </a>
        @endif
        <a href="{{ route('murid.konseling.index') }}" class="sk-btab {{ request()->routeIs('murid.konseling.index*') || request()->routeIs('murid.konseling.show*') ? 'active' : '' }}">
            <i class="bi bi-chat-heart{{ request()->routeIs('murid.konseling.index*') ? '-fill' : '' }}"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('murid.pengaduan.index') }}" class="sk-btab {{ request()->routeIs('murid.pengaduan.*') ? 'active' : '' }}">
            <i class="bi bi-shield-fill-exclamation text-danger"></i>
            <span>Darurat</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var sidebar = document.getElementById('sidebarMenu');
            var toggle  = document.getElementById('sidebarToggle');
            var overlay = document.getElementById('sidebarOverlay');
            function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
            function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; }
            if (toggle)  toggle.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeSidebar(); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
