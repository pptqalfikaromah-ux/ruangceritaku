<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Masuk') - {{ app_setting('app_name', 'Ruang Ceritaku') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ app_logo() }}">

    <!-- Google Fonts Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Nunito:wght@500;600;700;800&display=swap" rel="stylesheet">

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
            --font-main: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            --text-dark: #1e293b;
            --text-mid: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --bg-page: #f1f5f9;
        }

        body {
            font-family: var(--font-main);
            color: var(--text-dark);
            background-color: var(--bg-page);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            letter-spacing: 0.024em;
            word-spacing: 0.03em;
            line-height: 1.62;
            -webkit-font-smoothing: antialiased;
        }

        .text-mint, .text-primary-teal, .text-teal { color: var(--primary) !important; }
        .bg-mint, .bg-teal { background-color: var(--primary) !important; }
        .bg-teal-subtle { background-color: var(--primary-bg) !important; }
        .btn-gold { background: var(--gold); border: none; color: var(--text-dark); font-weight: 700; }
        .btn-gold:hover { background: #d97706; color: #fff; }
        .btn-mint, .btn-emerald { background: var(--primary); border: none; color: #fff; font-weight: 700; }
        .btn-mint:hover, .btn-emerald:hover { background: var(--primary-dark); color: #fff; }

        .form-control, .form-select {
            min-height: 50px;
            padding: 10px 16px;
            border-radius: 14px;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            line-height: 1.5;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.2) !important;
        }

        .badge {
            padding: 0.42rem 0.85rem !important;
            letter-spacing: 0.025em;
            line-height: 1.35;
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
