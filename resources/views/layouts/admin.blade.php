@extends('layouts.app')

@push('admin-styles')
<script>
    if (typeof tailwind !== 'undefined') {
        tailwind.config.theme.extend.colors['primary'] = '#3525cd';
        tailwind.config.theme.extend.colors['primary-container'] = '#4f46e5';
        tailwind.config.theme.extend.colors['on-primary'] = '#ffffff';
        tailwind.config.theme.extend.colors['on-primary-container'] = '#dad7ff';
        tailwind.config.theme.extend.colors['primary-fixed'] = '#e0e7ff';
        tailwind.config.theme.extend.colors['on-primary-fixed'] = '#312e81';
        tailwind.config.theme.extend.colors['secondary'] = '#006c49';
        tailwind.config.theme.extend.colors['secondary-container'] = '#6cf8bb';
        tailwind.config.theme.extend.colors['on-secondary-container'] = '#00714d';
        tailwind.config.theme.extend.colors['surface-container-lowest'] = '#ffffff';
        tailwind.config.theme.extend.colors['surface-container-low'] = '#eff4ff';
        tailwind.config.theme.extend.colors['surface-container'] = '#e5eeff';
        tailwind.config.theme.extend.colors['surface-container-high'] = '#dce9ff';
        tailwind.config.theme.extend.colors['surface-container-highest'] = '#d3e4fe';
    }
