<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduExam') - Platform Ujian Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════ */
        /* DESIGN SYSTEM — EduExam                     */
        /* ═══════════════════════════════════════════ */
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --primary-border: #c7d2fe;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --success-border: #bbf7d0;
            --warning: #ea580c;
            --warning-light: #fff7ed;
            --warning-border: #fed7aa;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.05);
            --shadow-lg: 0 10px 30px rgba(0,0,0,.1);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            color: var(--gray-800);
            background: var(--gray-50);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: var(--primary); text-decoration: none; }
        a:hover { text-decoration: underline; }
        img { max-width: 100%; }
        input, select, textarea, button { font-family: inherit; font-size: inherit; }

        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: var(--radius-md);
            font-weight: 600; font-size: 0.875rem; cursor: pointer;
            border: 1px solid transparent; transition: all .18s ease;
            text-decoration: none; white-space: nowrap;
        }
        .btn:hover { text-decoration: none; transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); color: #fff; }
        .btn-secondary { background: var(--white); color: var(--gray-700); border-color: var(--gray-300); }
        .btn-secondary:hover { background: var(--gray-50); color: var(--gray-800); }
        .btn-success { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-success:hover { background: #15803d; color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-danger:hover { background: #b91c1c; color: #fff; }
        .btn-sm { padding: 6px 14px; font-size: 0.8125rem; border-radius: var(--radius-sm); }
        .btn-lg { padding: 14px 28px; font-size: 1rem; border-radius: var(--radius-lg); }
        .btn-block { width: 100%; justify-content: center; }
        .btn-icon { padding: 8px; border-radius: var(--radius-sm); }

        /* CARDS */
        .card {
            background: var(--white); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);
        }
        .card-header {
            padding: 16px 20px; border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-body { padding: 20px; }
        .card-title { font-size: 1rem; font-weight: 700; color: var(--gray-800); }

        /* FORMS */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.875rem; color: var(--gray-700); }
        .form-control {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md); font-size: 0.9375rem; color: var(--gray-800);
            background: var(--white); transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .form-control::placeholder { color: var(--gray-400); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-error { color: var(--danger); font-size: 0.8125rem; margin-top: 4px; }

        /* ALERTS */
        .alert { padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; display: flex; gap: 10px; align-items: flex-start; border: 1px solid transparent; font-size: 0.9rem; }
        .alert-success { background: var(--success-light); color: #14532d; border-color: var(--success-border); }
        .alert-error   { background: var(--danger-light); color: #7f1d1d; border-color: var(--danger-border); }
        .alert-warning { background: var(--warning-light); color: #7c2d12; border-color: var(--warning-border); }
        .alert-info    { background: var(--primary-light); color: #312e81; border-color: var(--primary-border); }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; }
        .badge-primary { background: var(--primary-light); color: var(--primary-dark); }
        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-warning { background: var(--warning-light); color: var(--warning); }
        .badge-danger  { background: var(--danger-light); color: var(--danger); }
        .badge-gray    { background: var(--gray-100); color: var(--gray-600); }

        /* TABLE */
        .table-wrap { overflow-x: auto; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); }
        table { width: 100%; border-collapse: collapse; background: var(--white); }
        th { padding: 12px 16px; background: var(--gray-50); font-weight: 600; text-align: left; font-size: 0.8125rem; color: var(--gray-500); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--gray-200); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--gray-50); }

        /* UTILITIES */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-muted  { color: var(--gray-500); }
        .text-sm     { font-size: 0.8125rem; }
        .text-xs     { font-size: 0.75rem; }
        .font-bold   { font-weight: 700; }
        .font-medium { font-weight: 500; }
        .mt-1 { margin-top: 4px; } .mt-2 { margin-top: 8px; } .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; } .mt-6 { margin-top: 24px; }
        .mb-1 { margin-bottom: 4px; } .mb-2 { margin-bottom: 8px; } .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 12px; } .gap-4 { gap: 16px; }
        .flex { display: flex; } .items-center { align-items: center; } .justify-between { justify-content: space-between; }
        .flex-wrap { flex-wrap: wrap; } .flex-1 { flex: 1; }
        .grid { display: grid; }
        .hidden { display: none; }
        .w-full { width: 100%; }

        /* STAT CARD */
        .stat-card {
            background: var(--white); border-radius: var(--radius-lg);
            padding: 20px; border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);
        }
        .stat-card .stat-value { font-size: 1.875rem; font-weight: 800; color: var(--gray-900); line-height: 1.1; }
        .stat-card .stat-label { font-size: 0.8125rem; color: var(--gray-500); margin-top: 4px; font-weight: 500; }
        .stat-card .stat-icon {
            width: 44px; height: 44px; border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
        }

        /* PAGINATION */
        .pagination { display: flex; gap: 4px; flex-wrap: wrap; }
        .pagination .page-item a, .pagination .page-item span {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: var(--radius-sm);
            font-size: 0.875rem; font-weight: 500; border: 1px solid var(--gray-200);
            color: var(--gray-600); background: var(--white); text-decoration: none;
        }
        .pagination .page-item.active span { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .page-item a:hover { background: var(--primary-light); color: var(--primary); }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 48px 24px; color: var(--gray-400); }
        .empty-state .empty-icon { font-size: 3rem; margin-bottom: 16px; }
        .empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--gray-600); margin-bottom: 8px; }
        .empty-state p  { font-size: 0.875rem; }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
