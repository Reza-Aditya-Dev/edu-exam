@extends('layouts.app')

@section('title', 'Masuk — CBT EduExam SMA Nusantara')

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
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-surface antialiased">
    <div class="w-full max-w-[420px] flex flex-col relative bg-surface py-6">
        
        <!-- Ambient Glow -->
        <div class="relative w-full flex flex-col items-center">
            <div class="absolute -top-10 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
            
            <!-- Top Branding & Logo Card -->
            <div class="flex flex-col items-center text-center mt-2 mb-6 w-full">
                <div class="relative mb-3">
                    <div class="w-18 h-18 bg-surface-container-lowest rounded-2xl shadow-md p-3 flex items-center justify-center border border-surface-container">
                        <div class="w-12 h-12 rounded-xl bg-primary text-on-primary flex items-center justify-center font-bold text-2xl shadow-sm">
                            E
                        </div>
                    </div>
                    <div class="absolute -bottom-1 -right-1 bg-secondary text-on-secondary text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary-container animate-pulse"></span>
                        <span>Aktif</span>
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-high rounded-full text-on-surface-variant text-xs font-semibold mb-2">
                    <span class="material-symbols-outlined text-[15px] text-primary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                    <span>CBT Portal Resmi Siswa</span>
                </div>
                
                <h1 class="font-headline-sm text-2xl font-bold text-on-surface tracking-tight mb-1">
                    Selamat Datang
                </h1>
                <p class="font-body-sm text-xs text-on-surface-variant max-w-[280px]">
                    Masuk untuk mengikuti ujian sekolah dengan tertib dan lancar
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="w-full bg-surface-container-lowest rounded-2xl shadow-xl p-6 flex flex-col gap-4 border border-surface-container">
                <!-- Session Alerts -->
                @if(session('error'))
                    <div class="p-3 bg-error-container/50 border border-error/30 rounded-xl text-error text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('success'))
                    <div class="p-3 bg-secondary-container/40 border border-secondary/30 rounded-xl text-secondary text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" id="loginForm" class="flex flex-col gap-4">
                    @csrf

                    <!-- Input 1: NISN / Email / Username -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface flex items-center justify-between" for="login">
                            <span>NISN / Username / Email</span>
                            <span class="text-[10px] text-on-surface-variant font-normal">Wajib</span>
                        </label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                                   placeholder="Contoh: 100200300 atau siswa"
                                   class="w-full h-12 pl-11 pr-4 bg-surface-container-low text-on-surface text-xs md:text-sm rounded-xl placeholder:text-outline border border-surface-container focus:outline-none focus:bg-surface-container-lowest focus:border-primary transition-all duration-200 @error('login') border-error @enderror">
                        </div>
                        @error('login')
                            <p class="text-[11px] text-error mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input 2: Kata Sandi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface flex items-center justify-between" for="password">
                            <span>Kata Sandi</span>
                            <span class="text-[10px] text-on-surface-variant font-normal">Sandi Akun</span>
                        </label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                   placeholder="Masukkan kata sandi"
                                   class="w-full h-12 pl-11 pr-11 bg-surface-container-low text-on-surface text-xs md:text-sm rounded-xl placeholder:text-outline border border-surface-container focus:outline-none focus:bg-surface-container-lowest focus:border-primary transition-all duration-200 @error('password') border-error @enderror">
                            <button type="button" onclick="togglePassword()" class="absolute right-2.5 w-8 h-8 flex items-center justify-center text-outline hover:text-on-surface rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-error mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded text-primary bg-surface-container-low focus:ring-0 cursor-pointer">
                            <span class="text-xs text-on-surface-variant">Ingat saya</span>
                        </label>
                        <a href="javascript:void(0)" onclick="openHelpModal()" class="text-xs font-medium text-primary hover:underline">
                            Lupa sandi?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="loginBtn" class="w-full h-12 bg-primary hover:bg-primary-container text-on-primary font-headline-sm text-sm font-bold rounded-xl shadow-md active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2 mt-1">
                        <span id="btnText">Masuk ke Ujian</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </form>

                <!-- Quick Demo Fill Pills for Testing -->
                <div class="pt-2 border-t border-surface-container flex flex-col gap-1.5">
                    <span class="text-[10px] text-on-surface-variant font-medium text-center">Akun Percobaan (Klik untuk mengisi):</span>
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="fillCreds('siswa', 'password123')" class="px-2.5 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs text-primary font-semibold transition-colors">
                            Siswa
                        </button>
                        <button type="button" onclick="fillCreds('guru', 'password123')" class="px-2.5 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs text-primary font-semibold transition-colors">
                            Guru
                        </button>
                        <button type="button" onclick="fillCreds('admin', 'password123')" class="px-2.5 py-1 rounded-lg bg-surface-container-low hover:bg-surface-container text-xs text-primary font-semibold transition-colors">
                            Admin
                        </button>
                    </div>
                </div>

                <!-- Session Info Banner -->
                <div class="mt-1 p-3 bg-surface-container-low rounded-xl flex items-start gap-2 border border-surface-container">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5" style="font-variation-settings: 'FILL' 1;">info</span>
                    <div class="flex flex-col text-on-surface-variant text-[11px] leading-snug">
                        <span class="font-semibold text-on-surface">Jadwal Sesi Ujian: 08:00 - 12:00 WIB</span>
                        Pastikan koneksi internet stabil demi menjaga kelancaran pengerjaan soal CBT.
                    </div>
                </div>
            </div>

            <!-- Footer & Operator Helpline -->
            <div class="w-full mt-4 flex flex-col items-center gap-2 text-center">
                <div class="px-3 py-1 bg-surface-container rounded-full text-on-surface text-[11px] font-medium inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[15px] text-secondary" style="font-variation-settings: 'FILL' 1;">school</span>
                    <span>Sistem CBT • SMA Nusantara TP 2026/2027</span>
                </div>
                <div class="flex items-center gap-1.5 text-on-surface-variant text-xs">
                    <span>Butuh bantuan?</span>
                    <button type="button" onclick="openHelpModal()" class="text-primary font-semibold hover:underline flex items-center gap-0.5">
                        <span>Hubungi Operator Ujian</span>
                        <span class="material-symbols-outlined text-[14px]">support_agent</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Help Modal Dialog -->
        <div id="help-modal" class="fixed inset-0 z-50 bg-inverse-surface/40 backdrop-blur-sm hidden items-center justify-center p-4">
            <div class="w-full max-w-[390px] bg-surface-container-lowest rounded-2xl p-5 shadow-2xl flex flex-col gap-3 border border-surface-container">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-on-surface font-headline-sm text-sm font-bold">
                        <span class="material-symbols-outlined text-primary">contact_support</span>
                        <span>Bantuan Peserta Ujian</span>
                    </div>
                    <button type="button" onclick="closeHelpModal()" class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
                <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                    Jika Anda mengalami kendala saat masuk akun, kartu peserta hilang, atau kendala teknis:
                </p>
                <div class="bg-surface-container-low rounded-xl p-3 flex flex-col gap-2 text-xs">
                    <div class="flex items-center gap-2 text-on-surface">
                        <span class="material-symbols-outlined text-[18px] text-secondary">person_pin</span>
                        <span>Posko Lab CBT (Gedung B, Lantai 1)</span>
                    </div>
                    <div class="flex items-center gap-2 text-on-surface">
                        <span class="material-symbols-outlined text-[18px] text-secondary">call</span>
                        <span>Hotline Operator: 0812-3456-7890</span>
                    </div>
                </div>
                <button type="button" onclick="closeHelpModal()" class="w-full h-11 bg-primary text-on-primary font-label-lg text-xs font-bold rounded-xl mt-1">
                    Mengerti, Terima Kasih
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
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

    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Memproses Masuk...</span>';
    });
</script>
@endsection