</script>
<style>
    ::-webkit-scrollbar { display: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-surface text-on-surface font-body-md antialiased">

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Aside Sidebar (Navbar Admin) -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
        <div class="flex flex-col min-h-0 flex-1">
            <!-- Brand Header -->
            <div class="h-20 px-space-lg flex items-center justify-between bg-surface-container-low/50 shrink-0 border-b border-surface-container-low/80">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-space-sm no-underline min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-md flex-shrink-0">
                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>

                    <div class="flex flex-col ml-space-xs min-w-0">
                        <div class="flex items-center gap-space-xs">
                            <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight font-bold">EduExam</span>
                            <span class="px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm">Portal Admin</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    </div>
                </a>
                <button type="button" class="lg:hidden p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container" onclick="toggleSidebar()" title="Tutup Menu">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Scrollable Nav List -->
            <div class="px-space-md py-space-sm overflow-y-auto flex-1 no-scrollbar">
                <!-- Section: Menu Utama -->
                <div class="px-space-md py-space-xs mb-space-xs">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Menu Utama</span>
                </div>
                <nav class="flex flex-col gap-1">
                    <!-- Dashboard -->
                    @php $isDashboard = request()->routeIs('admin.dashboard'); @endphp
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-space-md py-2.5 transition-colors rounded-lg font-label-lg text-label-lg {{ $isDashboard ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        <span>Dashboard</span>
                    </a>

                    <!-- Data Siswa -->
                    @php $isStudent = request()->routeIs('admin.students*'); @endphp
                    <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isStudent ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">group</span>
                        <span>Data Siswa</span>
                    </a>

                    <!-- Data Guru -->
                    @php $isTeacher = request()->routeIs('admin.teachers*'); @endphp
                    <a href="{{ route('admin.teachers') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isTeacher ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                        <span>Data Guru</span>
                    </a>

                    <!-- Data Kelas -->
                    @php $isClassroom = request()->routeIs('admin.classrooms*'); @endphp
                    <a href="{{ route('admin.classrooms') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isClassroom ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                        <span>Data Kelas</span>
                    </a>

                    <!-- Mata Pelajaran -->
                    @php $isSubject = request()->routeIs('admin.subjects*'); @endphp
                    <a href="{{ route('admin.subjects') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isSubject ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                        <span>Mata Pelajaran</span>
                    </a>

                    <!-- Semua Ujian -->
                    @php $isExam = request()->routeIs('admin.exams*'); @endphp
                    <a href="{{ route('admin.exams') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isExam ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                        <span>Semua Ujian</span>
                    </a>

                    <!-- Tahun Ajaran -->
                    @php $isYear = request()->routeIs('admin.academic-years*'); @endphp
                    <a href="{{ route('admin.academic-years') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isYear ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                        <span>Tahun Ajaran</span>
                    </a>
                </nav>

                <!-- Section: Sistem -->
                <div class="px-space-md pt-space-md pb-space-xs mt-space-sm">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Sistem</span>
                </div>
                <nav class="flex flex-col gap-1">
                    <!-- Pengaturan Sistem -->
                    @php $isSetting = request()->routeIs('admin.settings*'); @endphp
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isSetting ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                        <span>Pengaturan Sistem</span>
                    </a>

                    <!-- Log Aktivitas -->
                    @php $isLog = request()->routeIs('admin.logs*'); @endphp
                    <a href="{{ route('admin.logs') }}" class="flex items-center gap-3 px-space-md py-2.5 rounded-lg font-label-lg text-label-lg transition-colors {{ $isLog ? 'bg-primary-container text-on-primary font-semibold shadow-[0_1px_3px_0_rgba(15,23,42,0.06)]' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                        <span>Log Aktivitas</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Bottom School & Logout Bar -->
        <div class="p-space-md m-space-md rounded-xl bg-surface-container-low flex items-center justify-between shrink-0 border border-slate-100">
            <div class="flex items-center gap-space-sm min-w-0">
                <span class="material-symbols-outlined text-secondary text-[22px] shrink-0" style="font-variation-settings: 'FILL' 1;">verified</span>
                <div class="flex flex-col min-w-0">
                    <span class="font-label-md text-label-md text-on-surface truncate font-semibold">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant truncate">NPSN: {{ \App\Models\SchoolSetting::get('school_npsn', '20103482') }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0 shrink-0">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-on-error-container transition-colors" title="Keluar" onclick="return confirm('Keluar dari portal administrator?')">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        <!-- Topbar / Header -->
        <header class="fixed top-0 left-0 lg:left-72 right-0 h-20 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-md md:px-space-lg">
            <div class="flex items-center flex-1 max-w-lg mr-space-md">
                <button type="button" class="lg:hidden mr-2.5 p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors" onclick="toggleSidebar()" title="Buka Menu">
                    <span class="material-symbols-outlined text-[22px]">menu</span>
                </button>
                <form action="{{ route('admin.students') }}" method="GET" class="w-full flex items-center bg-surface-container-low rounded-lg px-3 py-1.5 focus-within:ring-2 focus-within:ring-primary-container transition-all">
                    <span class="material-symbols-outlined text-outline text-[20px] mr-2">search</span>
                    <input name="search" class="w-full bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface placeholder:text-outline" placeholder="Cari data siswa (nama, NIS, email)..." type="text" value="{{ request('search') }}"/>
                </form>
            </div>
            <div class="flex items-center gap-space-md">
                @php
                    $headerActiveYear = \App\Models\AcademicYear::where('is_active', true)->first();
                @endphp
                <div class="hidden xl:flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-[16px]">school</span>
                    <span>Tahun Ajaran {{ $headerActiveYear ? $headerActiveYear->name : '2026/2027' }} • {{ ($headerActiveYear && $headerActiveYear->semester == 2) ? 'Genap' : 'Ganjil' }} (Aktif)</span>
                </div>
                <button class="relative p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" type="button" title="Notifikasi Sistem">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-error rounded-full ring-2 ring-surface-container-lowest"></span>
                </button>
                <div class="h-8 w-px bg-surface-container-high hidden sm:block"></div>
                <div class="flex items-center gap-3">
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="font-label-lg text-label-lg text-on-surface leading-tight font-semibold">{{ auth()->user()?->name ?? 'Administrator' }}</span>
                        <span class="font-label-sm text-label-sm text-secondary leading-tight font-medium">{{ auth()->user()?->role === 'admin' ? 'Administrator Sistem' : 'Staff Admin' }}</span>
                    </div>
                    <img alt="Profile" class="w-8 h-8 rounded-full object-cover ring-2 ring-surface-container-high" src="{{ auth()->user()?->avatar_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBgUVH_R0crxrhsjBVkSdY1Slm5HWJBUN8t9o3-Zl2Flebi-ObCvjt5o6TXQ6idNvsgaU3CdsU-JeLfIiIkJvfuzoloKrSaE25Cw8uAyEMnEPXfee6PwgtmsMmhIlMfR28q7uiS1oa-5eIOChjdrUyl8krMY8TMFeh7fPu06m5GVkMwQoESxWwyoWmLf_1d30NaFmhojZWbkswxQxQ1rdLoe3rFHqb82HmobLdYpexSOLIfo_J9oRMC' }}"/>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="w-full pt-20 bg-surface min-h-screen px-space-md md:px-space-lg py-space-lg flex-1">
            @if(session('success'))
                <div class="mb-space-md p-space-md rounded-xl bg-secondary-container text-on-secondary-container flex items-center gap-2 text-xs font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-space-md p-space-md rounded-xl bg-error-container text-on-error-container flex items-center gap-2 text-xs font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mb-space-md p-space-md rounded-xl bg-surface-container-high text-primary flex items-center gap-2 text-xs font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @yield('admin-content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }
</script>
@endsection
