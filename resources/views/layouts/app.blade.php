<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduExam') - Platform Ujian Digital</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#4f46e5',
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            dark: '#3730a3',
                            light: '#eef2ff',
                            border: '#c7d2fe',
                        },
                        secondary: {
                            DEFAULT: '#006c49',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            500: '#10b981',
                            600: '#059669',
                            700: '#006c49',
                            dark: '#005236',
                            light: '#ecfdf5',
                            border: '#a7f3d0',
                        },
                        tertiary: {
                            DEFAULT: '#684000',
                            light: '#fffbeb',
                        },
                        danger: {
                            DEFAULT: '#dc2626',
                            light: '#fef2f2',
                            border: '#fecaca',
                        },
                        surface: '#f8f9ff',
                        background: '#f8f9ff',
                        "outline-variant": "#c7c4d8",
                        "tertiary-container": "#885500",
                        "on-background": "#0b1c30",
                        "on-secondary-fixed": "#002113",
                        "surface-container-high": "#dce9ff",
                        "on-secondary-fixed-variant": "#005236",
                        "inverse-primary": "#c3c0ff",
                        "error-container": "#ffdad6",
                        "on-secondary-container": "#00714d",
                        "on-tertiary-fixed-variant": "#653e00",
                        "on-primary": "#ffffff",
                        "on-surface": "#0b1c30",
                        "secondary-fixed-dim": "#4edea3",
                        "tertiary-fixed-dim": "#ffb95f",
                        "surface-bright": "#f8f9ff",
                        "secondary-container": "#6cf8bb",
                        "surface-container-low": "#eff4ff",
                        "on-tertiary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-highest": "#d3e4fe",
                        "on-surface-variant": "#464555",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#d3e4fe",
                        "on-tertiary-fixed": "#2a1700",
                        "surface-dim": "#cbdbf5",
                        "inverse-on-surface": "#eaf1ff",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#c3c0ff",
                        "on-primary-container": "#dad7ff",
                        "error": "#ba1a1a",
                        "inverse-surface": "#213145",
                        "primary-fixed": "#e2dfff",
                        "surface-container": "#e5eeff",
                        "on-tertiary-container": "#ffd4a4",
                        "secondary-fixed": "#6ffbbe",
                        "tertiary-fixed": "#ffddb8",
                        "outline": "#777587",
                        "primary-container": "#4f46e5",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#3323cc",
                        "on-primary-fixed": "#0f0069",
                        "surface-tint": "#4d44e3"
                    },
                    spacing: {
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-md": "1rem",
                        "space-lg": "1.5rem",
                        "space-xl": "2rem",
                    },
                    fontSize: {
                        "label-sm": ["11px", { lineHeight: "14px", fontWeight: "600" }],
                        "label-md": ["12px", { lineHeight: "16px", fontWeight: "600" }],
                        "label-lg": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }],
                        "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }],
                        "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }],
                        "headline-sm": ["18px", { lineHeight: "24px", fontWeight: "600" }],
                        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
                        "headline-lg": ["32px", { lineHeight: "40px", fontWeight: "700" }],
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        headline: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        'body-sm': ['Inter'],
                        'body-md': ['Inter'],
                        'body-lg': ['Inter'],
                        'label-sm': ['Inter'],
                        'label-md': ['Inter'],
                        'label-lg': ['Inter'],
                        'headline-sm': ['Plus Jakarta Sans'],
                        'headline-md': ['Plus Jakarta Sans'],
                        'headline-lg': ['Plus Jakarta Sans'],
                    },
                    boxShadow: {
                        'card': '0 1px 3px rgba(15,23,42,0.05), 0 1px 2px rgba(15,23,42,0.03)',
                        'card-hover': '0 10px 25px -5px rgba(15,23,42,0.08), 0 8px 10px -6px rgba(15,23,42,0.04)',
                    }
                }
            }
        };
    </script>

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --primary-border: #c7d2fe;
            --secondary: #006c49;
            --secondary-light: #ecfdf5;
            --secondary-border: #a7f3d0;
            --warning: #ea580c;
            --warning-light: #fff7ed;
            --warning-border: #fed7aa;
            --tertiary: #f59e0b;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
            --gray-50: #f8f9ff;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0b1c30;
            --white: #ffffff;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f8f9ff;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        /* TAILWIND COMPONENT CLASSES */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.125rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            white-space: nowrap;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: 1px solid transparent;
        }
        .btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn-primary {
            background-color: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2);
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border-color: #cbd5e1;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .btn-success {
            background-color: #006c49;
            color: #ffffff;
            border-color: #006c49;
        }
        .btn-success:hover {
            background-color: #005236;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 108, 73, 0.3);
        }
        .btn-danger {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.5rem;
        }
        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
            border-radius: 0.875rem;
        }

        /* CARD COMPONENT */
        .card {
            background-color: #ffffff;
            border-radius: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card-header {
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* FORM COMPONENT */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.375rem;
            font-size: 0.8125rem;
            color: #334155;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #0f172a;
            background-color: #ffffff;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .form-error {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.375rem;
            font-weight: 500;
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-primary { background-color: #eef2ff; color: #4f46e5; }
        .badge-success { background-color: #ecfdf5; color: #006c49; }
        .badge-warning { background-color: #fff7ed; color: #ea580c; }
        .badge-danger  { background-color: #fef2f2; color: #dc2626; }
        .badge-gray    { background-color: #f1f5f9; color: #475569; }

        /* ALERTS */
        .alert {
            padding: 0.875rem 1.125rem;
            border-radius: 0.75rem;
            margin-bottom: 1.125rem;
            display: flex;
            gap: 0.75rem;
            align-items: center;
            font-size: 0.875rem;
            border: 1px solid transparent;
        }
        .alert-success { background-color: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .alert-error   { background-color: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .alert-warning { background-color: #fff7ed; color: #9a3412; border-color: #fed7aa; }
        .alert-info    { background-color: #eef2ff; color: #3730a3; border-color: #c7d2fe; }

        /* TABLE WRAP */
        .table-wrap {
            overflow-x: auto;
            border-radius: 1rem;
            background-color: #ffffff;
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            padding: 0.875rem 1.125rem;
            background-color: #f8fafc;
            font-weight: 700;
            text-align: left;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        td {
            padding: 0.875rem 1.125rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: rgba(248, 250, 252, 0.75); }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen">
    @yield('content')
    @stack('scripts')
</body>
</html>
