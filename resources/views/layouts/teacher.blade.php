@extends('layouts.app')

@push('styles')
<script>
    (function() {
        try {
            if (localStorage.getItem('edu_teacher_sidebar_collapsed') === 'true' && window.innerWidth >= 1024) {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch(e) {}
    })();
</script>
<style>
    /* Smooth transitions */
    #sidebar {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #mainWrapper {
        transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Desktop Collapsed Rules */
    @media (min-width: 1024px) {
        .sidebar-collapsed #sidebar {
            width: 5rem !important; /* 80px */
        }
        .sidebar-collapsed #mainWrapper {
            padding-left: 5rem !important; /* 80px */
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-brand-full,
        .sidebar-collapsed .sidebar-help-full,
        .sidebar-collapsed .sidebar-section-title {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-brand-mini {
            display: flex !important;
        }
        .sidebar-collapsed .sidebar-help-mini {
            display: flex !important;
        }
        .sidebar-collapsed .sidebar-bottom-full {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-bottom-mini {
            display: flex !important;
        }
        .sidebar-collapsed .sidebar-nav-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 2.75rem !important;
            height: 2.75rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }

    /* Mobile Drawer Rules */
    @media (max-width: 1023px) {
        #sidebar {
            transform: translateX(-100%);
        }
        #sidebar.open {
            transform: translateX(0) !important;
        }
        #mainWrapper {
            padding-left: 0 !important;
        }
        #sidebarOverlay.show {
            display: block !important;
        }
        .sidebar-brand-mini,
        .sidebar-help-mini,
        .sidebar-bottom-mini {
            display: none !important;
        }
    }
</style>
@stack('teacher-styles')
@endpush

@section('content')

@php
    $teacherUser = auth()->user();
    $activeYear = \App\Models\AcademicYear::getActive();
    $unreadNotif = $teacherUser ? $teacherUser->notifications()->where('is_read', false)->count() : 0;
    $adminEmail = \App\Models\User::admins()->value('email');

    $navActive = 'bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm';
    $navIdle = 'font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface';

    $isResults = request()->routeIs('teacher.exams.results', 'teacher.exams.student_detail', 'teacher.exams.analytics')
        || (request()->routeIs('teacher.exams.index') && request('status') === 'completed');
    $isExams = request()->routeIs('teacher.exams*') && !$isResults;

    $navItems = [
        ['label' => 'Dashboard',       'icon' => 'dashboard',  'url' => route('teacher.dashboard'),       'active' => request()->routeIs('teacher.dashboard')],
        ['label' => 'Bank Soal',       'icon' => 'quiz',       'url' => route('teacher.questions.index'), 'active' => request()->routeIs('teacher.questions*')],
        ['label' => 'Manajemen Ujian', 'icon' => 'assignment', 'url' => route('teacher.exams.index'),     'active' => $isExams],
        ['label' => 'Hasil Ujian',     'icon' => 'fact_check', 'url' => route('teacher.exams.index', ['status' => 'completed']), 'active' => $isResults],
        ['label' => 'Profil Guru',     'icon' => 'person',     'url' => route('teacher.profile'),         'active' => request()->routeIs('teacher.profile')],
    ];
