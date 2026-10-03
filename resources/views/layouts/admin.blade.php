@extends('layouts.app')

@push('admin-styles')
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
<div class="min-h-screen flex bg-slate-50 text-slate-900 font-sans">
    
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-35 hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 bottom-0 left-0 w-68 bg-[#0b1c30] border-r border-slate-800/80 flex flex-col z-40 transition-transform duration-300 ease-in-out lg:translate-x-0">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800/80 bg-[#081525]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-white no-underline">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-extrabold text-lg text-white shadow-md shadow-indigo-600/30">
                    E
                </div>
                <div>
                    <div class="font-headline font-extrabold text-base tracking-tight text-white leading-none">EduExam</div>
                    <div class="text-[10px] text-indigo-300 font-semibold uppercase tracking-wider mt-0.5">Admin Portal</div>
                </div>
            </a>
        </div>
        
        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">dashboard</span>
                <span>Dashboard</span>
            </a>
            
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3.5 pt-4 pb-1 font-headline">
                Master Data
            </div>
            
            <a href="{{ route('admin.students') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.students*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">school</span>
                <span>Data Siswa</span>
            </a>
            
            <a href="{{ route('admin.teachers') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.teachers*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">badge</span>
                <span>Data Guru</span>
            </a>
            
            <a href="{{ route('admin.classrooms') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.classrooms*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">meeting_room</span>
                <span>Kelas & Ruangan</span>
            </a>
            
            <a href="{{ route('admin.subjects') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.subjects*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">menu_book</span>
                <span>Mata Pelajaran</span>
            </a>
            
            <a href="{{ route('admin.academic-years') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.academic-years*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">calendar_month</span>
                <span>Tahun Ajaran</span>
            </a>
            
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3.5 pt-4 pb-1 font-headline">
                Monitoring & Audit
            </div>
            
            <a href="{{ route('admin.exams') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.exams*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">assignment</span>
                <span>Semua Ujian</span>
            </a>
            
            <a href="{{ route('admin.logs') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.logs*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">history</span>
                <span>Log Aktivitas</span>
            </a>
            
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3.5 pt-4 pb-1 font-headline">
                Konfigurasi
            </div>
            
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <span class="material-symbols-outlined" style="font-size: 20px;">settings</span>
                <span>Pengaturan Sistem</span>
            </a>
        </nav>

        <!-- Sidebar Footer User Profile -->
        <div class="p-4 border-t border-slate-800/80 bg-[#081525]">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-slate-700 flex-shrink-0">
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium truncate">Administrator</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 flex-shrink-0">
                    @csrf
                    <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-slate-800/80 transition" title="Keluar">
                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-68">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur-sm border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <button type="button" class="lg:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-700 hover:bg-slate-100" onclick="toggleSidebar()">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="font-headline font-bold text-slate-900 text-base md:text-lg leading-tight">
                    @yield('page_title', 'Admin Panel')
                </h1>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Admin
                </span>
                <span class="text-xs font-semibold text-slate-700 hidden md:inline-block">
                    {{ auth()->user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm text-xs font-semibold px-3 py-1.5 flex items-center gap-1 hover:text-rose-600" onclick="return confirm('Keluar dari sistem?')">
                        <span class="material-symbols-outlined" style="font-size: 16px;">logout</span>
                        <span class="hidden sm:inline">Keluar</span>
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
            
            @yield('admin-content')
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
