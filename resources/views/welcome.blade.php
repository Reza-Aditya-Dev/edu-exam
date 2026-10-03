<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduExam — Platform Ujian Digital Sekolah Terintegrasi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS Engine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#4f46e5',
                            dark: '#3730a3',
                            light: '#eef2ff',
                        },
                        secondary: {
                            DEFAULT: '#006c49',
                            dark: '#005236',
                            light: '#ecfdf5',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        headline: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-[#f8f9ff] text-slate-800 font-sans antialiased overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200/80 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="#" class="flex items-center gap-3 no-underline">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-indigo-600/30">
                    E
                </div>
                <div class="font-headline font-extrabold text-2xl text-slate-900 tracking-tight">
                    EduExam
                </div>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Fitur Unggulan</a>
                <a href="#akses" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Portal Masuk</a>
                
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm">
                                Dashboard Siswa ➔
                            </a>
                        @elseif(auth()->user()->role === 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
                                Dashboard Guru ➔
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition shadow-sm">
                                Admin Panel ➔
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-sm flex items-center gap-1.5">
                            <span class="material-symbols-outlined" style="font-size: 16px;">login</span>
                            Masuk ke Sistem
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="pt-40 pb-24 px-6 relative overflow-hidden bg-gradient-to-b from-indigo-50/70 via-[#f8f9ff] to-white text-center">
        <div class="max-w-4xl mx-auto flex flex-col items-center">
            
            <!-- Status Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm text-xs font-semibold text-indigo-700 mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Platform CBT SMA Terintegrasi</span>
            </div>
            
            <h1 class="font-headline font-extrabold text-4xl sm:text-5xl md:text-6xl text-slate-900 tracking-tight leading-[1.15] mb-6">
                Sistem <span class="text-indigo-600">Ujian Digital</span> Cepat, Tertib, & Bebas Kendala
            </h1>
            
            <p class="text-slate-600 text-base md:text-lg max-w-2xl mx-auto leading-relaxed mb-10">
                Satu platform terpadu untuk evaluasi akademik sekolah. Mulai dari bank soal guru, pengerjaan ujian berbasis smartphone untuk siswa, hingga audit komprehensif bagi admin sekolah.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3.5 items-center justify-center">
                <a href="#akses" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                    <span>Mulai Akses Sekarang</span>
                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_downward</span>
                </a>
                <a href="#fitur" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-sm transition shadow-sm">
                    Pelajari Fitur
                </a>
            </div>
            
        </div>
    </section>

    <!-- PORTALS -->
    <section id="akses" class="py-20 px-6 bg-white border-t border-slate-100">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2 block">Portal Akses Pengguna</span>
                <h2 class="font-headline font-extrabold text-3xl md:text-4xl text-slate-900 tracking-tight">Pilih Peran Masuk Anda</h2>
                <p class="text-slate-500 text-sm mt-2 max-w-xl mx-auto">Pilih akses sesuai peran Anda di sekolah untuk membuka lembar kerja dan fitur yang relevan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Student Portal -->
                <a href="{{ route('login') }}?role=student" class="group p-8 rounded-2xl bg-[#f8f9ff] border border-slate-200/90 hover:border-indigo-400 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-6 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <span class="material-symbols-outlined" style="font-size: 32px;">school</span>
                        </div>
                        <h3 class="font-headline font-bold text-slate-900 text-xl mb-2">Portal Siswa</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-6">
                            Ikuti ujian secara realtime, pantau durasi waktu, simpan jawaban otomatis, dan tinjau hasil nilai serta pembahasan.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-indigo-600 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Siswa</span>
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                    </div>
                </a>
                
                <!-- Teacher Portal -->
                <a href="{{ route('login') }}?role=teacher" class="group p-8 rounded-2xl bg-[#f8f9ff] border border-slate-200/90 hover:border-emerald-400 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition">
                            <span class="material-symbols-outlined" style="font-size: 32px;">person_apron</span>
                        </div>
                        <h3 class="font-headline font-bold text-slate-900 text-xl mb-2">Portal Guru</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-6">
                            Susun bank soal dengan cepat, jadwalkan ujian kelas, acak urutan soal, dan pantau daya serap butir soal secara mendalam.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-emerald-700 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Guru</span>
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                    </div>
                </a>
                
                <!-- Admin Portal -->
                <a href="{{ route('login') }}?role=admin" class="group p-8 rounded-2xl bg-[#f8f9ff] border border-slate-200/90 hover:border-slate-800 hover:shadow-xl hover:-translate-y-1.5 transition-all text-center no-underline flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mx-auto mb-6 group-hover:bg-slate-900 group-hover:text-white transition">
                            <span class="material-symbols-outlined" style="font-size: 32px;">admin_panel_settings</span>
                        </div>
                        <h3 class="font-headline font-bold text-slate-900 text-xl mb-2">Administrator</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-6">
                            Kelola data induk sekolah (siswa, guru, rombel kelas, mapel), pantau semua aktivitas ujian, serta audit log sistem.
                        </p>
                    </div>
                    <div class="font-bold text-xs text-slate-800 flex items-center justify-center gap-1 group-hover:gap-2 transition-all">
                        <span>Masuk sebagai Admin</span>
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="fitur" class="py-20 px-6 bg-slate-50 border-t border-slate-200/80">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2 block">Fitur Unggulan</span>
                    <h2 class="font-headline font-extrabold text-3xl md:text-4xl text-slate-900 tracking-tight mb-6">
                        Dirancang Khusus untuk Standar Ujian Sekolah Modern
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">smartphone</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-slate-900 text-sm">Responsif & Mobile-Friendly</h4>
                                <p class="text-xs text-slate-500 mt-1">Antarmuka siswa dirancang khusus agar sangat nyaman dikerjakan melalui smartphone, tablet, maupun laptop.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">sync</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-slate-900 text-sm">Penyimpanan Jawaban Otomatis (Anti Hilang)</h4>
                                <p class="text-xs text-slate-500 mt-1">Setiap opsi yang dipilih siswa langsung tersimpan ke server via AJAX. Jika jaringan terputus, pengerjaan dapat dilanjutkan aman.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined">analytics</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-slate-900 text-sm">Koreksi & Analitik Instan</h4>
                                <p class="text-xs text-slate-500 mt-1">Nilai langsung terkalkulasi otomatis dengan perbandingan batas tuntas KKM dan grafik sebaran daya serap butir soal.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 md:p-8 bg-white border border-slate-200 rounded-3xl shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-xs font-semibold text-slate-400">cbt.sekolah.sch.id</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-indigo-900">Ujian Berlangsung: Penilaian Akhir Semester</div>
                            <div class="text-[11px] text-indigo-600 mt-0.5">Mata Pelajaran: Matematika Wajib • Kelas XII IPA 1</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-bold">LIVE</span>
                    </div>
                    <div class="space-y-2 pt-2">
                        <div class="h-3 bg-slate-100 rounded-full w-full"></div>
                        <div class="h-3 bg-slate-100 rounded-full w-5/6"></div>
                        <div class="h-3 bg-slate-100 rounded-full w-4/6"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#0b1c30] text-slate-400 py-12 px-6 border-t border-slate-800">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
            <div class="flex items-center gap-2 text-white font-headline font-bold text-sm">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center text-white text-xs">E</div>
                EduExam CBT
            </div>
            <div>
                &copy; {{ date('Y') }} EduExam. Platform Ujian Digital Sekolah Indonesia.
            </div>
        </div>
    </footer>

</body>
</html>