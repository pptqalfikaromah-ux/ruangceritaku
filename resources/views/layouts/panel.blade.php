<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Konseling') - {{ app_setting('app_name', 'Ruang Ceritaku') }}</title>
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
            --gold: #f59e0b;
            --gold-light: #fef3c7;
            --sidebar-w: 240px;
            --topbar-h: 60px;
            --font-main: 'Nunito', 'Inter', system-ui, sans-serif;
            --text-dark: #1e293b;
            --text-mid: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --bg-page: #f1f5f9;

            /* Backward compatibility aliases */
            --emis-teal: #0d9488;
            --emis-teal-dark: #0f766e;
            --emis-teal-light: #f0fdfa;
            --emis-teal-subtle: #ccfbf1;
            --emis-accent: #f59e0b;
            --emis-gold: #f59e0b;
            --emis-gold-hover: #d97706;
            --emis-gold-light: #fef3c7;
            --emis-gold-subtle: #fef3c7;
            --emis-sidebar-bg: #ffffff;
            --emis-sidebar-width: 240px;
            --emis-topbar-height: 60px;
            --emis-body-bg: #f1f5f9;
            --emis-card-border: #e2e8f0;
            --emis-text-dark: #1e293b;
            --emis-text-muted: #64748b;
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
        }

        /* TOPBAR (Identik dengan Portal Murid) */
        .sk-topbar, .modern-topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            z-index: 1040;
            display: flex; align-items: center;
            padding: 0 1.25rem; gap: 1rem;
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
            color: #ffffff;
        }
        .sk-brand-name { font-size: 1.15rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -.01em; line-height: 1; }
        .sk-brand-sub { font-size: .65rem; font-weight: 700; color: var(--text-muted); letter-spacing: .04em; text-transform: uppercase; }

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

        /* SIDEBAR (Identik dengan Portal Murid: Putih Bersih dengan Header Teal) */
        .sk-sidebar, .modern-sidebar, .emis-sidebar {
            position: fixed; top: var(--topbar-h); left: 0; bottom: 0;
            width: var(--sidebar-w); background: #ffffff;
            border-right: 1px solid var(--border-color);
            z-index: 1030; display: flex; flex-direction: column;
            overflow-y: auto; overflow-x: hidden;
            scrollbar-width: thin; scrollbar-color: var(--border-color) transparent;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            box-shadow: none;
        }

        .sk-sidebar-head { background: var(--primary); padding: 12px 16px; flex-shrink: 0; }
        .sk-sidebar-head .yr-label { font-size: .7rem; font-weight: 800; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .06em; }
        .sk-sidebar-head .yr-val { font-size: .92rem; font-weight: 700; color: #fff; margin-top: 1px; }
        .sk-sidebar-head .sm-badge {
            display: inline-block; background: rgba(255,255,255,.2); color: #fff;
            border-radius: 12px; padding: 2px 10px; font-size: .7rem; font-weight: 700; margin-top: 6px;
        }

        .sk-nav-section { padding: 8px 10px 0; }
        .sk-nav-heading, .nav-category-header, .emis-menu-header {
            font-size: .63rem !important; font-weight: 800 !important; text-transform: uppercase !important;
            letter-spacing: .08em !important; color: var(--text-muted) !important; padding: 10px 10px 4px !important;
            margin: 0 !important;
        }

        .sk-nav-item, .modern-nav-link, .emis-menu-link {
            display: flex !important; align-items: center !important; gap: 10px !important;
            padding: 8px 12px !important; border-radius: 8px !important;
            font-size: .84rem !important; font-weight: 600 !important; color: var(--text-mid) !important;
            text-decoration: none !important; transition: all .18s ease !important; margin-bottom: 2px !important;
            position: relative !important; white-space: nowrap !important; overflow: hidden !important;
        }
        .sk-nav-item i, .modern-nav-link i, .emis-menu-link i {
            font-size: 1.1rem !important; flex-shrink: 0 !important; color: var(--text-muted) !important;
            transition: color .18s !important; margin-right: 0 !important; width: auto !important;
        }
        .sk-nav-item:hover, .modern-nav-link:hover, .emis-menu-link:hover {
            background: var(--primary-bg) !important; color: var(--primary-dark) !important;
        }
        .sk-nav-item:hover i, .modern-nav-link:hover i, .emis-menu-link:hover i {
            color: var(--primary) !important;
        }
        .sk-nav-item.active, .modern-nav-link.active, .emis-menu-link.active {
            background: linear-gradient(90deg, var(--primary-bg) 0%, #e0f7f4 100%) !important;
            color: var(--primary-dark) !important; font-weight: 700 !important;
            border-left: 3px solid var(--primary) !important; padding-left: 9px !important;
            box-shadow: none !important; border-radius: 8px !important;
        }
        .sk-nav-item.active i, .modern-nav-link.active i, .emis-menu-link.active i {
            color: var(--primary) !important;
        }

        /* SUBMENU & COLLAPSIBLE NAVIGATION */
        .sk-nav-toggle {
            display: flex !important; align-items: center !important; gap: 10px !important;
            padding: 8px 12px !important; border-radius: 8px !important;
            font-size: .84rem !important; font-weight: 600 !important; color: var(--text-mid) !important;
            background: transparent !important; border: none !important; width: 100% !important;
            text-align: left !important; cursor: pointer !important; transition: all .18s ease !important;
            margin-bottom: 2px !important;
        }
        .sk-nav-toggle:hover {
            background: var(--primary-bg) !important; color: var(--primary-dark) !important;
        }
        .sk-nav-toggle:hover i { color: var(--primary) !important; }
        .sk-nav-toggle .sk-chevron {
            transition: transform .22s ease; font-size: .75rem !important; color: var(--text-muted);
        }
        .sk-nav-toggle[aria-expanded="true"] {
            background: #f8fafc !important; color: var(--primary-dark) !important; font-weight: 700 !important;
        }
        .sk-nav-toggle[aria-expanded="true"] .sk-chevron {
            transform: rotate(180deg); color: var(--primary);
        }
        .sk-nav-toggle.has-active {
            color: var(--primary-dark) !important; font-weight: 700 !important;
        }
        .sk-submenu {
            padding-left: 14px; margin-bottom: 4px; border-left: 2px solid #e2e8f0; margin-left: 20px;
        }
        .sk-submenu .sk-subitem {
            display: flex !important; align-items: center !important; gap: 8px !important;
            padding: 6px 10px !important; border-radius: 6px !important;
            font-size: .8rem !important; font-weight: 500 !important; color: var(--text-mid) !important;
            text-decoration: none !important; transition: all .15s ease !important; margin-bottom: 2px !important;
        }
        .sk-submenu .sk-subitem i { font-size: .85rem !important; color: var(--text-muted); }
        .sk-submenu .sk-subitem:hover {
            background: var(--primary-bg) !important; color: var(--primary-dark) !important;
        }
        .sk-submenu .sk-subitem:hover i { color: var(--primary) !important; }
        .sk-submenu .sk-subitem.active {
            background: var(--primary-bg) !important; color: var(--primary-dark) !important; font-weight: 700 !important;
        }
        .sk-submenu .sk-subitem.active i { color: var(--primary) !important; }

        /* LIVE BELL NOTIFICATIONS & DANGER PULSE */
        @keyframes pulse-danger {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            50% { transform: scale(1.08); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .badge-danger-pulse {
            animation: pulse-danger 1.8s infinite !important;
            background-color: #ef4444 !important; color: #ffffff !important; font-weight: 800 !important;
        }
        .bell-pulse {
            animation: pulse-danger 2s infinite !important;
        }
        .sk-bell-btn {
            position: relative; width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: #f8fafc; border: 1px solid var(--border-color); color: var(--text-dark);
            cursor: pointer; transition: all .2s;
        }
        .sk-bell-btn:hover {
            background: var(--primary-bg); color: var(--primary); border-color: var(--primary-light);
        }
        .sk-bell-badge {
            position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px;
            padding: 0 4px; border-radius: 9px; font-size: 0.65rem; font-weight: 800;
            line-height: 18px; text-align: center;
        }

        .sk-sidebar-footer {
            margin-top: auto; padding: 12px 10px; border-top: 1px solid var(--border-color); flex-shrink: 0;
            background: #ffffff;
        }

        /* LAYOUT & MAIN */
        .sk-layout, .modern-app-layout { display: flex; min-height: 100vh; padding-top: var(--topbar-h); }
        .sk-main, .modern-main-content, .emis-main {
            margin-left: var(--sidebar-w) !important; flex-grow: 1 !important; min-width: 0 !important;
            padding: 20px 24px 36px !important; transition: margin-left .3s ease !important;
            width: calc(100% - var(--sidebar-w)) !important; max-width: 100% !important;
        }

        /* BREADCRUMB BAR (Identik dengan Portal Murid) */
        .sk-breadbar, .modern-breadcrumb, .emis-breadcrumb-card {
            background: #ffffff !important; border: 1px solid var(--border-color) !important;
            border-radius: 8px !important; padding: 10px 16px !important; margin-bottom: 20px !important;
            display: flex !important; align-items: center !important; gap: 6px !important;
            font-size: .82rem !important; color: var(--text-muted) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,.04) !important; width: 100% !important;
        }
        .sk-breadbar .sk-bpage {
            font-size: 1.05rem !important; font-weight: 800 !important; color: var(--text-dark) !important;
            display: block !important; margin-bottom: 2px !important;
        }
        .sk-breadbar a, .modern-breadcrumb a, .emis-breadcrumb-card a {
            color: var(--primary) !important; text-decoration: none !important; font-weight: 600 !important;
        }
        .sk-breadbar a:hover, .modern-breadcrumb a:hover, .emis-breadcrumb-card a:hover {
            text-decoration: underline !important;
        }
        .sk-breadbar .current, .modern-breadcrumb .current, .emis-breadcrumb-card .current {
            color: var(--text-dark) !important; font-weight: 700 !important;
        }

        /* BUTTONS & BADGES */
        .btn-gold { background: var(--gold) !important; border: none !important; color: var(--text-dark) !important; font-weight: 700 !important; }
        .btn-gold:hover { background: #d97706 !important; color: #fff !important; }
        .btn-mint, .btn-emerald, .btn-primary {
            background: var(--primary) !important; border-color: var(--primary) !important; color: #fff !important; font-weight: 700 !important;
        }
        .btn-mint:hover, .btn-emerald:hover, .btn-primary:hover {
            background: var(--primary-dark) !important; border-color: var(--primary-dark) !important; color: #fff !important;
        }
        .btn-outline-primary {
            color: var(--primary) !important; border-color: var(--primary) !important; font-weight: 600 !important;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary) !important; color: #fff !important;
        }

        /* CARDS & PANELS */
        .card, .card-app {
            background: #ffffff !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,.04) !important;
        }
        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        /* STAT CARDS & FEATURE CARDS (Harmoni Hijau-Teal Portal Siswa) */
        .card-stat {
            background: #ffffff !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            padding: 16px !important;
            display: flex !important;
            align-items: center !important;
            gap: 14px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,.04) !important;
        }
        .card-stat-icon {
            width: 44px !important; height: 44px !important; border-radius: 10px !important;
            display: flex !important; align-items: center !important; justify-content: center !important;
            font-size: 1.35rem !important; flex-shrink: 0 !important;
        }

        .emis-section-title {
            font-size: 1rem !important; font-weight: 800 !important; color: var(--text-dark) !important;
            display: flex !important; align-items: center !important; gap: 8px !important;
            margin-top: 1.5rem !important; margin-bottom: 0.85rem !important;
        }
        .emis-section-title i { color: var(--primary) !important; }

        .emis-feature-card {
            background: #ffffff !important; border: 1px solid var(--border-color) !important;
            border-radius: 10px !important; padding: 1.15rem 1.25rem !important;
            display: flex !important; align-items: center !important; justify-content: space-between !important;
            text-decoration: none !important; color: var(--text-dark) !important;
            transition: all .2s ease !important; box-shadow: 0 1px 3px rgba(0,0,0,.03) !important;
            height: 100% !important;
        }
        .emis-feature-card:hover {
            border-color: var(--primary) !important; transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(13,148,136,.1) !important; color: var(--primary-dark) !important;
        }
        .emis-feature-icon-box {
            width: 44px !important; height: 44px !important; border-radius: 10px !important;
            background-color: var(--primary-bg) !important; color: var(--primary) !important;
            display: flex !important; align-items: center !important; justify-content: center !important;
            font-size: 1.3rem !important; flex-shrink: 0 !important; margin-right: 12px !important;
        }
        .emis-feature-title { font-size: .92rem !important; font-weight: 700 !important; color: var(--text-dark) !important; margin-bottom: 2px !important; }
        .emis-feature-desc { font-size: .75rem !important; color: var(--text-muted) !important; margin: 0 !important; line-height: 1.35 !important; }
        .emis-feature-arrow { color: var(--text-muted) !important; font-size: 1rem !important; transition: transform .15s ease !important; }
        .emis-feature-card:hover .emis-feature-arrow { color: var(--primary) !important; transform: translateX(3px) !important; }

        .btn-emis-pill {
            padding: 7px 18px !important; border-radius: 50px !important; font-size: .83rem !important;
            font-weight: 700 !important; border: 1.5px solid var(--border-color) !important;
            display: inline-flex !important; align-items: center !important; gap: 6px !important;
            transition: all .2s ease !important;
        }
        .btn-emis-pill-active {
            background-color: var(--primary) !important; border-color: var(--primary) !important;
            color: #ffffff !important; box-shadow: 0 2px 8px rgba(13,148,136,.25) !important;
        }
        .btn-emis-pill-outline {
            background-color: #ffffff !important; color: var(--text-mid) !important;
        }
        .btn-emis-pill-outline:hover {
            border-color: var(--primary) !important; color: var(--primary) !important; background-color: var(--primary-bg) !important;
        }

        /* TABEL & FORMULIR */
        .table { font-size: .86rem !important; }
        .table thead th { font-weight: 700 !important; color: var(--text-mid) !important; font-size: .78rem !important; text-transform: uppercase !important; letter-spacing: .04em !important; }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(13,148,136,.18) !important;
        }

        /* OVERLAY (MOBILE) */
        .sk-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 1025; backdrop-filter: blur(2px); }
        .sk-overlay.show { display: block; }

        /* HELPER WARNA & UTILITY */
        .text-primary-teal, .text-teal, .text-mint, .text-emerald { color: var(--primary) !important; }
        .bg-primary-teal, .bg-teal { background-color: var(--primary) !important; }
        .bg-teal-subtle { background-color: var(--primary-bg) !important; color: var(--primary-dark) !important; }
        .border-teal { border-color: var(--primary) !important; }
        .text-slate-800 { color: #1e293b !important; }
        .fs-xs { font-size: .75rem !important; }
        .fs-xxs { font-size: .68rem !important; }
        .fw-extrabold { font-weight: 800 !important; }

        /* PAGINATION */
        .pagination svg, .page-item svg, nav[role="navigation"] svg { width: 1rem !important; height: 1rem !important; max-width: 1rem !important; max-height: 1rem !important; }
        .page-link { color: var(--primary); }
        .page-item.active .page-link { background-color: var(--primary); border-color: var(--primary); }

        /* SPACING UTILITIES (SPACE-Y) */
        .space-y-1 > * + * { margin-top: 0.25rem !important; }
        .space-y-1.5 > * + * { margin-top: 0.4rem !important; }
        .space-y-2 > * + * { margin-top: 0.5rem !important; }
        .space-y-2.5 > * + * { margin-top: 0.65rem !important; }
        .space-y-3 > * + * { margin-top: 1rem !important; }
        .space-y-4 > * + * { margin-top: 1.5rem !important; }
        .space-y-5 > * + * { margin-top: 1.75rem !important; }
        .space-y-6 > * + * { margin-top: 2rem !important; }

        /* ANDROID MOBILE BOTTOM NAVIGATION BAR */
        .panel-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(226, 232, 240, 0.9);
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 1040;
            padding: 0 4px;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
        }
        .panel-btab {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 6px 0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
        }
        .panel-btab-icon {
            font-size: 1.25rem;
            line-height: 1;
            margin-bottom: 2px;
            transition: transform 0.2s ease;
        }
        .panel-btab:hover, .panel-btab:focus {
            color: var(--primary);
            text-decoration: none;
        }
        .panel-btab.active {
            color: var(--primary);
            font-weight: 800;
        }
        .panel-btab.active .panel-btab-icon {
            transform: scale(1.15);
        }
        .panel-nav-badge {
            position: absolute;
            top: -4px;
            right: -8px;
            min-width: 17px;
            height: 17px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.62rem;
            font-weight: 800;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.4);
            border: 1.5px solid #ffffff;
        }

        /* RESPONSIVE & MOBILE FRIENDLY */
        @media (max-width: 991.98px) {
            body { padding-bottom: 74px !important; }
            .sk-sidebar, .modern-sidebar, .emis-sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: none;
            }
            .sk-sidebar.open, .modern-sidebar.show, .emis-sidebar.show {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.2);
            }
            .sk-main, .modern-main-content, .emis-main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 14px 12px 84px !important;
            }
            .sk-period-badge { display: none !important; }
            .sk-user-text { display: none !important; }
            .sk-breadbar { display: none !important; }
        }
        @media (max-width: 767.98px) {
            .table-responsive { border-radius: 8px; }
            .card-header, .card-body, .card-footer { padding: 1rem !important; }
            .btn { font-size: 0.82rem !important; }
        }
        @media (max-width: 575.98px) {
            .sk-topbar, .modern-topbar {
                height: 56px !important;
                padding: 0 10px !important;
            }
            .sk-brand img {
                height: 32px !important;
                max-width: 40px !important;
            }
            .h4, h4 { font-size: 1.15rem !important; }
            .h5, h5 { font-size: 1rem !important; }
            .sk-main { padding: 12px 10px 84px !important; }
        }
        @media (min-width: 992px) { .sk-burger { display: none !important; } }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $currentUser = auth()->user();
        $activeYear  = active_academic_year();
        $activeSem   = active_semester();
        $userRole    = $currentUser?->role;
        $menuItems   = config('menu.items', []);
        $roleLabel   = $currentUser?->role === 'admin' ? 'Administrator' : ($currentUser?->role === 'guru_bk' ? 'Guru BK / Konselor' : 'Pengguna');
        $portalLabel = $currentUser?->role === 'admin' ? 'Admin Portal' : 'Guru BK Portal';
        $avatarUrl   = $currentUser?->avatar_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($currentUser?->name ?? 'User') . '&background=0d9488&color=ffffff&bold=true');
    @endphp

    <!-- TOPBAR (Identik dengan Portal Murid) -->
    <header class="sk-topbar modern-topbar">
        <button class="sk-burger" id="sidebarToggle" aria-label="Buka Menu" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>

        <a href="{{ route('panel.dashboard') }}" class="sk-brand text-decoration-none">
            <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 36px; width: auto; max-width: 44px; object-fit: contain; margin-right: 6px;">
            <div class="d-flex flex-column justify-content-center">
                <div class="sk-brand-name">{{ app_setting('app_name', 'Ruang Ceritaku') }}</div>
                <div class="sk-brand-sub d-none d-sm-block">{{ app_setting('school_name', $portalLabel) }}</div>
            </div>
        </a>

        <!-- Academic Period Badge -->
        <div class="sk-period-badge d-none d-lg-flex">
            <i class="bi bi-calendar3"></i>
            <span>{{ $activeYear?->name ?? '2025/2026' }}</span>
            <span class="mx-1" style="color:var(--text-muted)">•</span>
            <span>Semester {{ $activeSem?->name ?? 'Ganjil' }}</span>
        </div>

        <!-- User Profile & Notifications Widget -->
        <div class="sk-user-info">
            <!-- Live Notifications Bell Dropdown -->
            @php
                $panelNotifs = panel_notifications();
                $recentNotifs = panel_recent_notifications(8);
            @endphp
            <div class="dropdown me-1">
                <button class="sk-bell-btn {{ $panelNotifs['has_danger'] ? 'bell-pulse border-danger' : '' }}" 
                        type="button" 
                        id="dropdownNotifBell" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false" 
                        title="{{ $panelNotifs['total'] }} Notifikasi Baru">
                    <i class="bi {{ $panelNotifs['has_danger'] ? 'bi-bell-fill text-danger' : 'bi-bell' }} fs-5"></i>
                    @if($panelNotifs['total'] > 0)
                        <span class="sk-bell-badge {{ $panelNotifs['has_danger'] ? 'badge-danger-pulse' : 'bg-danger text-white' }}">
                            {{ $panelNotifs['total'] > 99 ? '99+' : $panelNotifs['total'] }}
                        </span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end shadow-lg border rounded-3 py-0 mt-2" 
                     aria-labelledby="dropdownNotifBell" 
                     style="width: 360px; max-width: 90vw; z-index: 1060; border-color: var(--border-color) !important;">
                    <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-1.5">
                                <i class="bi bi-bell-fill text-primary-teal"></i> Notifikasi Masuk
                                @if($panelNotifs['total'] > 0)
                                    <span class="badge {{ $panelNotifs['has_danger'] ? 'badge-danger-pulse' : 'bg-primary-teal text-white' }} rounded-pill fs-xxs">
                                        {{ $panelNotifs['total'] }} Baru
                                    </span>
                                @endif
                            </div>
                            @if($panelNotifs['has_danger'])
                                <div class="text-danger fw-bold fs-xxs mt-0.5">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Ada aduan darurat / berisiko tinggi!
                                </div>
                            @endif
                        </div>
                        @if($panelNotifs['total'] > 0)
                            <form action="{{ route('panel.notifications.mark-all-read') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0 text-muted fs-xxs hover-teal" title="Tandai semua dibaca">
                                    <i class="bi bi-check2-all me-1"></i> Tandai Dibaca
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="list-group list-group-flush overflow-auto" style="max-height: 360px;">
                        @forelse($recentNotifs as $notif)
                            <a href="{{ $notif['url'] }}" 
                               class="list-group-item list-group-item-action p-2.5 border-bottom transition-all {{ $notif['is_danger'] ? 'bg-danger-subtle' : '' }}">
                                <div class="d-flex align-items-start gap-2.5">
                                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0 {{ $notif['is_danger'] ? 'bg-white shadow-xs' : 'bg-light' }}" style="width: 36px; height: 36px;">
                                        <i class="bi {{ $notif['icon'] }} fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                            <span class="badge {{ $notif['badge_class'] }} rounded-pill fs-xxs px-2 py-0.5">
                                                {{ $notif['badge_label'] }}
                                            </span>
                                            <span class="text-muted fs-xxs">{{ $notif['time_ago'] }}</span>
                                        </div>
                                        <div class="fw-bold text-dark fs-xs text-truncate">{{ $notif['title'] }}</div>
                                        <div class="text-muted fs-xxs text-truncate">
                                            <i class="bi bi-person me-1"></i>{{ $notif['sender'] }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-1"></i>
                                <div class="fw-bold fs-xs text-dark">Tidak Ada Notifikasi Baru</div>
                                <div class="fs-xxs">Semua aduan, konseling, dan IMS telah ditinjau.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2 bg-light border-top text-center">
                        <div class="d-flex justify-content-around small fs-xxs">
                            <a href="{{ route('panel.konseling.index') }}" class="text-decoration-none text-muted hover-teal">
                                Konseling: <strong>{{ $panelNotifs['counseling'] }}</strong>
                            </a>
                            <span class="text-muted">&bull;</span>
                            <a href="{{ route('panel.pengaduan.index') }}" class="text-decoration-none text-muted hover-teal">
                                Aduan: <strong>{{ $panelNotifs['complaints'] }}</strong>
                            </a>
                            <span class="text-muted">&bull;</span>
                            <a href="{{ route('panel.aduan-walikelas.index') }}" class="text-decoration-none text-muted hover-teal">
                                Walas: <strong>{{ $panelNotifs['homeroom_complaints'] }}</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sk-user-text">
                <div class="sk-user-name">{{ Str::words($currentUser?->name ?? 'User', 3, '') }}</div>
                <div class="sk-user-role">{{ $roleLabel }}</div>
            </div>

            <div class="dropdown">
                <img src="{{ $avatarUrl }}"
                     alt="{{ $currentUser?->name }}"
                     class="sk-avatar"
                     data-bs-toggle="dropdown"
                     aria-expanded="false">

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2 mt-2" style="min-width: 230px; border: 1px solid var(--border-color) !important;">
                    <li class="px-3 py-2" style="border-bottom: 1px solid #f1f5f9; background: var(--primary-bg);">
                        <div class="fw-bold text-dark" style="font-size: .85rem;">{{ $currentUser?->name }}</div>
                        <div class="text-muted" style="font-size: .72rem;">{{ $currentUser?->email ?? $currentUser?->username }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('panel.setting.profile') }}" style="font-size: .83rem;">
                            <i class="bi bi-person me-2 text-primary-teal"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('panel.password.change') }}" style="font-size: .83rem;">
                            <i class="bi bi-key me-2 text-warning"></i> Ubah Password
                        </a>
                    </li>
                    @if($currentUser?->isAdmin())
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('panel.setting.index') }}" style="font-size: .83rem;">
                                <i class="bi bi-gear me-2 text-success"></i> Pengaturan Madrasah
                            </a>
                        </li>
                    @endif
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger fw-semibold" style="font-size: .83rem;">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar Portal
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- OVERLAY (Mobile) -->
    <div class="sk-overlay" id="sidebarOverlay"></div>

    <!-- MAIN APP LAYOUT -->
    <div class="sk-layout modern-app-layout">
        <!-- SIDEBAR (Identik dengan Portal Murid: Header Teal + Menu Putih Elegan) -->
        <aside class="sk-sidebar modern-sidebar emis-sidebar" id="sidebarMenu">
            <!-- Sidebar Head: Tahun Ajaran & Semester -->
            <div class="sk-sidebar-head">
                <div class="yr-label">Tahun Ajaran Aktif</div>
                <div class="yr-val">{{ $activeYear?->name ?? '2025/2026' }}</div>
                <div class="sm-badge">
                    <i class="bi bi-calendar2-week me-1"></i>Semester {{ $activeSem?->name ?? 'Ganjil' }}
                </div>
            </div>

            <!-- Nav Items -->
            <div class="sk-nav-section flex-grow-1">
                @php
                    $panelNotifs = panel_notifications();
                @endphp
                @foreach($menuItems as $item)
                    @if(in_array($userRole, $item['roles']))
                        @if($item['type'] === 'header')
                            <div class="sk-nav-heading {{ $loop->first ? '' : 'mt-2' }}">
                                {{ $item['label'] }}
                            </div>
                        @elseif($item['type'] === 'item')
                            @php
                                $isActive = request()->routeIs($item['active']);
                            @endphp
                            <a href="{{ route($item['route']) }}" class="sk-nav-item modern-nav-link emis-menu-link {{ $isActive ? 'active' : '' }}">
                                <i class="bi {{ $item['icon'] }}"></i>
                                <span class="flex-grow-1">{{ $item['label'] }}</span>
                            </a>
                        @elseif($item['type'] === 'dropdown')
                            @php
                                // Cek apakah salah satu anak aktif
                                $hasActiveChild = false;
                                foreach($item['children'] as $child) {
                                    if (in_array($userRole, $child['roles']) && request()->routeIs($child['active'])) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="sk-nav-dropdown mb-1">
                                <button type="button" 
                                        class="sk-nav-toggle {{ $hasActiveChild ? 'has-active' : '' }}" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#{{ $item['id'] }}" 
                                        aria-expanded="{{ $hasActiveChild ? 'true' : 'false' }}">
                                    <i class="bi {{ $item['icon'] }}"></i>
                                    <span class="flex-grow-1">{{ $item['label'] }}</span>
                                    <i class="bi bi-chevron-down sk-chevron"></i>
                                </button>
                                <div class="collapse {{ $hasActiveChild ? 'show' : '' }} sk-submenu" id="{{ $item['id'] }}">
                                    @foreach($item['children'] as $child)
                                        @if(in_array($userRole, $child['roles']))
                                            @php
                                                $isChildActive = request()->routeIs($child['active']);
                                            @endphp
                                            <a href="{{ route($child['route']) }}" class="sk-subitem {{ $isChildActive ? 'active' : '' }}">
                                                <i class="bi {{ $child['icon'] ?? 'bi-circle' }}"></i>
                                                <span class="flex-grow-1">{{ $child['label'] }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- Sidebar Footer -->
            <div class="sk-sidebar-footer">
                <div style="background: var(--primary-bg); border: 1px solid var(--primary-light); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px;">
                    <div style="font-size: .7rem; font-weight: 700; color: var(--primary-dark); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 2px;">
                        <i class="bi bi-shield-check me-1"></i>{{ $roleLabel }}
                    </div>
                    <div style="font-size: .82rem; font-weight: 700; color: var(--text-dark);">
                        {{ Str::limit($currentUser?->name ?? 'User', 22) }}
                    </div>
                    <div style="font-size: .7rem; color: var(--text-muted);">
                        {{ $currentUser?->email ?? $currentUser?->username }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sk-nav-item w-100 border-0 bg-transparent text-danger" style="cursor: pointer; text-align: left;">
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span class="fw-bold">Keluar Portal</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="sk-main modern-main-content emis-main">
            <!-- Breadcrumbs Bar (Identik dengan Portal Murid) -->
            <div class="sk-breadbar modern-breadcrumb emis-breadcrumb-card">
                <div style="flex: 1; min-width: 0;">
                    <div class="sk-bpage">@yield('title', 'Panel Konseling')</div>
                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                        <a href="{{ route('panel.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Beranda
                        </a>
                        @hasSection('breadcrumb')
                            @php
                                $bc = trim($__env->yieldContent('breadcrumb'));
                            @endphp
                            @if(!str_contains($bc, 'Beranda') && !str_contains($bc, 'beranda'))
                                <i class="bi bi-chevron-right" style="font-size: .65rem; color: var(--text-muted);"></i>
                                @yield('breadcrumb')
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <x-alert />
            @yield('content')
        </main>
    </div>

    <!-- ANDROID MOBILE BOTTOM NAVIGATION BAR -->
    <nav class="panel-bottom-nav d-lg-none" id="panelBottomNav">
        <a href="{{ route('panel.dashboard') }}" class="panel-btab {{ request()->routeIs('panel.dashboard*') || request()->routeIs('panel.beranda*') ? 'active' : '' }}">
            <div class="panel-btab-icon">
                <i class="bi bi-house-door{{ request()->routeIs('panel.dashboard*') || request()->routeIs('panel.beranda*') ? '-fill' : '' }}"></i>
            </div>
            <span>Beranda</span>
        </a>
        <a href="{{ route('panel.konseling.index') }}" class="panel-btab {{ request()->routeIs('panel.konseling.*') ? 'active' : '' }}">
            <div class="panel-btab-icon position-relative">
                <i class="bi bi-chat-heart{{ request()->routeIs('panel.konseling.*') ? '-fill' : '' }}"></i>
                @if(isset($panelNotifs['counseling']) && $panelNotifs['counseling'] > 0)
                    <span class="panel-nav-badge">{{ $panelNotifs['counseling'] }}</span>
                @endif
            </div>
            <span>Konseling</span>
        </a>
        <a href="{{ route('panel.murid.index') }}" class="panel-btab {{ request()->routeIs('panel.murid.*') ? 'active' : '' }}">
            <div class="panel-btab-icon">
                <i class="bi bi-people{{ request()->routeIs('panel.murid.*') ? '-fill' : '' }}"></i>
            </div>
            <span>Murid</span>
        </a>
        <a href="{{ route('panel.pengaduan.index') }}" class="panel-btab {{ request()->routeIs('panel.pengaduan.*') ? 'active' : '' }}">
            <div class="panel-btab-icon position-relative">
                <i class="bi bi-shield{{ request()->routeIs('panel.pengaduan.*') ? '-fill' : '-check' }}"></i>
                @if(isset($panelNotifs['complaints']) && $panelNotifs['complaints'] > 0)
                    <span class="panel-nav-badge">{{ $panelNotifs['complaints'] }}</span>
                @endif
            </div>
            <span>Pengaduan</span>
        </a>
        <button type="button" class="panel-btab border-0 bg-transparent" id="mobileMenuMoreBtn" aria-label="Buka Menu Lengkap">
            <div class="panel-btab-icon">
                <i class="bi bi-grid-fill"></i>
            </div>
            <span>Menu</span>
        </button>
    </nav>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Mobile Toggle Script (Identik dengan Portal Murid & Android App) -->
    <script>
        (function () {
            var sidebar = document.getElementById('sidebarMenu');
            var toggle  = document.getElementById('sidebarToggle');
            var overlay = document.getElementById('sidebarOverlay');
            var moreBtn = document.getElementById('mobileMenuMoreBtn');

            function openSidebar() {
                sidebar.classList.add('open');
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (toggle) toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            if (moreBtn) moreBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openSidebar();
            });

            if (overlay) overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
