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
        "surface-container-highest": "#d3e4fe",
        "on-primary-fixed": "#0f0069",
        "secondary": "#006c49",
        "surface-container": "#e5eeff",
        "on-secondary-fixed-variant": "#005236",
        "error-container": "#ffdad6",
        "inverse-primary": "#c3c0ff",
        "on-tertiary-container": "#ffd4a4",
        "outline-variant": "#c7c4d8",
        "surface-container-high": "#dce9ff",
        "primary-container": "#4f46e5",
        "primary": "#3525cd",
        "background": "#f8f9ff",
        "on-secondary-container": "#00714d",
        "tertiary-fixed-dim": "#ffb95f",
        "tertiary": "#684000",
        "primary-fixed": "#e2dfff",
        "on-secondary-fixed": "#002113",
        "on-primary-container": "#dad7ff",
        "secondary-container": "#6cf8bb",
        "surface": "#f8f9ff",
        "surface-bright": "#f8f9ff",
        "on-surface": "#0b1c30",
        "on-primary": "#ffffff",
        "outline": "#777587",
        "on-tertiary-fixed": "#2a1700",
        "error": "#ba1a1a",
        "on-tertiary": "#ffffff",
        "tertiary-container": "#885500",
        "primary-fixed-dim": "#c3c0ff",
        "on-error": "#ffffff",
        "secondary-fixed-dim": "#4edea3",
        "surface-container-lowest": "#ffffff",
        "on-error-container": "#93000a",
        "on-secondary": "#ffffff",
        "surface-dim": "#cbdbf5",
        "on-background": "#0b1c30",
        "surface-variant": "#d3e4fe",
        "on-tertiary-fixed-variant": "#653e00",
        "surface-container-low": "#eff4ff",
        "surface-tint": "#4d44e3",
        "inverse-on-surface": "#eaf1ff",
        "secondary-fixed": "#6ffbbe",
        "tertiary-fixed": "#ffddb8",
        "on-primary-fixed-variant": "#3323cc",
        "on-surface-variant": "#464555",
        "inverse-surface": "#213145"
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "2xl": "1rem",
        "full": "9999px"
      },
      spacing: {
        "space-xl": "2rem",
        "space-lg": "1.5rem",
        "gutter": "1rem",
        "margin": "1.5rem",
        "space-xs": "0.25rem",
        "margin-mobile": "1rem",
        "space-sm": "0.5rem",
        "gutter-mobile": "0.75rem",
        "space-md": "1rem"
      },
      fontFamily: {
        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
        "body-lg": ["Inter", "sans-serif"],
        "label-md": ["Inter", "sans-serif"],
        "body-md": ["Inter", "sans-serif"],
        "label-lg": ["Inter", "sans-serif"],
        "headline-md-mobile": ["Plus Jakarta Sans", "sans-serif"],
        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
        "body-sm": ["Inter", "sans-serif"],
        "label-sm": ["Inter", "sans-serif"],
        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
        "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"]
      }
    }
  }
};
</script>
<style>
    body {
        background-color: #f8f9ff !important;
        font-family: 'Inter', sans-serif !important;
        color: #0b1c30 !important;
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
</style>
@stack('mobile-styles')
@endpush

@section('content')
<div class="min-h-screen flex flex-col items-center justify-start bg-surface font-body-md text-on-surface antialiased">
    <div class="w-full max-w-[480px] min-h-screen flex flex-col relative bg-surface">
        
        <!-- Top Header -->
        <header class="fixed top-0 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl border-b border-surface-container shadow-[0_1px_8px_rgba(0,0,0,0.04)] pt-safe">
            <div class="max-w-[480px] mx-auto h-16 px-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5 min-w-0">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 text-inherit no-underline">
                        <div class="w-9 h-9 rounded-xl bg-primary text-on-primary flex items-center justify-center font-bold text-lg shadow-sm">
                            E
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-headline-sm text-sm font-bold text-on-surface truncate leading-tight">EduExam</span>
                            <span class="text-[11px] text-on-surface-variant leading-none font-medium">SMA Nusantara</span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @include('partials.notification-dropdown')
                    
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col w-full pt-16 pb-24 px-4 bg-surface">
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

        <!-- Bottom Navigation -->
        <nav class="bottom-nav fixed bottom-0 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl border-t border-surface-container shadow-[0_-1px_12px_rgba(11,28,48,0.06)] pb-safe">
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
@endsection
