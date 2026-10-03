@extends('layouts.app')

@push('teacher-styles')
<style>
    /* Responsive mobile drawer */
    @media (max-width: 1024px) {
        #sidebar { transform: translateX(-100%); }
        #sidebar.open { transform: translateX(0); }
        #sidebarOverlay.show { display: block; }
    }
</style>
@endpush

@section('content')
@php
    $teacherUser = auth()->user();
    $activeYear = \App\Models\AcademicYear::getActive();
    $unreadNotif = $teacherUser->notifications()->where('is_read', false)->count();
    $adminEmail = \App\Models\User::admins()->value('email');

    $navActive = 'bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm';
    $navIdle = 'font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface';

    $isResults = request()->routeIs('teacher.exams.results', 'teacher.exams.student_detail')
        || (request()->routeIs('teacher.exams.index') && request('status') === 'completed' && request('view') !== 'analytics');
    $isAnalytics = request()->routeIs('teacher.exams.analytics')
        || (request()->routeIs('teacher.exams.index') && request('view') === 'analytics');
    $isExams = request()->routeIs('teacher.exams*') && !$isResults && !$isAnalytics;

    $navItems = [
        ['label' => 'Dashboard',       'icon' => 'dashboard',  'url' => route('teacher.dashboard'),       'active' => request()->routeIs('teacher.dashboard')],
        ['label' => 'Bank Soal',       'icon' => 'quiz',       'url' => route('teacher.questions.index'), 'active' => request()->routeIs('teacher.questions*')],
        ['label' => 'Manajemen Ujian', 'icon' => 'assignment', 'url' => route('teacher.exams.index'),     'active' => $isExams],
        ['label' => 'Hasil Ujian',     'icon' => 'fact_check', 'url' => route('teacher.exams.index', ['status' => 'completed']), 'active' => $isResults],
        ['label' => 'Analisis Ujian',  'icon' => 'analytics',  'url' => route('teacher.exams.index', ['status' => 'completed', 'view' => 'analytics']), 'active' => $isAnalytics],
        ['label' => 'Profil Guru',     'icon' => 'person',     'url' => route('teacher.profile'),         'active' => request()->routeIs('teacher.profile')],
    ];
@endphp
<div class="min-h-screen flex bg-surface text-on-surface font-body-md antialiased">

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0">
        <div class="flex flex-col min-h-0">
            <!-- Brand Header -->
            <a href="{{ route('teacher.dashboard') }}" class="h-20 px-space-lg flex items-center gap-space-sm bg-surface-container-low/50 no-underline">
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

            <div class="px-space-md pt-space-md pb-space-sm">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider px-space-sm">Menu Utama</span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-space-xs px-space-md overflow-y-auto">
                @foreach($navItems as $item)
                    <a href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif
                       class="flex items-center gap-space-sm px-space-md py-space-sm rounded-lg transition-colors {{ $item['active'] ? $navActive : $navIdle }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Help Center Card -->
        <div class="p-space-md m-space-md rounded-xl bg-surface-container-low flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Pusat Bantuan</span>
                <span class="material-symbols-outlined text-[18px] text-primary">help</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Perlu kendala teknis pembuatan soal?</p>
            <a href="{{ $adminEmail ? 'mailto:' . $adminEmail : '#' }}" class="inline-flex items-center justify-center py-1.5 px-space-sm rounded bg-surface-container-lowest text-primary font-label-sm text-label-sm shadow-[0_1px_8px_rgba(0,0,0,0.04)] hover:bg-surface-container-high hover:text-on-surface transition-colors">Hubungi Admin</a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 h-20 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] flex items-center justify-between gap-space-md px-4 sm:px-6 lg:px-space-xl">
            <div class="flex items-center gap-space-md min-w-0">
                <button type="button" class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high" onclick="toggleSidebar()" aria-label="Buka menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <form action="{{ route('teacher.exams.index') }}" method="GET" class="relative hidden md:flex items-center">
                    <span class="material-symbols-outlined absolute left-space-md text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input name="search" value="{{ request()->routeIs('teacher.exams.index') ? request('search') : '' }}" class="w-64 xl:w-80 h-10 pl-10 pr-space-md bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant border-0 focus:outline-none focus:ring-0 focus:bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] transition-all" placeholder="Cari ujian, mata pelajaran, atau topik..." type="text"/>
                </form>
                <h1 class="md:hidden font-headline-sm text-headline-sm text-on-surface font-bold truncate">@yield('page_title', 'Portal Guru')</h1>
                @if($activeYear)
                    <div class="hidden xl:flex items-center gap-1.5 px-space-md py-1.5 rounded-full bg-surface-container-high text-on-surface">
                        <span class="material-symbols-outlined text-[16px] text-primary">event</span>
                        <span class="font-label-sm text-label-sm font-semibold">Semester {{ ucfirst($activeYear->semester) }} {{ $activeYear->name }}</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-space-md flex-shrink-0">
                <button aria-label="Notifikasi" title="{{ $unreadNotif }} notifikasi belum dibaca" class="relative p-space-sm rounded-full bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" type="button">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                    @if($unreadNotif > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-error ring-2 ring-surface-container-lowest"></span>
                    @endif
                </button>
                <a href="{{ route('teacher.profile') }}" class="flex items-center gap-space-sm pl-space-sm no-underline">
                    <img alt="Foto profil" class="w-8 h-8 rounded-full object-cover shadow-[0_1px_8px_rgba(0,0,0,0.04)]" src="{{ $teacherUser->avatar_url }}"/>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="font-label-lg text-label-lg text-on-surface">{{ $teacherUser->name }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $teacherUser->nip ? 'NIP ' . $teacherUser->nip : 'Guru Pengajar' }}</span>
                    </div>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="p-space-sm rounded-full bg-surface-container-low text-on-surface-variant hover:bg-error-container hover:text-on-error-container transition-colors" title="Keluar" onclick="return confirm('Keluar dari sistem?')">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
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
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
@endsection
