@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "on-primary-container": "#c1beff",
        "tertiary-container": "#425268",
        "on-tertiary-container": "#b5c5df",
        "error-container": "#ffdad6",
        "on-secondary-fixed": "#171c25",
        "on-secondary-fixed-variant": "#424751",
        "background": "#f9f9ff",
        "inverse-primary": "#c3c0ff",
        "surface-container-highest": "#d8e3fb",
        "surface-tint": "#5148d7",
        "on-background": "#111c2d",
        "secondary-fixed-dim": "#c2c6d3",
        "on-error": "#ffffff",
        "surface-variant": "#d8e3fb",
        "on-primary-fixed-variant": "#372abf",
        "on-surface": "#111c2d",
        "surface": "#f9f9ff",
        "on-primary": "#ffffff",
        "inverse-surface": "#263143",
        "secondary-container": "#dee2ef",
        "surface-container": "#e7eeff",
        "on-error-container": "#93000a",
        "surface-bright": "#f9f9ff",
        "primary": "#2a14b4",
        "surface-container-lowest": "#ffffff",
        "on-tertiary-fixed-variant": "#38485d",
        "primary-fixed-dim": "#c3c0ff",
        "tertiary-fixed": "#d3e4fe",
        "outline": "#777586",
        "primary-fixed": "#e3dfff",
        "surface-container-high": "#dee8ff",
        "on-surface-variant": "#464554",
        "tertiary-fixed-dim": "#b7c8e1",
        "primary-container": "#4338ca",
        "surface-container-low": "#f0f3ff",
        "surface-dim": "#cfdaf2",
        "error": "#ba1a1a",
        "secondary": "#5a5e69",
        "on-tertiary": "#ffffff",
        "on-tertiary-fixed": "#0b1c30",
        "outline-variant": "#c7c4d7",
        "inverse-on-surface": "#ecf1ff",
        "tertiary": "#2b3b50",
        "on-primary-fixed": "#100069",
        "secondary-fixed": "#dee2ef",
        "on-secondary-container": "#60646f",
        "on-secondary": "#ffffff"
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "2xl": "1rem",
        "full": "9999px"
      },
      spacing: {
        "space-xs": "0.25rem",
        "space-sm": "0.5rem",
        "space-md": "1rem",
        "space-lg": "1.5rem",
        "space-xl": "2rem",
        "gutter-sm": "1rem",
        "gutter": "1.5rem",
        "margin-sm": "1rem",
        "margin": "2rem",
        "margin-mobile": "1rem",
        "gutter-mobile": "0.75rem"
      },
      fontFamily: {
        "headline-lg": ["Inter", "sans-serif"],
        "body-lg": ["Inter", "sans-serif"],
        "label-md": ["Inter", "sans-serif"],
        "body-md": ["Inter", "sans-serif"],
        "label-lg": ["Inter", "sans-serif"],
        "headline-md": ["Inter", "sans-serif"],
        "body-sm": ["Inter", "sans-serif"],
        "label-sm": ["Inter", "sans-serif"],
        "headline-sm": ["Inter", "sans-serif"]
      }
    }
  }
};
</script>
<style>
    body {
        background-color: #f9f9ff;
        font-family: 'Inter', sans-serif !important;
        color: #111c2d;
    }
    html.dark body {
        background-color: #0b1329 !important;
        color: #f1f5f9 !important;
    }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        vertical-align: middle;
        line-height: 1;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* ════════════ DARK MODE REFINEMENTS FOR SISWA ════════════ */
    html.dark .bg-surface {
        background-color: #0b1329 !important;
    }
    html.dark .bg-surface\/80 {
        background-color: rgba(11, 19, 41, 0.85) !important;
    }
    html.dark .bg-surface\/90 {
        background-color: rgba(11, 19, 41, 0.92) !important;
    }
    html.dark .bg-surface-container-lowest {
        background-color: #111c38 !important;
    }
    html.dark .bg-surface-container-lowest\/50 {
        background-color: rgba(17, 28, 56, 0.5) !important;
    }
    html.dark .bg-surface-container-low {
        background-color: #162447 !important;
    }
    html.dark .bg-surface-container-low\/50 {
        background-color: rgba(22, 36, 71, 0.5) !important;
    }
    html.dark .bg-surface-container {
        background-color: #1a2b54 !important;
    }
    html.dark .bg-surface-container-high {
        background-color: #213564 !important;
    }
    html.dark .bg-surface-container-highest {
        background-color: #283e74 !important;
    }
    html.dark .text-on-surface {
        color: #f8fafc !important;
    }
    html.dark .text-on-surface-variant {
        color: #94a3b8 !important;
    }
    html.dark .text-primary {
        color: #818cf8 !important;
    }
    html.dark .text-primary-container {
        color: #a5b4fc !important;
    }
    html.dark .border-surface-container {
        border-color: #1e293b !important;
    }
    html.dark .border-surface-container-high {
        border-color: #1e293b !important;
    }
    html.dark .border-surface-container-low\/80 {
        border-color: rgba(30, 41, 59, 0.8) !important;
    }
    html.dark aside {
        background-color: #111c38 !important;
        border-color: #1e293b !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
    }
    html.dark header {
        background-color: rgba(11, 19, 41, 0.85) !important;
        border-color: #1e293b !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    html.dark .bottom-nav {
        background-color: rgba(11, 19, 41, 0.95) !important;
        border-top-color: #1e293b !important;
        box-shadow: 0 -1px 16px rgba(0, 0, 0, 0.6) !important;
    }
    html.dark .bottom-nav a.text-primary {
        color: #818cf8 !important;
    }
    html.dark aside nav a.bg-surface-container-high {
        background-color: #1f325c !important;
        color: #a5b4fc !important;
    }
    html.dark .border-\[\#E3E8F5\],
    html.dark .border-\[\#e3e8f5\] {
        border-color: #1e293b !important;
    }
    html.dark .bg-\[\#EEF2FF\],
    html.dark .bg-\[\#eef2ff\] {
        background-color: rgba(30, 27, 75, 0.45) !important;
    }
    html.dark .border-\[\#C7D2FE\],
    html.dark .border-\[\#c7d2fe\] {
        border-color: #3730a3 !important;
    }
    html.dark .text-\[\#1E1B4B\],
    html.dark .text-\[\#1e1b4b\] {
        color: #e0e7ff !important;
    }
    html.dark .text-\[\#3730A3\],
    html.dark .text-\[\#3730a3\] {
        color: #c7d2fe !important;
    }
    html.dark .bg-\[\#ECFDF5\],
    html.dark .bg-\[\#ecfdf5\] {
        background-color: rgba(6, 78, 59, 0.35) !important;
    }
    html.dark .text-\[\#047857\],
    html.dark .text-\[\#059669\],
    html.dark .text-\[\#10B981\],
    html.dark .text-\[\#10b981\] {
        color: #34d399 !important;
    }
    html.dark .border-\[\#A7F3D0\]\/60 {
        border-color: rgba(5, 150, 105, 0.4) !important;
    }
    html.dark .bg-\[\#FEF2F2\],
    html.dark .bg-\[\#fef2f2\] {
        background-color: rgba(127, 29, 29, 0.35) !important;
    }
    html.dark .bg-\[\#FFFBEB\],
    html.dark .bg-\[\#fffbeb\] {
        background-color: rgba(120, 53, 15, 0.35) !important;
    }
    html.dark .text-\[\#92400E\],
    html.dark .text-\[\#92400e\] {
        color: #fcd34d !important;
    }
    html.dark .text-\[\#B45309\],
    html.dark .text-\[\#b45309\] {
        color: #fde68a !important;
    }
    html.dark .border-amber-200\/50 {
        border-color: rgba(245, 158, 11, 0.3) !important;
    }
    html.dark .bg-\[\#F8FAFC\],
    html.dark .bg-\[\#f8fafc\] {
        background-color: #162447 !important;
    }
    html.dark .border-\[\#E2E8F0\],
    html.dark .border-\[\#e2e8f0\] {
        border-color: #1e293b !important;
    }
    html.dark input,
    html.dark select,
    html.dark textarea {
        color: #f1f5f9;
    }
    html.dark input::placeholder,
    html.dark textarea::placeholder {
        color: #64748b;
    }
    html.dark .text-slate-900,
    html.dark .text-slate-800,
    html.dark .text-slate-700 {
        color: #f8fafc !important;
    }
    html.dark .text-slate-600,
    html.dark .text-slate-500 {
        color: #94a3b8 !important;
    }
    html.dark .text-slate-400 {
        color: #64748b !important;
    }
    html.dark .text-slate-300 {
        color: #475569 !important;
    }
    html.dark .bg-slate-100,
    html.dark .bg-slate-50 {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
    }
    html.dark .bg-slate-200 {
        background-color: #334155 !important;
    }
    html.dark .border-slate-200,
    html.dark .border-slate-100 {
        border-color: #1e293b !important;
    }
    html.dark .shadow-sm {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.4), 0 1px 2px -1px rgba(0, 0, 0, 0.4) !important;
    }
</style>
@stack('mobile-styles')
@endpush

@section('content')
<div class="min-h-screen bg-surface font-body-md text-on-surface antialiased flex">

    <!-- ═══ DESKTOP & TABLET FIXED SIDEBAR (>= md) ═══ -->
    <aside class="hidden md:flex fixed left-0 top-0 h-screen w-[245px] bg-surface-container-lowest z-50 flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] border-r border-[#E3E8F5]">
        <div class="flex flex-col">
            <!-- Brand -->
             <div class="sidebar-brand-full h-20 px-space-lg flex items-center justify-between bg-surface-container-low/50 shrink-0 border-b border-surface-container-low/80">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-space-sm no-underline min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-md flex-shrink-0">
                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>

                    <div class="flex flex-col ml-space-xs min-w-0">
                        <div class="flex items-center gap-space-xs">
                            <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight font-bold">EduExam</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    </div>
                </a>
                <div class="flex items-center gap-1">
                    
                    <button type="button" class="lg:hidden p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container cursor-pointer" onclick="toggleSidebar()" title="Tutup Menu">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
            </div>
            <div class="h-px mx-space-lg bg-surface-container-high"></div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-1 p-space-md">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-surface-container-high text-primary-container font-semibold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('student.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">home</span>
                    <span class="text-sm font-medium">Beranda</span>
                </a>
                <a href="{{ route('student.dashboard') }}#daftar-ujian" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.exam.*') ? 'bg-surface-container-high text-primary-container font-semibold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('student.exam.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">assignment</span>
                    <span class="text-sm font-medium">Ujian</span>
                </a>
                <a href="{{ route('student.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.history') ? 'bg-surface-container-high text-primary-container font-semibold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('student.history') ? "font-variation-settings: 'FILL' 1;" : '' }}">history</span>
                    <span class="text-sm font-medium">Riwayat</span>
                </a>
                <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('student.profile') ? 'bg-surface-container-high text-primary-container font-semibold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('student.profile') ? "font-variation-settings: 'FILL' 1;" : '' }}">person</span>
                    <span class="text-sm font-medium">Profil</span>
                </a>
            </nav>
        </div>

        <!-- Aside Bottom Section -->
        <div class="p-space-md flex flex-col gap-2.5">
            <!-- Theme Toggle in Sidebar -->
            <button type="button" 
                    onclick="toggleTheme()" 
                    class="theme-toggle-btn w-full flex items-center justify-between px-3 py-2 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors border border-surface-container cursor-pointer text-xs font-medium"
                    title="Alihkan tema gelap/terang">
                <div class="flex items-center gap-2">
                    <span class="theme-moon-icon material-symbols-outlined text-[18px]">dark_mode</span>
                    <span class="theme-sun-icon material-symbols-outlined text-[18px] text-amber-400 hidden">light_mode</span>
                    <span>Tema Tampilan</span>
                </div>
                <span class="theme-mode-label text-[11px] font-semibold text-primary">Ubah</span>
            </button>

            <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-2 border border-surface-container">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[18px]">help</span>
                    <span class="text-xs font-semibold text-on-surface">Butuh Bantuan?</span>
                </div>
                <a href="{{ route('student.profile') }}" class="text-[11px] text-primary hover:underline font-medium">Pusat Bantuan</a>
            </div>
        </div>
    </aside>

    <!-- ═══ MAIN WRAPPER ═══ -->
    <div class="w-full md:pl-[245px] flex flex-col min-h-screen transition-all">
        
        <!-- Header -->
        <header class="fixed top-0 left-0 md:left-[245px] right-0 h-16 bg-surface/80 backdrop-blur-xl z-40 border-b border-[#E3E8F5] shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
            <div class="h-16 w-full px-4 md:px-space-lg flex items-center justify-between">
                <!-- Title & Date on Desktop/Tablet -->
                <div class="hidden md:flex flex-col">
                    <span class="font-headline-sm text-base font-bold text-on-surface leading-tight">
                        @yield('page_title', 'Beranda')
                    </span>
                    <span class="font-label-sm text-xs text-on-surface-variant leading-tight">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <!-- Brand on Mobile -->
                <div class="flex md:hidden items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-md flex-shrink-0">
                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-headline-sm text-sm font-bold text-on-surface truncate leading-tight">EduExam</span>
                        <span class="text-[11px] text-on-surface-variant leading-none font-medium truncate">{{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    </div>
                </div>

                <!-- Right Header -->
                <div class="flex items-center gap-2 md:gap-space-md">
                    <!-- Search Bar (Desktop / Tablet) -->
                    <form action="{{ request()->routeIs('student.history') ? route('student.history') : route('student.dashboard') }}" method="GET" class="hidden md:flex items-center relative" id="student-search-form">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-lowest text-on-surface-variant shadow-[0_1px_2px_rgba(0,0,0,0.02)] border border-[#E3E8F5] focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 transition-all duration-150 w-52 lg:w-64">
                            <button type="submit" class="flex items-center justify-center p-0 text-on-surface-variant hover:text-primary transition-colors cursor-pointer" title="Cari ujian">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                            </button>
                            <input 
                                type="text" 
                                name="search" 
                                id="student-search-input"
                                value="{{ request('search') ?? request('q') }}" 
                                placeholder="Cari ujian atau materi..." 
                                autocomplete="off"
                                class="w-full text-xs bg-transparent border-0 outline-none text-on-surface placeholder:text-on-surface-variant p-0 focus:ring-0"
                            >
                            @if(request('search') || request('q'))
                                <a href="{{ request()->routeIs('student.history') ? route('student.history') : route('student.dashboard') }}" class="text-on-surface-variant hover:text-error flex items-center justify-center p-0.5 rounded transition-colors" title="Hapus pencarian">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Search Button (Mobile Toggle) -->
                    <button type="button" onclick="toggleMobileSearch()" class="flex md:hidden p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" title="Cari ujian">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </button>

                    <!-- Dark / Light Mode Toggle Button -->
                    <button type="button" 
                            id="themeToggleBtn" 
                            class="theme-toggle-btn w-9 h-9 md:w-10 md:h-10 rounded-xl bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer focus:outline-none border border-transparent hover:border-surface-container shadow-xs shrink-0" 
                            aria-label="Alihkan tema gelap/terang"
                            title="Alihkan mode tema">
                        <span id="themeMoonIcon" class="theme-moon-icon material-symbols-outlined text-[20px] md:text-[22px]">dark_mode</span>
                        <span id="themeSunIcon" class="theme-sun-icon material-symbols-outlined text-[20px] md:text-[22px] text-amber-400 hidden">light_mode</span>
                    </button>

                    @include('partials.notification-dropdown')
                </div>
            </div>

            <!-- Mobile Search Bar (Expandable) -->
            <div id="mobile-search-bar" class="{{ (request('search') || request('q')) ? '' : 'hidden' }} md:hidden px-4 py-2 bg-surface-container-lowest border-t border-[#E3E8F5] shadow-sm">
                <form action="{{ request()->routeIs('student.history') ? route('student.history') : route('student.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <div class="flex-1 flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface text-on-surface-variant border border-[#E3E8F5] focus-within:border-primary">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <input 
                            type="text" 
                            name="search" 
                            id="mobile-search-input"
                            value="{{ request('search') ?? request('q') }}" 
                            placeholder="Cari ujian atau materi..." 
                            autocomplete="off"
                            class="w-full text-xs bg-transparent border-0 outline-none text-on-surface placeholder:text-on-surface-variant p-0 focus:ring-0"
                        >
                        @if(request('search') || request('q'))
                            <a href="{{ request()->routeIs('student.history') ? route('student.history') : route('student.dashboard') }}" class="text-on-surface-variant hover:text-error flex items-center justify-center p-0.5" title="Hapus pencarian">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs font-semibold shadow-sm">
                        Cari
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="relative pt-16 w-full px-4 md:px-space-lg bg-surface min-h-screen pb-24 md:pb-10">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mt-3 p-3 bg-secondary-container/40 border border-secondary/30 rounded-xl text-secondary text-sm flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-secondary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mt-3 p-3 bg-error-container/50 border border-error/30 rounded-xl text-error text-sm flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-error" style="font-variation-settings: 'FILL' 1;">error</span>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mt-3 p-3 bg-surface-container border border-primary/20 rounded-xl text-primary text-sm flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-primary" style="font-variation-settings: 'FILL' 1;">info</span>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            @endif

            @yield('student-content')
        </main>

        <!-- Bottom Navigation (Mobile Only, < md) -->
        <nav class="bottom-nav md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl border-t border-surface-container shadow-[0_-1px_12px_rgba(11,28,48,0.06)] pb-safe">
            <div class="max-w-[480px] mx-auto flex items-center justify-around h-16 px-2">
                <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center justify-center min-w-[64px] h-12 transition-colors {{ request()->routeIs('student.dashboard') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[24px]" style="{{ request()->routeIs('student.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">home</span>
                    <span class="text-[11px] font-medium mt-0.5">Beranda</span>
                </a>
                <a href="{{ route('student.dashboard') }}#daftar-ujian" class="flex flex-col items-center justify-center min-w-[64px] h-12 transition-colors {{ request()->routeIs('student.exam.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[24px]" style="{{ request()->routeIs('student.exam.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">assignment</span>
                    <span class="text-[11px] font-medium mt-0.5">Ujian</span>
                </a>
                <a href="{{ route('student.history') }}" class="flex flex-col items-center justify-center min-w-[64px] h-12 transition-colors {{ request()->routeIs('student.history') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[24px]" style="{{ request()->routeIs('student.history') ? "font-variation-settings: 'FILL' 1;" : '' }}">history_edu</span>
                    <span class="text-[11px] font-medium mt-0.5">Riwayat</span>
                </a>
                <a href="{{ route('student.profile') }}" class="flex flex-col items-center justify-center min-w-[64px] h-12 transition-colors {{ request()->routeIs('student.profile') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[24px]" style="{{ request()->routeIs('student.profile') ? "font-variation-settings: 'FILL' 1;" : '' }}">person</span>
                    <span class="text-[11px] font-medium mt-0.5">Profil</span>
                </a>
            </div>
        </nav>
    </div>
</div>

<script>
    function toggleMobileSearch() {
        const bar = document.getElementById('mobile-search-bar');
        if (bar) {
            bar.classList.toggle('hidden');
            if (!bar.classList.contains('hidden')) {
                const input = document.getElementById('mobile-search-input');
                if (input) input.focus();
            }
        }
    }
</script>
@endsection
