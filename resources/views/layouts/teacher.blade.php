@extends('layouts.app')

@push('styles')
<style>
    body { background: var(--gray-50); }
    
    /* DESKTOP LAYOUT */
    .app-layout { display: flex; min-height: 100vh; }
    
    /* SIDEBAR */
    .sidebar { width: 260px; background: white; border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 40; transition: transform .3s; }
    .sidebar-header { height: 70px; display: flex; align-items: center; padding: 0 24px; border-bottom: 1px solid var(--gray-100); }
    .sidebar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .sidebar-brand .logo { width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.125rem; }
    .sidebar-brand .name { font-weight: 800; font-size: 1.25rem; color: var(--gray-900); }
    
    .sidebar-nav { flex: 1; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; gap: 4px; }
    .nav-header { font-size: 0.75rem; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 1px; margin: 16px 0 8px 12px; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: var(--radius-md); text-decoration: none; color: var(--gray-600); font-weight: 600; font-size: 0.9375rem; transition: all .2s; }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-900); text-decoration: none; }
    .nav-item.active { background: var(--primary-light); color: var(--primary-dark); }
    .nav-icon { font-size: 1.25rem; width: 24px; text-align: center; }
    
    .sidebar-footer { padding: 16px; border-top: 1px solid var(--gray-100); }
    .user-profile-sm { display: flex; align-items: center; gap: 12px; padding: 8px; border-radius: var(--radius-md); transition: background .2s; text-decoration: none; }
    .user-profile-sm:hover { background: var(--gray-100); text-decoration: none; }
    .user-avatar-sm { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .user-info-sm { overflow: hidden; }
    .user-name-sm { font-weight: 700; font-size: 0.875rem; color: var(--gray-800); white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
    .user-role-sm { font-size: 0.75rem; color: var(--gray-500); }
    
    /* MAIN CONTENT */
    .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-width: 0; }
    
    /* TOPBAR */
    .topbar { height: 70px; background: white; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 30; }
    .topbar-left { display: flex; align-items: center; gap: 16px; }
    .menu-toggle { display: none; background: none; border: none; font-size: 1.5rem; color: var(--gray-600); cursor: pointer; }
    .page-title { font-size: 1.25rem; font-weight: 800; color: var(--gray-900); margin: 0; }
    
    .topbar-right { display: flex; align-items: center; gap: 16px; }
    
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
@stack('teacher-styles')
@endpush

@section('content')
<div class="app-layout">
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('teacher.dashboard') }}" class="sidebar-brand">
                <div class="logo">E</div>
                <div class="name">EduExam</div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('teacher.dashboard') }}" class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            
            <div class="nav-header">Manajemen Ujian</div>
            <a href="{{ route('teacher.exams.index') }}" class="nav-item {{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
                <span class="nav-icon">📝</span> Data Ujian
            </a>
            
            <div class="nav-header">Bank Soal</div>
            <a href="{{ route('teacher.questions.index') }}" class="nav-item {{ request()->routeIs('teacher.questions.*') ? 'active' : '' }}">
                <span class="nav-icon">📚</span> Semua Soal
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="{{ route('teacher.profile') }}" class="user-profile-sm">
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="user-avatar-sm">
                <div class="user-info-sm">
                    <div class="user-name-sm">{{ auth()->user()->name }}</div>
                    <div class="user-role-sm">Guru Pengajar</div>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                <h1 class="page-title">@yield('page_title', 'Dashboard Guru')</h1>
            </div>
            
            <div class="topbar-right">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Keluar dari sistem?')">Keluar</button>
                </form>
            </div>
        </header>
        
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif
            
            @yield('teacher-content')
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
