@extends('layouts.app')

@section('title', 'Masuk — CBT EduExam ' . ($schoolName ?? \App\Models\SchoolSetting::get('school_name', '')))

@push('styles')
<style>
    /* ===== WRAPPER ===== */
    .login-wrapper { min-height: 100vh; }
    .login-box { max-width: 1100px; }

    @media (min-width: 1024px) {
        .left-col  { flex: 1.15; }
        .right-col { flex: 0.85; min-width: 420px; }
    }

    /* ========================================================= */
    /* ===== LEFT PANEL — HORIZONTAL SPLIT GRADIENT ============ */
    /* ===== DIPER GELAP: sisi kiri lebih gelap ================ */
    /* ========================================================= */
    .left-overlay {
        background: linear-gradient(
            to right,
            rgba(8, 15, 45, 0.98) 0%,
            rgba(10, 20, 60, 0.95) 20%,
            rgba(15, 35, 95, 0.85) 40%,
            rgba(20, 55, 140, 0.65) 60%,
            rgba(35, 80, 180, 0.35) 80%,
            rgba(59, 130, 246, 0.08) 100%
        );
    }

    /* ===== LOGO ICON (RIGHT PANEL) ===== */
    .logo-icon-right {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.25);
        margin: 0 auto 12px;
    }

    /* ===== LOGO ICON (LEFT PANEL — TOP BAR) — BIRU ===== */
    .logo-icon-left {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(59, 130, 246, 0.4);
    }

    /* ===== BUTTON PRIMARY ===== */
    .btn-masuk {
        background: #1d6fe9;
        background: linear-gradient(135deg, #1a62d6, #2563eb);
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
    }
    .btn-masuk:hover {
        background: linear-gradient(135deg, #174eb2, #1d4ed8);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.38);
    }

    /* ========================================================= */
    /* ===== FEATURE CARD ICONS — SEMUA BIRU ==================== */
    /* ========================================================= */
    .feature-card-icon-1,
    .feature-card-icon-2,
    .feature-card-icon-3 {
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.35);
    }

    /* ===== RIGHT PANEL — TERANG ===== */
    .right-panel-bg {
        background:
            radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.08), transparent 45%),
            radial-gradient(circle at 80% 80%, rgba(14, 165, 233, 0.06), transparent 45%),
            #f8fafc;
    }

    /* ========================================================= */
    /* ===== SHADOW TEKS DI PANEL KIRI ========================== */
    /* ========================================================= */
    .left-col h2,
    .left-col h4 {
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.7));
    }
    .left-col p {
        filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.6));
    }

    /* ========================================================= */
    /* ===== GRADIENT TEXT "EduExam" — BIRU SEPERTI TOMBOL ====== */
    /* ========================================================= */
    .text-gradient-blue {
        background: linear-gradient(to right, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
        display: block;
        filter: none !important;
        text-shadow: none !important;
    }

    /* ========================================================= */
    /* ===== WAVE DIVIDER BIRU TRANSPARAN — TINGGI ============== */
    /* ========================================================= */
    .wave-container {
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 130px;
        z-index: 2;
        pointer-events: none;
        overflow: hidden;
    }

    .wave-svg {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
        display: block;
    }

    .wave-layer-1 {
        animation: waveMove1 14s linear infinite;
        opacity: 0.5;
    }

    .wave-layer-2 {
        animation: waveMove2 9s linear infinite;
        opacity: 0.8;
    }

    @keyframes waveMove1 {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    @keyframes waveMove2 {
        0%   { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }

    /* ========================================================= */
    /* ===== MOBILE SPECIFIC STYLES (Ref Design) =============== */
    /* ========================================================= */
    @media (max-width: 1023px) {
        .mobile-header-bg {
            background-color: #dceaf8;
            background-image: 
                radial-gradient(circle at 10% 20%, #eff6ff 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, #dbeafe 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, #bfdbfe 0%, transparent 70%);
        }
        .mobile-card-shadow {
            box-shadow: 0 -12px 30px -4px rgba(15, 23, 42, 0.08), 0 -4px 12px -2px rgba(15, 23, 42, 0.04);
        }
    }
</style>
@endpush

@section('content')
{{-- =============================================================== --}}
{{-- 1. MOBILE VIEW (< lg): Strict match with Reference Image       --}}
{{-- =============================================================== --}}
<div class="lg:hidden min-h-screen flex flex-col mobile-header-bg">
    {{-- Top Section: Logo Header & Exam Illustration --}}
    <div class="relative w-full pt-6 pb-0 flex flex-col items-center overflow-hidden">
        {{-- App Header Branding --}}
        <div class="text-center z-10 px-4 mb-1">
            <div class="flex items-center justify-center gap-2 mb-1">
                {{-- Logo Shield Icon matching reference --}}
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-700 to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/30">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">local_library</span>
                </div>
                <h1 class="font-headline text-[22px] font-black text-slate-900 tracking-tight">EduExam</h1>
            </div>
            <p class="text-[11px] font-medium text-slate-500 leading-tight">Portal Ujian Digital</p>
            <p class="text-[11px] font-bold text-slate-800 leading-tight mt-0.5">
                {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}
            </p>
        </div>

        {{-- Illustration of students doing exam (Stretches edge-to-edge across screen) --}}
        <div class="w-full relative z-10 flex justify-center -mb-5">
            <img src="{{ asset('images/mobile_exam_hero_full.png') }}"
                 alt="Siswa Ujian EduExam"
                 class="w-full h-auto object-cover object-bottom select-none pointer-events-none">
        </div>
    </div>

    {{-- Bottom White Sheet Form --}}
    <div class="flex-1 bg-white rounded-t-[32px] mobile-card-shadow px-6 pt-6 pb-6 flex flex-col justify-between z-20">
        <div>
            {{-- Title & Subtitle --}}
            <div class="mb-5">
                <h2 class="font-headline text-[19px] font-bold text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
                <p class="text-[12px] text-slate-500 mt-1">Silakan login untuk melanjutkan ke halaman sistem.</p>
            </div>

            {{-- Alerts --}}
            @if(session('error'))
                <div class="flex items-center gap-2 p-3 mb-4 bg-red-50 text-red-600 text-[12px] rounded-xl border border-red-200">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="flex items-center gap-2 p-3 mb-4 bg-green-50 text-green-600 text-[12px] rounded-xl border border-green-200">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form Login Mobile --}}
            <form method="POST" action="{{ route('login.submit') }}" id="mobileLoginForm" class="flex flex-col gap-3.5">
                @csrf

                {{-- Input Username / Email --}}
                <div>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 text-slate-400 pointer-events-none flex items-center">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <input type="text" name="login" id="mobile_login" value="{{ old('login') }}"
                               required autofocus autocomplete="username"
                               placeholder="Email atau NIP / NISN"
                               class="w-full h-12 pl-11 pr-4 bg-white text-slate-900 text-sm rounded-xl placeholder:text-slate-400 border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all duration-200 @error('login') border-red-400 @enderror">
                    </div>
                    @error('login')
                        <p class="text-[11px] text-red-500 mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Password --}}
                <div>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 text-slate-400 pointer-events-none flex items-center">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input type="password" name="password" id="mobile_password"
                               required autocomplete="current-password"
                               placeholder="Password"
                               class="w-full h-12 pl-11 pr-11 bg-white text-slate-900 text-sm rounded-xl placeholder:text-slate-400 border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all duration-200 @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePasswordMobile()"
                                class="absolute right-3 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-lg transition-colors"
                                aria-label="Toggle password">
                            <span class="material-symbols-outlined text-[20px]" id="mobile-eye-icon">visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-red-500 mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me & Forgot Password --}}
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-blue-600 bg-slate-50 focus:ring-0 cursor-pointer border-slate-300">
                        <span class="text-[13px] text-slate-600">Ingat saya</span>
                    </label>
                    <a href="javascript:void(0)" onclick="openHelpModal()"
                       class="text-[13px] font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        Lupa password?
                    </a>
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit" id="mobileLoginBtn"
                        class="w-full h-12 mt-1.5 btn-masuk text-white font-headline text-[15px] font-semibold rounded-xl active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[19px]">arrow_forward</span>
                    <span id="mobileBtnText">Masuk</span>
                </button>
            </form>

            {{-- Divider atau --}}
            <div class="flex items-center gap-3 my-4 text-slate-400 text-xs">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="font-normal text-slate-400">atau</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            {{-- Akun Percobaan (Quick Demo) --}}
            <div class="flex items-center justify-center gap-2">
                <button type="button" onclick="fillCredsMobile('siswa', 'password123')"
                        class="px-3.5 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                    Siswa
                </button>
                <button type="button" onclick="fillCredsMobile('guru', 'password123')"
                        class="px-3.5 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                    Guru
                </button>
                <button type="button" onclick="fillCredsMobile('admin', 'password123')"
                        class="px-3.5 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                    Admin
                </button>
            </div>
        </div>

        {{-- Footer Mobile Hubungi Admin --}}
        <div class="text-center mt-6 pt-2">
            <p class="text-xs text-slate-500">Belum punya akun?</p>
            <a href="javascript:void(0)" onclick="openHelpModal()" class="text-xs font-semibold text-blue-600 hover:underline mt-0.5 inline-block">
                Hubungi admin sekolah
            </a>
        </div>
    </div>
