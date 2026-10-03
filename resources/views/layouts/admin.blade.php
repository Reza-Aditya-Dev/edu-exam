@extends('layouts.app')

@push('styles')
<style>
    body { background: var(--gray-50); }
    
    /* DESKTOP LAYOUT */
    .app-layout { display: flex; min-height: 100vh; }
    
    /* SIDEBAR */
    .sidebar { width: 260px; background: #0f172a; border-right: none; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 40; transition: transform .3s; }
    .sidebar-header { height: 70px; display: flex; align-items: center; padding: 0 24px; border-bottom: 1px solid rgba(255,255,255,0.1); background: #0b1121; }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .sidebar-brand .logo { width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.125rem; }
    .sidebar-brand .name { font-weight: 800; font-size: 1.25rem; color: white; }
    
    .sidebar-nav { flex: 1; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; gap: 4px; }
    .nav-header { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 16px 0 8px 12px; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: var(--radius-md); text-decoration: none; color: #cbd5e1; font-weight: 600; font-size: 0.9375rem; transition: all .2s; }
    .nav-item:hover { background: rgba(255,255,255,0.1); color: white; text-decoration: none; }
    .nav-item.active { background: var(--primary); color: white; }
    .nav-icon { font-size: 1.25rem; width: 24px; text-align: center; }
    
    /* MAIN CONTENT */
    .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-width: 0; }
    
    /* TOPBAR */
    .topbar { height: 70px; background: white; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 30; }
    .topbar-left { display: flex; align-items: center; gap: 16px; }
    .menu-toggle { display: none; background: none; border: none; font-size: 1.5rem; color: var(--gray-600); cursor: pointer; }
    .page-title { font-size: 1.25rem; font-weight: 800; color: var(--gray-900); margin: 0; }
    
    .topbar-right { display: flex; align-items: center; gap: 16px; }
    .admin-badge { background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 800; letter-spacing: .5px; }
    
    /* CONTENT AREA */
    .content-area { padding: 32px; flex: 1; overflow-y: auto; }
    
    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .main-content { margin-left: 0; }
        .menu-toggle { display: block; }
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 35; display: none; }
        .sidebar-overlay.show { display: block; }
        .content-area { padding: 20px; }
        .topbar { padding: 0 20px; }
    }
</style>
@stack('admin-styles')
@endpush

@section('content')
<div class="app-layout">
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <div class="logo">E</div>
                <div class="name">EduExam</div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
            </a>
            
            <div class="nav-header">Master Data</div>
            <a href="{{ route('admin.students') }}" class="nav-item {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                <i class="bi bi-people-fill nav-icon"></i> Data Siswa
            </a>
            <a href="{{ route('admin.teachers') }}" class="nav-item {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}">
                <i class="bi bi-person-workspace nav-icon"></i> Data Guru
            </a>
            <a href="{{ route('admin.classrooms') }}" class="nav-item {{ request()->routeIs('admin.classrooms*') ? 'active' : '' }}">
                <i class="bi bi-building nav-icon"></i> Kelas & Ruangan
            </a>
            <a href="{{ route('admin.subjects') }}" class="nav-item {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}">
                <i class="bi bi-book-half nav-icon"></i> Mata Pelajaran
            </a>
            <a href="{{ route('admin.academic-years') }}" class="nav-item {{ request()->routeIs('admin.academic-years*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range-fill nav-icon"></i> Tahun Ajaran
            </a>
            
            <div class="nav-header">Monitoring</div>
            <a href="{{ route('admin.exams') }}" class="nav-item {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill nav-icon"></i> Semua Ujian
            </a>
            <a href="{{ route('admin.logs') }}" class="nav-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <i class="bi bi-clock-history nav-icon"></i> Log Aktivitas
            </a>
            
            <div class="nav-header">Sistem</div>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill nav-icon"></i> Pengaturan
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
                <h1 class="page-title">@yield('page_title', 'Admin Panel')</h1>
            </div>
            
            <div class="topbar-right">
                <span class="admin-badge">ADMINISTRATOR</span>
                <span class="font-bold mr-2">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Keluar dari sistem?')">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </header>
        
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
            @endif
            
            @yield('admin-content')
        </div>
    </main>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
@endsection
