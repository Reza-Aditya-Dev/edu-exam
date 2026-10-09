<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>EduExam — Platform Ujian Digital Sekolah Terintegrasi</title>

    <!-- Dark Mode Init (Anti-FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0b1c30',
                            dark: '#071220',
                            light: '#132c4a',
                        },
                        brand: {
                            blue: '#2563eb',
                            deep: '#1d4ed8',
                            light: '#eff6ff',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        script: ['Caveat', 'cursive'],
                    }
                }
            }
        };
    </script>
    <style>
        /* Smooth scrolling & mobile safety */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark ::-webkit-scrollbar-track {
            background: #0b1322;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #1e293b;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
        /* Pulse wave animation */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.3; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-indicator {
            animation: pulse-ring 2s infinite ease-in-out;
        }

        /* Tab panel fade animation */
        @keyframes tabFadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .tab-fade-in {
            animation: tabFadeIn 0.35s ease-out forwards;
        }
    </style>
</head>
<body class="bg-white text-slate-800 dark:bg-[#070f1e] dark:text-slate-100 font-sans antialiased selection:bg-blue-100 selection:text-blue-700 dark:selection:bg-blue-900/40 dark:selection:text-blue-200 transition-colors duration-200">

    

    <!-- NAVBAR -->
    <header class="w-full bg-white/95 dark:bg-[#0b172a]/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 sticky top-0 z-50 transition-colors duration-200">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 h-16 sm:h-20 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-2.5 no-underline group flex-shrink-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#2563eb] flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 3.27L18.84 9 12 12.73 5.16 9 12 6.27zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                    </svg>
                </div>
                <div class="font-extrabold text-xl sm:text-2xl text-[#0b1c30] dark:text-white tracking-tight transition-colors">
                    Edu<span class="text-[#2563eb]">Exam</span>
                </div>
            </a>
            
            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-7 lg:gap-9">
                <div class="relative py-2">
                    <a href="{{ url('/') }}" class="text-sm font-bold text-[#0b1c30] dark:text-white transition">Beranda</a>
                    <span class="block w-6 h-[3px] bg-[#2563eb] rounded-full mx-auto mt-1"></span>
                </div>
                <a href="#tentang" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Tentang</a>
                <a href="#fitur" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Fitur</a>
                <a href="#panduan" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Panduan</a>
            </nav>

            <!-- Right Controls: CTA, Dark/Light Mode Toggle & Mobile Hamburger -->
            <div class="flex items-center gap-2 sm:gap-3">

                <!-- Dark / Light Mode Toggle Button (Desktop & Mobile) -->
                <button type="button" 
                        id="themeToggleBtn" 
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-amber-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors shadow-sm cursor-pointer" 
                        aria-label="Alihkan tema gelap/terang"
                        title="Alihkan mode tema">
                    <span id="themeMoonIcon" class="material-symbols-outlined text-[20px] sm:text-[22px]">dark_mode</span>
                    <span id="themeSunIcon" class="material-symbols-outlined text-[20px] sm:text-[22px] hidden">light_mode</span>
                </button>

                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="px-5 sm:px-6 py-2 sm:py-2.5 rounded-full bg-[#0b1c30] hover:bg-[#132c4a] dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm flex items-center gap-1.5 sm:gap-2">
                                <span>Dashboard Siswa</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        @elseif(auth()->user()->role === 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="px-5 sm:px-6 py-2 sm:py-2.5 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition shadow-sm flex items-center gap-1.5 sm:gap-2">
                                <span>Dashboard Guru</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="px-5 sm:px-6 py-2 sm:py-2.5 rounded-full bg-[#0b1c30] hover:bg-[#132c4a] dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm flex items-center gap-1.5 sm:gap-2">
                                <span>Admin Panel</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 sm:px-7 py-2 sm:py-2.5 rounded-full bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-bold text-xs transition shadow-sm flex items-center gap-1.5 sm:gap-2">
                            <span>Masuk</span>
                        </a>
                    @endauth
                @endif

                

                <!-- Mobile Hamburger Button -->
                <button type="button" 
                        id="mobileMenuToggle" 
                        class="md:hidden w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition cursor-pointer" 
                        aria-label="Buka menu navigasi"
                        aria-expanded="false">
                    <span id="menuOpenIcon" class="material-symbols-outlined text-2xl">menu</span>
                    <span id="menuCloseIcon" class="material-symbols-outlined text-2xl hidden">close</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer / Dropdown Menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-slate-100 dark:border-slate-800 bg-white/98 dark:bg-[#0b172a]/98 backdrop-blur-lg px-4 pt-3 pb-6 shadow-xl transition-all duration-300">
            <nav class="flex flex-col space-y-1">
                <a href="{{ url('/') }}" class="mobile-nav-link flex items-center justify-between px-4 py-3 rounded-xl bg-blue-50/70 dark:bg-blue-950/50 text-[#2563eb] dark:text-blue-400 font-bold text-sm">
                    <span>Beranda</span>
                    <span class="w-2 h-2 rounded-full bg-[#2563eb] dark:bg-blue-400"></span>
                </a>
                <a href="#tentang" class="mobile-nav-link flex items-center px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold text-sm transition">
                    <span>Tentang Platform</span>
                </a>
                <a href="#fitur" class="mobile-nav-link flex items-center px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold text-sm transition">
                    <span>Fitur Unggulan</span>
                </a>
                <a href="#panduan" class="mobile-nav-link flex items-center px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold text-sm transition">
                    <span>Panduan Pelaksanaan</span>
                </a>
                
                <!-- Quick Mobile Theme Switcher Item -->
                <button type="button" 
                        id="mobileThemeToggleBtn" 
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold text-sm transition cursor-pointer">
                    <span class="flex items-center gap-2">
                        <span id="mobileThemeIcon" class="material-symbols-outlined text-lg text-amber-500">dark_mode</span>
                        <span id="mobileThemeLabel">Mode Gelap</span>
                    </span>
                    <span class="text-[11px] px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold border border-slate-200 dark:border-slate-700" id="mobileThemeBadge">Aktifkan</span>
                </button>
            </nav>

            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-2">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-4">Pilih Portal Akses</div>
                <div class="grid grid-cols-3 gap-2 px-1">
                    <a href="{{ route('login') }}?role=student" class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-center font-bold text-xs flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-lg">school</span>
                        <span>Siswa</span>
                    </a>
                    <a href="{{ route('login') }}?role=teacher" class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-center font-bold text-xs flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-lg">person_apron</span>
                        <span>Guru</span>
                    </a>
                    <a href="{{ route('login') }}?role=admin" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-center font-bold text-xs flex flex-col items-center gap-1">
                        <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                        <span>Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO SECTION (BAGIAN ATAS SESUAI GAMBAR) -->
    <section class="relative pt-0 pb-10 sm:pb-14 lg:pb-20 overflow-hidden bg-white dark:bg-[#070f1e] transition-colors duration-200">
        <!-- Far-left soft organic background curves as in screenshot -->
        <div class="absolute left-0 top-[45%] -translate-y-1/2 w-6 sm:w-16 h-32 sm:h-56 bg-[#e1edff] dark:bg-blue-950/40 rounded-r-full pointer-events-none opacity-80"></div>
        <div class="absolute -left-12 top-1/3 -translate-y-1/2 w-48 h-72 bg-blue-50/60 dark:bg-blue-900/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-10 lg:gap-8 items-start">
                
                <!-- LEFT COLUMN: Content & Value Props -->
                <div class="lg:col-span-5 xl:col-span-5 z-10 text-left pt-6 sm:pt-10 lg:pt-14 pb-4 lg:pb-12">
                    <!-- Pill Tag -->
                    <div class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full bg-[#eef4ff] text-[#2563eb] text-[11px] sm:text-xs font-semibold mb-5 sm:mb-6 border border-[#dbeafe] dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-900/50">
                        <span>Platform Ujian Digital untuk Sekolah</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2563eb] dark:bg-blue-400"></span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[52px] font-extrabold text-[#0b1c30] dark:text-white tracking-tight leading-[1.15] sm:leading-[1.12] mb-5 sm:mb-6">
                        Ujian Digital,<br>
                        <span class="text-[#2563eb] dark:text-blue-400">Lebih Mudah,</span><br>
                        <span class="text-[#2563eb] dark:text-blue-400">Lebih Terukur.</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base leading-relaxed max-w-lg mb-6 sm:mb-8 font-normal">
                        EduExam hadir untuk membantu sekolah dalam melaksanakan ujian secara online dengan aman, praktis, dan efisien. Dilengkapi dengan fitur lengkap untuk guru, siswa, dan admin.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-3.5 mb-8 sm:mb-10 w-full sm:w-auto">
                      
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-[#2563eb] hover:bg-[#1d4ed8] text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5">
                                <span>Mulai Sekarang</span>
                            </a>

                        <button type="button" 
                                onclick="openDemoModal()" 
                                class="w-full sm:w-auto px-6 py-3.5 rounded-full bg-[#f4f7fc] hover:bg-[#e9effa] text-slate-700 dark:bg-slate-800/90 dark:hover:bg-slate-800 dark:text-slate-200 font-semibold text-xs sm:text-sm border border-slate-200/80 dark:border-slate-700 flex items-center justify-center gap-2.5 transition">
                            <span>Pelajari Lebih Lanjut</span>
                            <span class="w-6 h-6 rounded-full bg-[#2563eb] text-white flex items-center justify-center text-[10px] pl-0.5">▶</span>
                        </button>
                    </div>

                    <!-- 3 Horizontal Feature Highlights -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 pt-5 sm:pt-6 border-t border-slate-100 dark:border-slate-800/80 max-w-xl">
                        <!-- 100% Digital -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#eaf4ff] dark:bg-blue-950/70 dark:text-blue-400 flex items-center justify-center text-[#2563eb] flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">verified_user</span>
                            </div>
                            <div>
                                <div class="font-bold text-[#0b1c30] dark:text-white text-xs sm:text-sm leading-tight">100% Digital</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Tanpa kertas, lebih efisien</div>
                            </div>
                        </div>

                        <!-- Real-time -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#eaf4ff] dark:bg-blue-950/70 dark:text-blue-400 flex items-center justify-center text-[#2563eb] flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">bolt</span>
                            </div>
                            <div>
                                <div class="font-bold text-[#0b1c30] dark:text-white text-xs sm:text-sm leading-tight">Real-time</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Hasil langsung tersedia</div>
                            </div>
                        </div>

                        <!-- Aman -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#eaf4ff] dark:bg-blue-950/70 dark:text-blue-400 flex items-center justify-center text-[#2563eb] flex-shrink-0">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <div>
                                <div class="font-bold text-[#0b1c30] dark:text-white text-xs sm:text-sm leading-tight">Aman</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Data terlindungi dengan baik</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Hero Photo with Exact Organic Wavy Curve ("Lika-Liku") -->
                <div class="lg:col-span-7 xl:col-span-7 flex justify-center lg:justify-end mt-4 lg:mt-0 w-full self-start">
                    
                    <!-- Container positioned right-0 directly against viewport on desktop -->
                    <div class="relative w-full max-w-[640px] lg:max-w-none lg:absolute lg:top-0 lg:right-0 lg:bottom-0 lg:w-[48vw] xl:w-[50vw] 2xl:w-[52vw] flex justify-end items-start pointer-events-none sm:pointer-events-auto">

                        <!-- Decorative: Handwritten Cursive Slogan -->
                        <div class="absolute top-2 sm:top-5 right-6 sm:right-12 md:right-16 lg:right-20 xl:right-28 2xl:right-36 z-20 pointer-events-none select-none text-right">
                            <div class="font-script text-lg sm:text-2xl md:text-3xl font-bold text-[#1e3a8a] dark:text-blue-300 leading-[1.05] -rotate-6 tracking-wide drop-shadow-sm">
                                Bersama<br>
                                Membangun<br>
                                Pendidikan<br>
                                Digital
                            </div>
                            <svg class="w-16 sm:w-24 h-3 sm:h-4 ml-auto -mt-1 text-[#1e3a8a] dark:text-blue-300" viewBox="0 0 100 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                <path d="M5 6 C35 14, 70 2, 95 9" />
                            </svg>
                        </div>

                        <!-- Decorative: Floating Tilted Pill Accent -->
                        <div class="absolute top-10 sm:top-14 right-2 sm:right-4 w-4 sm:w-6 h-12 sm:h-20 rounded-full bg-[#46659c]/50 dark:bg-blue-500/30 rotate-[28deg] z-10 pointer-events-none"></div>

                        <!-- Bottom right deep navy organic wave accent peeking out -->
                        <div class="absolute bottom-0 right-0 w-44 sm:w-72 md:w-80 h-32 sm:h-48 md:h-56 bg-[#0b1c30] dark:bg-[#0d1f38] rounded-tl-[80px] sm:rounded-tl-[120px] rounded-br-[0px] z-0 pointer-events-none opacity-95"></div>

                        <!-- The Image with Organic Curved "Lika-Liku" Shape -->
                        <div class="relative z-10 drop-shadow-2xl w-full h-full flex justify-end">
                            <img src="{{ asset('images/hero_students_curved.png') }}" 
                                 alt="Siswa Mengikuti Ujian Digital EduExam" 
                                 class="w-full h-auto lg:h-full lg:w-full object-cover object-left-top block select-none">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 4 FLOATING FEATURE CARDS (Directly Below Hero) -->
    <section class="relative z-20 -mt-2 sm:-mt-6 lg:-mt-8 mb-16 sm:mb-20 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-[#0e1726] rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-200/60 dark:shadow-black/50 border border-slate-100 dark:border-slate-800 p-6 sm:p-8 md:p-10 transition-colors duration-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-0 lg:divide-x divide-slate-100 dark:divide-slate-800">
                    
                    <!-- Feature 1: Ujian Online -->
                    <div class="p-4 sm:p-5 lg:px-6 rounded-2xl bg-slate-50/60 sm:bg-transparent border border-slate-100 sm:border-0 dark:border-slate-800/60 dark:bg-slate-800/20 sm:dark:bg-transparent first:pl-0 hover:bg-blue-50/30 dark:hover:bg-blue-950/30 transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#eaf4ff] dark:bg-blue-950/70 dark:text-blue-400 text-[#2563eb] flex items-center justify-center mb-4 sm:mb-5">
                            <span class="material-symbols-outlined text-2xl">description</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-base mb-2">Ujian Online</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Pelaksanaan ujian lebih fleksibel dengan sistem yang stabil, ringan, dan aman anti-kendala.
                        </p>
                    </div>

                    <!-- Feature 2: Bank Soal -->
                    <div class="p-4 sm:p-5 lg:px-6 rounded-2xl bg-slate-50/60 sm:bg-transparent border border-slate-100 sm:border-0 dark:border-slate-800/60 dark:bg-slate-800/20 sm:dark:bg-transparent hover:bg-emerald-50/30 dark:hover:bg-emerald-950/30 transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#e6fbf4] dark:bg-emerald-950/70 dark:text-emerald-400 text-[#0d9488] flex items-center justify-center mb-4 sm:mb-5">
                            <span class="material-symbols-outlined text-2xl">database</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-base mb-2">Bank Soal</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Kelola ribuan soal dengan mudah, kelompokkan berdasarkan mata pelajaran, KD, dan jenjang kelas.
                        </p>
                    </div>

                    <!-- Feature 3: Penilaian Otomatis -->
                    <div class="p-4 sm:p-5 lg:px-6 rounded-2xl bg-slate-50/60 sm:bg-transparent border border-slate-100 sm:border-0 dark:border-slate-800/60 dark:bg-slate-800/20 sm:dark:bg-transparent hover:bg-purple-50/30 dark:hover:bg-purple-950/30 transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#f3edff] dark:bg-purple-950/70 dark:text-purple-400 text-[#7c3aed] flex items-center justify-center mb-4 sm:mb-5">
                            <span class="material-symbols-outlined text-2xl">monitoring</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-base mb-2">Penilaian Otomatis</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Hemat waktu berhari-hari dengan koreksi otomatis seketika dan kalkulasi nilai akurat tuntas KKM.
                        </p>
                    </div>

                    <!-- Feature 4: Analitik Hasil -->
                    <div class="p-4 sm:p-5 lg:px-6 rounded-2xl bg-slate-50/60 sm:bg-transparent border border-slate-100 sm:border-0 dark:border-slate-800/60 dark:bg-slate-800/20 sm:dark:bg-transparent last:pr-0 hover:bg-amber-50/30 dark:hover:bg-amber-950/30 transition">
                        <div class="w-12 h-12 rounded-2xl bg-[#fff6e6] dark:bg-amber-950/70 dark:text-amber-400 text-[#d97706] flex items-center justify-center mb-4 sm:mb-5">
                            <span class="material-symbols-outlined text-2xl">group</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-base mb-2">Analitik Hasil</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Pantau daya serap butir soal, grafik ketuntasan kelas, dan rekapitulasi nilai siap cetak & ekspor Excel.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- SECTION UNTUK GURU (From Screenshot Bottom Preview) -->
    <section id="tentang" class="py-14 sm:py-20 px-4 sm:px-6 bg-[#fcfdff] dark:bg-[#0a1220] border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                
                <!-- Left: Teacher image mockup -->
                <div class="lg:col-span-6 relative w-full max-w-lg mx-auto lg:max-w-none">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl sm:shadow-2xl border border-slate-100 dark:border-slate-800 aspect-[4/3] bg-slate-100 dark:bg-slate-800 group">
                        <img src="{{ asset('images/teacher_presenting.jpg') }}" 
                             alt="Guru Mengelola Ujian dengan EduExam" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Floating Badge on Teacher Photo -->
                        <div class="absolute bottom-4 left-4 right-4 p-3 rounded-xl bg-white/95 dark:bg-[#0e1726]/95 backdrop-blur-md border border-slate-200/80 dark:border-slate-700 shadow-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">Ujian Berlangsung: Biologi Kelas XI</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">32 Siswa Mengerjakan • Rata-rata Skor: 84.5</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">TERKENDALI</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Teacher Content -->
                <div class="lg:col-span-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-semibold mb-4">
                        <span>Untuk Guru</span>
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0b1c30] dark:text-white tracking-tight mb-4">
                        Kelola Ujian dengan Lebih Mudah
                    </h2>
                    
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6 sm:mb-8">
                        Buat, atur, dan pantau pelaksanaan ujian dengan sistem yang intuitif. Semua kebutuhan penilaian akademik sekolah dalam satu platform terpadu.
                    </p>

                    <div class="space-y-3.5 sm:space-y-4">
                        <div class="flex items-start gap-3.5 sm:gap-4 p-4 rounded-2xl bg-white dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800 shadow-sm hover:border-emerald-200 dark:hover:border-emerald-700 transition">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">dataset</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0b1c30] dark:text-white text-sm">Penyusunan Bank Soal Cepat & Terstruktur</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mendukung pilihan ganda acak, pembobotan nilai, kunci jawaban terenkripsi, serta integrasi gambar soal beresolusi tinggi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 sm:gap-4 p-4 rounded-2xl bg-white dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800 shadow-sm hover:border-blue-200 dark:hover:border-blue-700 transition">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">live_tv</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0b1c30] dark:text-white text-sm">Monitoring Ujian Live Real-time</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau status pengerjaan setiap siswa secara langsung, durasi pengerjaan, dan log aktivitas ujian secara transparan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 sm:gap-4 p-4 rounded-2xl bg-white dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800 shadow-sm hover:border-purple-200 dark:hover:border-purple-700 transition">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">query_stats</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0b1c30] dark:text-white text-sm">Koreksi Otomatis & Analisis Butir Soal</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nilai terhitung otomatis seketika dengan grafik daya serap, daftar remedial, dan rekapitulasi nilai siap ekspor.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION INTERACTIVE PLATFORM SHOWCASE (FITUR UNGGULAN LENGKAP) -->
    <section id="fitur" class="py-16 sm:py-24 px-4 sm:px-6 bg-white dark:bg-[#070f1e] border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2 block">Ekosistem Ujian Lengkap</span>
                <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl text-[#0b1c30] dark:text-white tracking-tight">
                    Fitur Khusus untuk Setiap Peran di Sekolah
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mt-3 leading-relaxed">
                    Setiap modul dirancang khusus untuk kenyamanan siswa, efisiensi guru, dan kontrol menyeluruh administrator sekolah.
                </p>

                <!-- Interactive Tab Switchers -->
                <div class="inline-flex p-1.5 rounded-2xl bg-slate-100/90 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 mt-8 gap-1">
                   
                    <button type="button"
                            onclick="switchFeatureTab('student', true)"
                            id="tabBtnStudent"
                            class="px-4 sm:px-6 py-2.5 rounded-xl font-bold text-xs transition-all bg-white dark:bg-slate-900 text-[#2563eb] dark:text-blue-400 shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[16px] align-middle mr-1">school</span>
                        Untuk Siswa
                    </button>

                    <button type="button" 
                            onclick="switchFeatureTab('teacher', true)" 
                            id="tabBtnTeacher" 
                            class="px-4 sm:px-6 py-2.5 rounded-xl font-semibold text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-[16px] align-middle mr-1">co_present</span>
                            Untuk Guru
                    </button>
                    <button type="button" 
                            onclick="switchFeatureTab('admin', true)" 
                            id="tabBtnAdmin" 
                            class="px-4 sm:px-6 py-2.5 rounded-xl font-semibold text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-[16px] align-middle mr-1">admin_panel_settings</span>
                            Administrator
                    </button>
                </div>
            </div>

            <!-- TAB CONTENT 1: SISWA -->
            <div id="tabContentStudent" class="feature-tab-panel tab-fade-in grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                <div class="lg:col-span-6 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-bold mb-3">
                        <span>Antarmuka Siswa Modern</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0b1c30] dark:text-white tracking-tight mb-4">
                        Pengerjaan Ujian Nyaman & Bebas Gangguan
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Siswa dapat fokus mengerjakan soal tanpa khawatir kehilangan jawaban. Tampilan responsif sempurna di layar smartphone maupun PC sekolah.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400">cloud_done</span>
                                Autosave Jawaban
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Jawaban tersimpan otomatis ke server via AJAX dalam hitungan milidetik.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400">timer</span>
                                Timer Countdown Akurat
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Penghitung mundur tersinkronisasi dengan server ujian sekolah.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400">grid_view</span>
                                Kisi Nomor Interaktif
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Navigasi cepat ke nomor terjawab, belum terjawab, dan tanda ragu-ragu.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-blue-600 dark:text-blue-400">history_edu</span>
                                Riwayat & Pembahasan
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Siswa dapat langsung melihat skor kelulusan dan pembahasan soal.</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 order-1 lg:order-2">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('images/student_mockup.png') }}" 
                             alt="Tampilan Ujian Siswa EduExam" 
                             class="w-full h-auto object-cover">
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT 2: GURU -->
            <div id="tabContentTeacher" class="feature-tab-panel hidden grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                <div class="lg:col-span-6 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs font-bold mb-3">
                        <span>Panel Manajemen Guru</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0b1c30] dark:text-white tracking-tight mb-4">
                        Otomasi Bank Soal & Pengawasan Ujian Real-time
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Memudahkan para guru menyiapkan ujian berkualitas, mengacak urutan soal per siswa, dan mengawasi jalannya ujian secara langsung dari gawai guru.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-emerald-600 dark:text-emerald-400">shuffle</span>
                                Acak Soal & Opsi Jawaban
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Mencegah contek-mencontek dengan urutan soal berbeda untuk setiap siswa.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-emerald-600 dark:text-emerald-400">key</span>
                                Manajemen Token Ujian
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Ujian hanya dapat dibuka dengan token resmi yang diatur guru pengawas.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-emerald-600 dark:text-emerald-400">bar_chart</span>
                                Grafik Sebaran Daya Serap
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Deteksi soal mana yang dianggap paling sulit oleh mayoritas siswa kelas.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-emerald-600 dark:text-emerald-400">file_download</span>
                                Ekspor Nilai ke Excel
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Daftar nilai langsung siap dicetak atau diimpor ke e-Rapor sekolah.</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 order-1 lg:order-2">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('images/teacher_mockup.png') }}" 
                             alt="Dashboard Guru EduExam" 
                             class="w-full h-auto object-cover">
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT 3: ADMIN -->
            <div id="tabContentAdmin" class="feature-tab-panel hidden grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
                <div class="lg:col-span-6 order-2 lg:order-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-xs font-bold mb-3">
                        <span>Pusat Kendali Administrator</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#0b1c30] dark:text-white tracking-tight mb-4">
                        Manajemen Terpusat Sekolah & Audit Sistem Lengkap
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Pusat pengelolaan seluruh data induk sekolah: rombongan belajar (rombel), data guru dan siswa, mata pelajaran, serta pencetakan kartu ujian resmi.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-slate-800 dark:text-blue-400">upload_file</span>
                                Import Massal Excel
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Import ratusan data siswa dan guru sekaligus dengan template Excel praktis.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-slate-800 dark:text-blue-400">badge</span>
                                Cetak Kartu Peserta Ujian
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Cetak kartu ujian per kelas lengkap dengan foto, NISN, dan barcode verifikasi.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-slate-800 dark:text-blue-400">article</span>
                                Berita Acara Pelaksanaan
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Dokumentasi administratif resmi pelaksanaan ujian siap cetak PDF.</div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0e1726] border border-slate-100 dark:border-slate-800">
                            <div class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-base text-slate-800 dark:text-blue-400">security_update_good</span>
                                Log Audit Aktivitas
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">Pantau riwayat login, waktu pengerjaan, dan catatan keamanan sistem.</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 order-1 lg:order-2">
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('images/admin_mockup.png') }}" 
                             alt="Panel Admin EduExam" 
                             class="w-full h-auto object-cover">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION PANDUAN PELAKSANAAN UJIAN (4 LANGKAH ALUR KERJA) -->
    <section id="panduan" class="py-16 sm:py-24 px-4 sm:px-6 bg-[#f8faff] dark:bg-[#0a1220] border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-2xl mx-auto mb-14 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2 block">Alur Kerja Praktis</span>
                <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl text-[#0b1c30] dark:text-white tracking-tight">
                    Panduan 4 Langkah Pelaksanaan Ujian
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mt-2">
                    Proses ujian sekolah kini disederhanakan dari persiapan hingga rekapitulasi nilai akhir.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                
                <!-- Step 1 -->
                <div class="bg-white dark:bg-[#0e1726] p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm relative flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 font-extrabold text-lg flex items-center justify-center mb-5">
                            01
                        </div>
                        <h4 class="font-bold text-[#0b1c30] dark:text-white text-base mb-2">Penyusunan Ujian</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Guru menginput soal ke bank soal, menentukan jadwal pelaksanaan, durasi waktu, serta token khusus ujian.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 text-[11px] font-bold text-blue-600 dark:text-blue-400">
                        <span>Akses: Guru / Pengawas</span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white dark:bg-[#0e1726] p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm relative flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 font-extrabold text-lg flex items-center justify-center mb-5">
                            02
                        </div>
                        <h4 class="font-bold text-[#0b1c30] dark:text-white text-base mb-2">Distribusi Token</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Siswa membuka portal siswa dari smartphone atau komputer lab, lalu memasukkan token ujian untuk memulai tes.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 text-[11px] font-bold text-blue-600 dark:text-blue-400">
                        <span>Akses: Siswa & Pengawas</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white dark:bg-[#0e1726] p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm relative flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 font-extrabold text-lg flex items-center justify-center mb-5">
                            03
                        </div>
                        <h4 class="font-bold text-[#0b1c30] dark:text-white text-base mb-2">Pengerjaan & Autosave</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Siswa mengerjakan soal dengan timer yang berjalan. Setiap jawaban tersimpan otomatis ke server secara realtime.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Sistem: Autosave Aktif</span>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white dark:bg-[#0e1726] p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm relative flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 font-extrabold text-lg flex items-center justify-center mb-5">
                            04
                        </div>
                        <h4 class="font-bold text-[#0b1c30] dark:text-white text-base mb-2">Koreksi & Analitik</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Saat waktu habis atau ujian dikumpulkan, nilai langsung terkoreksi. Guru dan admin dapat langsung mengunduh rekap.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 text-[11px] font-bold text-purple-600 dark:text-purple-400">
                        <span>Output: Nilai & Analisis</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION PORTAL AKSES PENGGUNA (PILIH PERAN MASUK ANDA) -->
    <section id="akses" class="py-16 sm:py-24 px-4 sm:px-6 bg-white dark:bg-[#070f1e] border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2 block">Portal Akses Pengguna</span>
                <h2 class="font-extrabold text-2xl sm:text-3xl md:text-4xl text-[#0b1c30] dark:text-white tracking-tight">Pilih Peran Masuk Anda</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">Pilih akses sesuai peran Anda di sekolah untuk membuka lembar kerja dan fitur yang relevan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                <!-- Student Portal -->
                <a href="{{ route('login') }}?role=student" class="group p-6 sm:p-8 rounded-3xl bg-[#f8faff] dark:bg-[#0e1726] border border-slate-200/80 dark:border-slate-800 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-blue-100/70 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-5 sm:mb-6 group-hover:bg-blue-600 group-hover:text-white transition shadow-sm">
                            <span class="material-symbols-outlined text-3xl">school</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-xl mb-2">Portal Siswa</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                            Ikuti ujian secara realtime, pantau durasi waktu, simpan jawaban otomatis, dan tinjau hasil nilai serta pembahasan.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-blue-600 dark:text-blue-400 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Siswa</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </a>
                
                <!-- Teacher Portal -->
                <a href="{{ route('login') }}?role=teacher" class="group p-8 rounded-3xl bg-[#f8faff] dark:bg-[#0e1726] border border-slate-200/80 dark:border-slate-800 hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-emerald-100/70 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-5 sm:mb-6 group-hover:bg-emerald-600 group-hover:text-white transition shadow-sm">
                            <span class="material-symbols-outlined text-3xl">person_apron</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-xl mb-2">Portal Guru</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                            Susun bank soal dengan cepat, jadwalkan ujian kelas, acak urutan soal, dan pantau daya serap butir soal secara mendalam.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-emerald-700 dark:text-emerald-400 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Guru</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </a>
                
                <!-- Admin Portal -->
                <a href="{{ route('login') }}?role=admin" class="group p-8 rounded-3xl bg-[#f8faff] dark:bg-[#0e1726] border border-slate-200/80 dark:border-slate-800 hover:border-slate-800 dark:hover:border-slate-600 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-slate-200/80 dark:bg-slate-800 text-slate-800 dark:text-slate-200 flex items-center justify-center mx-auto mb-5 sm:mb-6 group-hover:bg-[#0b1c30] dark:group-hover:bg-slate-700 group-hover:text-white transition shadow-sm">
                            <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                        </div>
                        <h3 class="font-extrabold text-[#0b1c30] dark:text-white text-xl mb-2">Administrator</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                            Kelola data induk sekolah (siswa, guru, rombel kelas, mapel), cetak kartu ujian, serta audit log sistem.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-slate-800 dark:text-slate-200 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Admin</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>
    </section>



    <!-- CALL TO ACTION BANNER -->
    <section class="py-16 sm:py-20 px-4 sm:px-6 bg-[#0b1c30] dark:bg-[#050b14] text-white relative overflow-hidden transition-colors duration-200">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-5">
                Siap Melaksanakan Ujian Digital Sekolah yang Modern & Bebas Kendala?
            </h2>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-8 font-normal leading-relaxed">
                Bergabunglah dengan ribuan siswa dan guru yang telah merasakan kemudahan evaluasi akademik digital terpadu bersama EduExam.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                    <span>Masuk ke Akun Anda</span>
                    
                </a>
                <button type="button" onclick="openDemoModal()" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-sm transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">play_circle</span>
                    <span>Coba Simulasi CBT (Demo)</span>
                </button>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#071220] dark:bg-[#03060d] text-slate-400 py-12 px-4 sm:px-6 border-t border-slate-800/80 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 pb-10 border-b border-slate-800">
                <div>
                    <div class="flex items-center gap-2.5 text-white font-extrabold text-lg mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 3.27L18.84 9 12 12.73 5.16 9 12 6.27zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                            </svg>
                        </div>
                        EduExam CBT
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm mb-4">
                        Sistem Penilaian Akademik dan Ujian Digital Terpadu untuk Sekolah Menengah di Indonesia. Cepat, tertib, hemat kertas, dan akurat.
                    </p>
                    <div class="flex items-center gap-2 text-[11px] text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Server Version v2.4 (Build Stable)</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Navigasi Cepat</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#tentang" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#fitur" class="hover:text-white transition">Fitur Unggulan</a></li>
                        <li><a href="#panduan" class="hover:text-white transition">Panduan Alur</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Portal Pengguna</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('login') }}?role=student" class="hover:text-white transition">Masuk Siswa</a></li>
                        <li><a href="{{ route('login') }}?role=teacher" class="hover:text-white transition">Masuk Guru</a></li>
                        <li><a href="{{ route('login') }}?role=admin" class="hover:text-white transition">Administrator Panel</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Halaman Login Terpadu</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Kontak & Bantuan</h5>
                    <ul class="space-y-3 text-xs">
                        <li>
                            <a href="mailto:admin@eduexam.id" class="group flex items-center gap-2.5 text-slate-400 hover:text-white transition">
                                <div class="w-7 h-7 rounded-lg bg-blue-600/10 border border-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition">
                                    <span class="material-symbols-outlined text-sm">mail</span>
                                </div>
                                <div class="truncate">
                                    <span class="block text-[10px] text-slate-500 uppercase font-semibold leading-none mb-1">Email Layanan</span>
                                    <span class="text-slate-300 group-hover:text-blue-400 transition font-medium">admin@eduexam.id</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-2.5 text-slate-400 hover:text-white transition">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase font-semibold leading-none mb-1">WhatsApp Bantuan</span>
                                    <span class="text-slate-300 group-hover:text-emerald-400 transition font-medium">0812-3456-7890</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-center sm:text-left">
                <div>
                    &copy; {{ date('Y') }} EduExam. Platform Ujian Digital Sekolah Indonesia. Seluruh hak cipta dilindungi.
                </div>
                <div class="flex items-center gap-4 text-slate-500">
                    <span>Privasi Siswa Terlindungi</span>
                    <span>•</span>
                    <span>Standar Kemdikbudristek</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE CBT SIMULATION MODAL (LIVE DEMO) -->
    <div id="cbtDemoModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-[#0e1726] rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden animate-in fade-in duration-200">
            <!-- Modal Header -->
            <div class="bg-[#0b1c30] text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <div>
                        <div class="text-xs font-bold">Simulasi Interaktif CBT Siswa</div>
                        <div class="text-[10px] text-slate-400">Ujian Simulasi Matematika • Soal #1 dari 3</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 rounded-lg bg-slate-800 text-amber-300 font-mono text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">timer</span>
                        <span id="demoTimer">44:59</span>
                    </div>
                    <button type="button" onclick="closeDemoModal()" class="text-slate-400 hover:text-white transition p-1">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Autosave Toast Banner -->
                <div id="demoSaveToast" class="hidden mb-4 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm text-emerald-600 dark:text-emerald-400">check_circle</span>
                    <span>Jawaban berhasil disimpan otomatis ke server (Latency: 0.14 detik)</span>
                </div>

                <div class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">Pertanyaan 1 (Bobot 5 Poin)</div>
                <p class="text-slate-800 dark:text-slate-100 text-sm sm:text-base font-medium mb-6 leading-relaxed">
                    Manakah dari persamaan matematika di bawah ini yang merupakan fungsi kuadrat dengan titik puncak berada di sumbu-Y?
                </p>

                <!-- Options -->
                <div class="space-y-3" id="demoOptionsContainer">
                    <label class="demo-option flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/30 cursor-pointer transition">
                        <input type="radio" name="demoAnswer" value="A" onchange="handleDemoAnswer('A')" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">A. <span class="font-mono">f(x) = 2x + 4</span></span>
                    </label>
                    <label class="demo-option flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/30 cursor-pointer transition">
                        <input type="radio" name="demoAnswer" value="B" onchange="handleDemoAnswer('B')" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">B. <span class="font-mono">f(x) = x² - 9</span></span>
                    </label>
                    <label class="demo-option flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/30 cursor-pointer transition">
                        <input type="radio" name="demoAnswer" value="C" onchange="handleDemoAnswer('C')" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">C. <span class="font-mono">f(x) = 3x² + 6x + 1</span></span>
                    </label>
                    <label class="demo-option flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/30 cursor-pointer transition">
                        <input type="radio" name="demoAnswer" value="D" onchange="handleDemoAnswer('D')" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">D. <span class="font-mono">f(x) = 1 / x</span></span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-[#09101c] border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-[11px] text-slate-500 dark:text-slate-400 text-center sm:text-left">
                    💡 <em>Silakan klik salah satu opsi di atas untuk menguji fitur simpan otomatis.</em>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" onclick="closeDemoModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition">
                        Tutup Simulasi
                    </button>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#2563eb] hover:bg-blue-700 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Masuk ke Ujian Nyata</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- BACK TO TOP FLOATING BUTTON -->
    <button type="button" 
            id="backToTopBtn" 
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="fixed bottom-6 right-6 z-40 w-11 h-11 rounded-full bg-[#0b1c30] text-white shadow-xl hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-500 transition-all opacity-0 pointer-events-none flex items-center justify-center"
            aria-label="Kembali ke atas">
        <span class="material-symbols-outlined text-xl">arrow_upward</span>
    </button>

    <!-- SCRIPTS -->
    <script>
        // ─── Theme Management Functionality ───
        function updateThemeUI(isDark) {
            const moonIcon = document.getElementById('themeMoonIcon');
            const sunIcon = document.getElementById('themeSunIcon');
            const toggleBtn = document.getElementById('themeToggleBtn');
            const mobileIcon = document.getElementById('mobileThemeIcon');
            const mobileLabel = document.getElementById('mobileThemeLabel');
            const mobileBadge = document.getElementById('mobileThemeBadge');

            if (isDark) {
                // In dark mode: display the sun (light_mode) icon to switch to light
                if (moonIcon) moonIcon.classList.add('hidden');
                if (sunIcon) sunIcon.classList.remove('hidden');
                if (toggleBtn) {
                    toggleBtn.setAttribute('title', 'Beralih ke mode terang');
                    toggleBtn.setAttribute('aria-label', 'Beralih ke mode terang');
                }
                if (mobileIcon) mobileIcon.textContent = 'light_mode';
                if (mobileLabel) mobileLabel.textContent = 'Mode Terang';
                if (mobileBadge) mobileBadge.textContent = 'Aktif';
            } else {
                // In light mode: display the moon (dark_mode) icon to switch to dark
                if (sunIcon) sunIcon.classList.add('hidden');
                if (moonIcon) moonIcon.classList.remove('hidden');
                if (toggleBtn) {
                    toggleBtn.setAttribute('title', 'Beralih ke mode gelap');
                    toggleBtn.setAttribute('aria-label', 'Beralih ke mode gelap');
                }
                if (mobileIcon) mobileIcon.textContent = 'dark_mode';
                if (mobileLabel) mobileLabel.textContent = 'Mode Gelap';
                if (mobileBadge) mobileBadge.textContent = 'Beralih';
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeUI(isDark);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Theme UI based on current html class
            const currentIsDark = document.documentElement.classList.contains('dark');
            updateThemeUI(currentIsDark);

            // Bind Desktop & Navbar Theme Toggle Button
            const themeBtn = document.getElementById('themeToggleBtn');
            if (themeBtn) {
                themeBtn.addEventListener('click', toggleTheme);
            }

            // Bind Mobile Drawer Theme Toggle Button
            const mobileThemeBtn = document.getElementById('mobileThemeToggleBtn');
            if (mobileThemeBtn) {
                mobileThemeBtn.addEventListener('click', toggleTheme);
            }

            // Sync with system preferences if user has not explicitly chosen
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                        updateThemeUI(true);
                    } else {
                        document.documentElement.classList.remove('dark');
                        updateThemeUI(false);
                    }
                }
            });

            // 1. Mobile Menu Toggle
            const toggleBtn = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const openIcon = document.getElementById('menuOpenIcon');
            const closeIcon = document.getElementById('menuCloseIcon');
            const navLinks = document.querySelectorAll('.mobile-nav-link');

            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function () {
                    const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
                    toggleBtn.setAttribute('aria-expanded', !isExpanded);
                    mobileMenu.classList.toggle('hidden');
                    openIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                });

                navLinks.forEach(link => {
                    link.addEventListener('click', closeMobileNav);
                });
            }

            // 2. Top notification bar dismiss
            const closeTopBar = document.getElementById('closeTopNotification');
            const topBar = document.getElementById('topNotificationBar');
            if (closeTopBar && topBar) {
                closeTopBar.addEventListener('click', function () {
                    topBar.style.display = 'none';
                });
            }

            // 3. Back to Top Button Visibility
            const backToTopBtn = document.getElementById('backToTopBtn');
            window.addEventListener('scroll', function () {
                if (window.scrollY > 400) {
                    backToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                    backToTopBtn.classList.add('opacity-100');
                } else {
                    backToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                    backToTopBtn.classList.remove('opacity-100');
                }
            });

            // 4. Timer countdown for CBT Demo Modal
            let demoSeconds = 45 * 60;
            const timerEl = document.getElementById('demoTimer');
            setInterval(function () {
                if (demoSeconds > 0) {
                    demoSeconds--;
                    const m = Math.floor(demoSeconds / 60).toString().padStart(2, '0');
                    const s = (demoSeconds % 60).toString().padStart(2, '0');
                    if (timerEl) timerEl.textContent = `${m}:${s}`;
                }
            }, 1000);

            // 5. Inisialisasi Carousel Showcase Fitur 8 Detik
            startFeatureAutoTimer();
        });

        function closeMobileNav() {
            const mobileMenu = document.getElementById('mobileMenu');
            const openIcon = document.getElementById('menuOpenIcon');
            const closeIcon = document.getElementById('menuCloseIcon');
            const toggleBtn = document.getElementById('mobileMenuToggle');
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }

        // Showcase Carousel: Otomatis berpindah setiap 8 detik dan tombol di atas tersinkronisasi
        const featureTabs = ['student', 'teacher', 'admin'];
        let currentFeatureIndex = 0;
        let featureAutoTimer = null;
        const FEATURE_ROTATION_INTERVAL = 8000; // 8 detik

        function switchFeatureTab(role, isUserClick = true) {
            const index = featureTabs.indexOf(role);
            if (index !== -1) {
                currentFeatureIndex = index;
            }

            // Sembunyikan semua panel tab
            const panels = document.querySelectorAll('.feature-tab-panel');
            panels.forEach(p => {
                p.classList.add('hidden');
                p.classList.remove('grid');
                p.classList.remove('tab-fade-in');
            });

            // Reset tampilan semua tombol tab di atas
            featureTabs.forEach(b => {
                const btn = document.getElementById('tabBtn' + b.charAt(0).toUpperCase() + b.slice(1));
                if (btn) {
                    btn.classList.remove('bg-white', 'text-[#2563eb]', 'shadow-sm', 'font-bold', 'dark:bg-slate-900', 'dark:text-blue-400');
                    btn.classList.add('text-slate-600', 'dark:text-slate-400', 'font-semibold');
                }
            });

            // Aktifkan tombol tab terpilih
            const activeBtn = document.getElementById('tabBtn' + role.charAt(0).toUpperCase() + role.slice(1));
            // Tampilkan panel gambar & konten terpilih
            const activePanel = document.getElementById('tabContent' + role.charAt(0).toUpperCase() + role.slice(1));

            if (activeBtn) {
                activeBtn.classList.add('bg-white', 'text-[#2563eb]', 'shadow-sm', 'font-bold', 'dark:bg-slate-900', 'dark:text-blue-400');
                activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400', 'font-semibold');
            }
            if (activePanel) {
                activePanel.classList.remove('hidden');
                activePanel.classList.add('grid');
                // Trigger animasi halus fade-in saat berganti gambar & konten
                void activePanel.offsetWidth;
                activePanel.classList.add('tab-fade-in');
            }

            // Jika diklik oleh pengguna, restart hitungan timer 8 detik agar tidak langsung berganti
            if (isUserClick) {
                startFeatureAutoTimer();
            }
        }

        function nextFeatureTab() {
            currentFeatureIndex = (currentFeatureIndex + 1) % featureTabs.length;
            switchFeatureTab(featureTabs[currentFeatureIndex], false);
        }

        function startFeatureAutoTimer() {
            if (featureAutoTimer) {
                clearInterval(featureAutoTimer);
            }
            featureAutoTimer = setInterval(nextFeatureTab, FEATURE_ROTATION_INTERVAL);
        }

        // CBT Demo Modal Functions
        function openDemoModal() {
            const modal = document.getElementById('cbtDemoModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDemoModal() {
            const modal = document.getElementById('cbtDemoModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function handleDemoAnswer(option) {
            const toast = document.getElementById('demoSaveToast');
            if (toast) {
                toast.classList.remove('hidden');
                toast.classList.add('flex');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 4000);
            }
        }
    </script>

</body>
</html>