</div>

{{-- =============================================================== --}}
{{-- 2. DESKTOP VIEW (hidden lg:flex): Unchanged Original Desktop   --}}
{{-- =============================================================== --}}
<div class="hidden lg:flex login-wrapper items-center justify-center bg-surface p-6">
    <div class="login-box w-full flex flex-row rounded-3xl overflow-hidden shadow-2xl">

        {{-- ===== LEFT PANEL ===== --}}
        <div class="left-col relative flex flex-col justify-between p-8 overflow-hidden">

            {{-- Background Image --}}
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/hero_students.jpg') }}"
                    alt="Siswa Ujian"
                    class="w-full h-full object-cover">
            </div>

            {{-- Split Gradient Overlay (LEBIH GELAP) --}}
            <div class="absolute inset-0 z-[1] left-overlay"></div>

            {{-- ===== WAVE DIVIDER BIRU TRANSPARAN — TINGGI ===== --}}
            <div class="wave-container">
                {{-- Layer 1 (belakang) --}}
                <svg class="wave-svg wave-layer-1" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="waveGrad1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%"   stop-color="#3b82f6" stop-opacity="0.55"/>
                            <stop offset="50%"  stop-color="#60a5fa" stop-opacity="0.35"/>
                            <stop offset="100%" stop-color="#93c5fd" stop-opacity="0.15"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#waveGrad1)"
                        d="M0,64L60,69.3C120,75,240,85,360,80C480,75,600,53,720,48C840,43,960,53,1080,64C1200,75,1320,85,1380,90.7L1440,96L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
                </svg>

                {{-- Layer 2 (depan) --}}
                <svg class="wave-svg wave-layer-2" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="waveGrad2" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%"   stop-color="#1d4ed8" stop-opacity="0.85"/>
                            <stop offset="45%"  stop-color="#3b82f6" stop-opacity="0.55"/>
                            <stop offset="100%" stop-color="#93c5fd" stop-opacity="0.00"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#waveGrad2)"
                        d="M0,96L48,90.7C96,85,192,75,288,69.3C384,64,480,64,576,69.3C672,75,768,85,864,85.3C960,85,1056,75,1152,69.3C1248,64,1344,64,1392,64L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"/>
                </svg>
            </div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between h-full">

                {{-- ===== TOP BAR ===== --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="logo-icon-left">
                            <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
                        </div>
                        <div>
                            <h2 class="font-headline text-lg font-extrabold text-white tracking-tight leading-tight">EduExam</h2>
                            <p class="text-[11px] text-white/70 leading-tight">Ujian Lebih Mudah, Masa Depan Lebih Cerah</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-white">
                        <span class="material-symbols-outlined text-[22px]">
                            school
                        </span>
                        <span class="text-[11px] text-white leading-relaxed">
                            Portal Ujian Digital<br>
                            <strong class="font-semibold text-white">
                                {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}
                            </strong>
                        </span>
                    </div>
                </div>

                {{-- ===== HERO ===== --}}
                <div class="flex-1 flex flex-col justify-center">
                    <h1 class="font-headline text-3xl lg:text-[2.4rem] font-extrabold leading-[1.15] tracking-tight mb-5">
                        <span class="block text-white" style="filter: drop-shadow(0 2px 6px rgba(0,0,0,0.6));">
                            Selamat Datang di
                        </span>
                        <span class="text-gradient-blue">EduExam</span>
                    </h1>

                    <p class="text-sm text-white leading-relaxed max-w-[400px] mb-8">
                        Platform ujian online yang aman, mudah <br>
                        digunakan, dan terpercaya untuk mendukung <br>
                        proses belajar mengajar di sekolah.
                    </p>

                    {{-- Fitur --}}
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl feature-card-icon-1 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                            </div>
                            <div>
                                <h4 class="font-headline text-[13px] font-bold text-white leading-tight">Aman &amp; Terpercaya</h4>
                                <p class="text-[11px] text-white/70 leading-snug">Data dan proses ujian Anda terlindungi dengan baik.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl feature-card-icon-2 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">public</span>
                            </div>
                            <div>
                                <h4 class="font-headline text-[13px] font-bold text-white leading-tight">Akses Mudah</h4>
                                <p class="text-[11px] text-white/70 leading-snug">Gunakan kapan saja dan di mana saja.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl feature-card-icon-3 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">monitoring</span>
                            </div>
                            <div>
                                <h4 class="font-headline text-[13px] font-bold text-white leading-tight">Hasil Akurat</h4>
                                <p class="text-[11px] text-white/70 leading-snug">Penilaian otomatis dengan sistem modern.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Quote --}}
                <p class="text-xs text-white/50 italic mt-4 pt-3 border-t border-white/10">
                    "Bersama EduExam, wujudkan pendidikan yang lebih baik."
                </p>
            </div>
        </div>

        {{-- ===== RIGHT PANEL ===== --}}
        <div class="right-col right-panel-bg flex flex-col items-center justify-center px-8 py-8 overflow-y-auto">
            <div class="w-full max-w-[380px]">

                {{-- ===== FORM CARD ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 mb-3">

                    {{-- Branding --}}
                    <div class="text-center mb-4">
                        <div class="logo-icon-right">
                            <span class="material-symbols-outlined text-white text-[26px]" style="font-variation-settings: 'FILL' 1;">school</span>
                        </div>
                        <h2 class="font-headline text-xl font-extrabold text-slate-900 tracking-tight">EduExam</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Portal Guru, Siswa, dan Administrator</p>
                        <p class="text-[13px] font-bold text-slate-900 mt-0.5">
                            {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}
                        </p>
                    </div>

                    {{-- Heading --}}
                    <div class="mb-4">
                        <h3 class="font-headline text-base font-extrabold text-slate-900 mb-0.5">Masuk ke Akun Anda</h3>
                        <p class="text-[11px] text-slate-500">Silakan login untuk melanjutkan ke halaman sistem.</p>
                    </div>

                    {{-- Alerts --}}
                    @if(session('error'))
                        <div class="flex items-center gap-2 p-2.5 mb-3 bg-red-50 text-red-600 text-[11px] rounded-xl border border-red-200">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="flex items-center gap-2 p-2.5 mb-3 bg-green-50 text-green-600 text-[11px] rounded-xl border border-green-200">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- ===== FORM ===== --}}
                    <form method="POST" action="{{ route('login.submit') }}" id="loginForm" class="flex flex-col gap-3">
                        @csrf

                        {{-- Login --}}
                        <div>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 text-slate-400 pointer-events-none">
                                    <span class="material-symbols-outlined text-[20px]">person</span>
                                </div>
                                <input type="text" name="login" id="login" value="{{ old('login') }}"
                                       required autofocus autocomplete="username"
                                       placeholder="Email atau NIP / NISN"
                                       class="w-full h-11 pl-10 pr-4 bg-slate-50 text-slate-900 text-sm rounded-xl placeholder:text-slate-400 border border-slate-200 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all duration-200 @error('login') border-red-400 @enderror">
                            </div>
                            @error('login')
                                <p class="text-[11px] text-red-500 mt-1 pl-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 text-slate-400 pointer-events-none">
                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                </div>
                                <input type="password" name="password" id="password"
                                       required autocomplete="current-password"
                                       placeholder="Password"
                                       class="w-full h-11 pl-10 pr-11 bg-slate-50 text-slate-900 text-sm rounded-xl placeholder:text-slate-400 border border-slate-200 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all duration-200 @error('password') border-red-400 @enderror">
                                <button type="button" onclick="togglePassword()"
                                        class="absolute right-2.5 w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-lg transition-colors"
                                        aria-label="Toggle password">
                                    <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility_off</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] text-red-500 mt-1 pl-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember & Forgot --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-blue-600 bg-slate-50 focus:ring-0 cursor-pointer border-slate-300">
                                <span class="text-xs text-slate-600">Ingat saya</span>
                            </label>
                            <a href="javascript:void(0)" onclick="openHelpModal()"
                               class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="loginBtn"
                                class="w-full h-11 mt-0.5 btn-masuk text-white font-headline text-sm font-bold rounded-xl active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                            <span id="btnText">Masuk</span>
                        </button>
                    </form>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-3 text-slate-400 text-[11px]">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span>Akun Percobaan</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                {{-- Demo Accounts --}}
                <div class="flex items-center justify-center gap-2 mb-3">
                    <button type="button" onclick="fillCreds('siswa', 'password123')"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                        Siswa
                    </button>
                    <button type="button" onclick="fillCreds('guru', 'password123')"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                        Guru
                    </button>
                    <button type="button" onclick="fillCreds('admin', 'password123')"
                            class="px-3 py-1.5 rounded-lg bg-white hover:bg-blue-50 text-xs text-blue-600 font-semibold border border-slate-200 hover:border-blue-300 transition-all duration-200">
                        Admin
                    </button>
                </div>

                {{-- Footer --}}
                <p class="text-center text-xs text-slate-500">
                    Belum punya akun?
                    <a href="javascript:void(0)" onclick="openHelpModal()" class="text-blue-600 font-bold hover:underline">Hubungi admin sekolah</a>
                </p>

            </div>
        </div>

    </div>