@endphp
<div class="min-h-screen flex bg-surface text-on-surface font-body-md antialiased">

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between overflow-hidden">
        <div class="flex flex-col min-h-0">
            <!-- Full Brand Header (Expanded) -->
            <div class="sidebar-brand-full h-20 px-space-lg flex items-center justify-between bg-surface-container-low/50">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-space-sm no-underline min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-md flex-shrink-0">
                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>
                    <div class="flex flex-col ml-space-xs min-w-0">
                        <div class="flex items-center gap-space-xs">
                            <span class="font-headline-sm text-headline-sm text-on-surface tracking-tight font-bold">EduExam</span>
                            <span class="px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm">Portal Guru</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">SMA Nusantara</span>
                    </div>
                </a>
                
            </div>

            <!-- Mini Brand Header (Collapsed) -->
            <div class="sidebar-brand-mini hidden h-20 w-full items-center justify-center bg-surface-container-low/50">
                <button type="button" onclick="toggleSidebarCollapse()" class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary shadow-md hover:scale-105 transition-transform cursor-pointer" title="Buka Sidebar">
                    <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                </button>
            </div>

            <div class="sidebar-section-title px-space-md pt-space-md pb-space-sm">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider px-space-sm">Menu Utama</span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-space-xs px-space-md overflow-y-auto">
                @foreach($navItems as $item)
                    <a href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif
                       title="{{ $item['label'] }}"
                       class="sidebar-nav-item flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors {{ $item['active'] ? $navActive : $navIdle }}">
                        <span class="material-symbols-outlined text-[20px] flex-shrink-0">{{ $item['icon'] }}</span>
                        <span class="sidebar-text truncate">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Bottom Controls Section -->
        <div class="flex flex-col shrink-0">
            <!-- Full Help Center Card (Expanded) -->
            <div class="sidebar-help-full p-space-sm mx-space-md mb-2 rounded-xl bg-surface-container-low flex flex-col gap-1">
                <div class="flex items-center justify-between">
                    <span class="font-label-sm text-[11px] text-on-surface-variant font-semibold">Pusat Bantuan</span>
                    <span class="material-symbols-outlined text-[16px] text-primary">help</span>
                </div>
                <p class="font-body-sm text-[11px] text-on-surface-variant leading-tight">Kendala teknis soal atau ujian?</p>
                <a href="{{ $adminEmail ? 'mailto:' . $adminEmail : '#' }}" class="inline-flex items-center justify-center py-1 px-space-xs rounded bg-surface-container-lowest text-primary font-label-sm text-[11px] hover:bg-surface-container-high transition-colors no-underline">Hubungi Admin</a>
            </div>

            <!-- Mini Help Icon (Collapsed) -->
            <div class="sidebar-help-mini hidden p-1 my-1 mx-auto flex-col items-center shrink-0">
                <a href="{{ $adminEmail ? 'mailto:' . $adminEmail : '#' }}" class="w-10 h-10 rounded-lg bg-surface-container-low flex items-center justify-center text-primary hover:bg-surface-container-high transition-colors" title="Pusat Bantuan (Hubungi Admin)">
                    <span class="material-symbols-outlined text-[20px]">help</span>
                </a>
            </div>

           

           
        </div>
    </aside>

    <!-- Main Content Area -->
    <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 pl-0 lg:pl-72">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 h-20 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex items-center justify-between gap-space-md px-4 sm:px-6 lg:px-space-xl">
            <div class="flex items-center gap-space-md min-w-0">
                <!-- Mobile toggle -->
                <button type="button" class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high" onclick="toggleMobileSidebar()" aria-label="Buka menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <!-- Desktop toggle (collapse / expand) -->
                <button type="button" id="sidebarCollapseBtn" class="hidden lg:flex w-10 h-10 rounded-lg items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer" onclick="toggleSidebarCollapse()" title="Buka / Tutup Sidebar" aria-label="Buka atau tutup sidebar">
                    <span class="material-symbols-outlined scale-x-[-1]">dock_to_left</span>
                </button>
                
                @php
                    $isQuestionPage = request()->routeIs('teacher.questions*');
                    $searchAction = $isQuestionPage ? route('teacher.questions.index') : route('teacher.exams.index');
                    $searchPlaceholder = $isQuestionPage ? 'Cari bank soal, topik, atau mapel...' : 'Cari ujian, mata pelajaran, atau kelas...';
                    $searchVal = $isQuestionPage ? (request()->routeIs('teacher.questions.index') ? request('search') : '') : (request()->routeIs('teacher.exams.index') ? request('search') : '');
                @endphp
                <form action="{{ $searchAction }}" method="GET" class="relative hidden md:flex items-center">
                    <span class="material-symbols-outlined absolute left-space-md text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input name="search" value="{{ $searchVal }}" class="w-64 xl:w-80 h-10 pl-10 pr-space-md bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant border-0 focus:outline-none focus:ring-0 focus:bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] transition-all" placeholder="{{ $searchPlaceholder }}" type="text"/>
                </form>
                
                <h1 class="md:hidden font-headline-sm text-headline-sm text-on-surface font-bold truncate">@yield('page_title', 'Portal Guru')</h1>
                @if($activeYear)
                    <div class="hidden xl:flex items-center gap-1.5 px-space-md py-1.5 rounded-full bg-surface-container-high text-on-surface">
                        <span class="material-symbols-outlined text-[16px] text-primary">event</span>
                        <span class="font-label-sm text-label-sm font-semibold">Semester {{ ucfirst($activeYear->semester) }} {{ $activeYear->name }}</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-space-sm sm:gap-space-md flex-shrink-0">
                <!-- Dark / Light Mode Toggle Button -->
                <button type="button" 
                        id="themeToggleBtn" 
                        class="w-10 h-10 rounded-xl bg-surface-container-low hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer focus:outline-none" 
                        aria-label="Alihkan tema gelap/terang"
                        title="Alihkan mode tema">
                    <span id="themeMoonIcon" class="material-symbols-outlined text-[20px]">dark_mode</span>
                    <span id="themeSunIcon" class="material-symbols-outlined text-[20px] text-amber-400 hidden">light_mode</span>
                </button>
                @include('partials.notification-dropdown')
                <a href="{{ route('teacher.profile') }}" class="flex items-center gap-space-sm pl-space-sm no-underline" title="Profil & Pengaturan Akun">
                    <img alt="Foto profil" class="w-8 h-8 rounded-full object-cover shadow-[0_1px_8px_rgba(0,0,0,0.04)] ring-1 ring-slate-200 dark:ring-slate-700" src="{{ $teacherUser ? $teacherUser->avatar_url : '' }}"/>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="font-label-lg text-label-lg text-on-surface">{{ $teacherUser ? $teacherUser->name : 'Guru' }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $teacherUser && $teacherUser->nip ? 'NIP ' . $teacherUser->nip : 'Guru Pengajar' }}</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 md:p-8">
            @if(session('success'))
                <div class="alert alert-success">
                    <span class="material-symbols-outlined text-emerald-600" style="font-size: 20px;">check_circle</span>
                    <span class="font-medium text-xs">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <span class="material-symbols-outlined text-rose-600" style="font-size: 20px;">error</span>
                    <span class="font-medium text-xs">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">info</span>
                    <span class="font-medium text-xs">{{ session('info') }}</span>
                </div>
            @endif
            
            @yield('teacher-content')
        </main>
    </div>
</div>

<script>
    // Mobile Drawer Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
    }

    // Desktop Collapsible Sidebar Toggle
    function toggleSidebarCollapse() {
        const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
        document.body.classList.toggle('sidebar-collapsed', isCollapsed);
        try {
            localStorage.setItem('edu_teacher_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch(e) {}
    }
</script>
@stack('teacher-scripts')
@endsection


