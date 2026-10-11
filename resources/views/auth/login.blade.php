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
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }
    .btn-masuk:hover {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        box-shadow: 0 6px 24px rgba(59, 130, 246, 0.4);
    }

    /* ========================================================= */
    /* ===== FEATURE CARD ICONS — SEMUA BIRU ==================== */
    /* ========================================================= */
    .feature-card-icon-1 {
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.35);
    }
    .feature-card-icon-2 {
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.35);
    }
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
    /* ⚠️ PENTING: JANGAN kasih filter ke h1 karena akan ======== */
    /* merusak gradient text di dalamnya ======================== */
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
    /* Warna: biru pekat → biru terang (konsisten tombol) ======= */
    /* ========================================================= */
    .text-gradient-blue {
        background: linear-gradient(to right, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
        display: block;
        /* WAJIB: matikan semua shadow/filter agar gradient terlihat */
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
        height: 130px; /* ← TINGGI */
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

    /* Responsive: wave lebih kecil di mobile */
    @media (max-width: 1023px) {
        .wave-container { height: 80px; }
    }
</style>
@endpush

        @section('content')
        <div class="login-wrapper flex items-center justify-center bg-surface p-3 lg:p-6">
            <div class="login-box w-full flex flex-col lg:flex-row rounded-3xl overflow-hidden shadow-2xl">

                {{-- ===== LEFT PANEL ===== --}}
                <div class="left-col relative flex flex-col justify-between p-6 lg:p-8 overflow-hidden">

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

                        {{-- Layer 1 (belakang) — biru muda memudar --}}
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

                        {{-- Layer 2 (depan) — biru lebih pekat lalu memudar --}}
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
                        <div class="flex items-center justify-between mb-4 lg:mb-6">
                            <div class="flex items-center gap-3">
                                {{-- Icon school — background BIRU --}}
                                <div class="logo-icon-left">
                                    <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
                                </div>
                                <div>
                                    <h2 class="font-headline text-lg font-extrabold text-white tracking-tight leading-tight">EduExam</h2>
                                    <p class="text-[11px] text-white/70 leading-tight">Ujian Lebih Mudah, Masa Depan Lebih Cerah</p>
                                </div>
                            </div>

                            <div class="hidden md:flex items-center gap-2 text-white">
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

                            {{-- Judul --}}
                            <h1 class="font-headline text-3xl lg:text-[2.4rem] font-extrabold leading-[1.15] tracking-tight mb-5">
                                {{-- Baris 1: putih solid --}}
                                <span class="block text-white" style="filter: drop-shadow(0 2px 6px rgba(0,0,0,0.6));">
                                    Selamat Datang di
                                </span>
                                {{-- Baris 2: gradient biru SEPERTI TOMBOL --}}
                                <span class="text-gradient-blue">EduExam</span>
                            </h1>

                            {{-- Deskripsi --}}
                            <p class="text-sm text-white leading-relaxed max-w-[400px] mb-8">
                                Platform ujian online yang aman, mudah <br>
                                digunakan, dan terpercaya untuk mendukung <br>
                                proses belajar mengajar di sekolah.
                            </p>

                            {{-- ===== FITUR (TANPA CARD) — SEMUA BIRU ===== --}}
                            <div class="flex flex-col gap-5">

                                {{-- Fitur 1 — Aman & Terpercaya --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl feature-card-icon-1 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                                    </div>
                                    <div>
                                        <h4 class="font-headline text-[13px] font-bold text-white leading-tight">Aman &amp; Terpercaya</h4>
                                        <p class="text-[11px] text-white/70 leading-snug">Data dan proses ujian Anda terlindungi dengan baik.</p>
                                    </div>
                                </div>

                                {{-- Fitur 2 — Akses Mudah --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl feature-card-icon-2 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings: 'FILL' 1;">public</span>
                                    </div>
                                    <div>
                                        <h4 class="font-headline text-[13px] font-bold text-white leading-tight">Akses Mudah</h4>
                                        <p class="text-[11px] text-white/70 leading-snug">Gunakan kapan saja dan di mana saja.</p>
                                    </div>
                                </div>

                                {{-- Fitur 3 — Hasil Akurat --}}
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

                {{-- ===== FOOTER QUOTE ===== --}}
                <p class="hidden lg:block text-xs text-white/50 italic mt-4 pt-3 border-t border-white/10">
                    "Bersama EduExam, wujudkan pendidikan yang lebih baik."
                </p>
            </div>
        </div>

        {{-- ===== RIGHT PANEL ===== --}}
        <div class="right-col right-panel-bg flex flex-col items-center justify-center px-5 py-6 lg:px-8 lg:py-8 overflow-y-auto">
            <div class="w-full max-w-[380px]">

                {{-- ===== FORM CARD ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-5 lg:p-6 mb-3">

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
                        {{-- Divider --}}
                        <div class="flex items-center gap-3 mb-3 text-slate-400 text-[11px]">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="font-medium">atau</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>

                        {{-- Footer --}}
                        <p class="text-center text-xs text-slate-500">
                            Belum punya akun?
                            <a href="javascript:void(0)" onclick="openHelpModal()" class="text-blue-600 font-bold hover:underline">Hubungi admin sekolah</a>
                        </p>
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

    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Memproses...</span>';
    });
</script>
@endsection