</div>

{{-- ===== HELP MODAL ===== --}}
<div id="help-modal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="w-full max-w-[400px] bg-white rounded-2xl p-5 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2 font-headline text-sm font-bold text-slate-900">
                <span class="material-symbols-outlined text-blue-600">contact_support</span>
                <span>Bantuan Peserta Ujian</span>
            </div>
            <button type="button" onclick="closeHelpModal()"
                    class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed mb-3">
            Jika Anda mengalami kendala saat masuk akun, kartu peserta hilang, atau kendala teknis:
        </p>

        <div class="bg-slate-50 rounded-xl p-3 flex flex-col gap-2 mb-3">
            <div class="flex items-center gap-2 text-xs text-slate-800">
                <span class="material-symbols-outlined text-[18px] text-blue-600">person_pin</span>
                <span>Posko Lab CBT (Gedung B, Lantai 1)</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-800">
                <span class="material-symbols-outlined text-[18px] text-blue-600">call</span>
                <span>Hotline Operator: 0812-3456-7890</span>
            </div>
        </div>

        <button type="button" onclick="closeHelpModal()"
                class="w-full h-10 btn-masuk text-white font-headline text-xs font-bold rounded-xl transition-all">
            Mengerti, Terima Kasih
        </button>
    </div>
</div>

{{-- ===== SCRIPT ===== --}}
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.textContent = isHidden ? 'visibility' : 'visibility_off';
    }

    function togglePasswordMobile() {
        const input = document.getElementById('mobile_password');
        const icon  = document.getElementById('mobile-eye-icon');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.textContent = isHidden ? 'visibility' : 'visibility_off';
    }

    function openHelpModal() {
        const modal = document.getElementById('help-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeHelpModal() {
        const modal = document.getElementById('help-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function fillCreds(u, p) {
        document.getElementById('login').value = u;
        document.getElementById('password').value = p;
    }

    function fillCredsMobile(u, p) {
        document.getElementById('mobile_login').value = u;
        document.getElementById('mobile_password').value = p;
    }

    document.getElementById('loginForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Memproses...</span>';
    });

    document.getElementById('mobileLoginForm')?.addEventListener('submit', function () {
        const btn = document.getElementById('mobileLoginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Memproses...</span>';
    });
</script>
@endsection