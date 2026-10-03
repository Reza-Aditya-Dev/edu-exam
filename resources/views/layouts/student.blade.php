@extends('layouts.app')

@push('styles')
<style>
    body { background: var(--gray-50); padding-bottom: 72px; }

    /* TOP HEADER */
    .mobile-header {
        position: sticky; top: 0; z-index: 100;
        background: var(--white); padding: 14px 16px;
        border-bottom: 1px solid var(--gray-100); box-shadow: var(--shadow-sm);
        display: flex; align-items: center; justify-content: space-between;
    }
    .mobile-header .brand { display: flex; align-items: center; gap: 10px; }
    .mobile-header .brand .logo { width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.875rem; font-weight: 800; }
    .mobile-header .brand-name { font-size: 1.0625rem; font-weight: 800; color: var(--gray-900); }
    .header-actions { display: flex; gap: 8px; align-items: center; }
    .notif-btn {
        position: relative; width: 38px; height: 38px; border-radius: 50%;
        border: none; background: var(--gray-100); cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        transition: background .15s;
    }
    .notif-btn:hover { background: var(--primary-light); }
    .notif-badge {
        position: absolute; top: 2px; right: 2px;
        min-width: 18px; height: 18px; border-radius: 9px;
        background: var(--danger); color: white; font-size: 10px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; padding: 0 4px;
        border: 2px solid white;
    }

    /* PAGE CONTENT */
    .page-content { padding: 0 16px; max-width: 520px; margin: 0 auto; }

    /* BOTTOM NAV */
    .bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
        background: var(--white); border-top: 1px solid var(--gray-100);
        display: flex; padding: 8px 0 12px; box-shadow: 0 -4px 16px rgba(0,0,0,.07);
    }
    .nav-item {
        flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
        text-decoration: none; color: var(--gray-400); transition: color .15s; padding: 4px;
    }
    .nav-item:hover { text-decoration: none; }
    .nav-item.active { color: var(--primary); }
    .nav-item .nav-icon { font-size: 1.375rem; line-height: 1; }
    .nav-item .nav-label { font-size: 0.6875rem; font-weight: 600; }
    .nav-item .nav-dot {
        width: 4px; height: 4px; background: var(--primary);
        border-radius: 2px; opacity: 0;
    }
    .nav-item.active .nav-dot { opacity: 1; }

    /* SECTION HEADERS */
    .section-title {
        font-size: 1rem; font-weight: 700; color: var(--gray-800);
        margin: 20px 0 12px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .section-title a { font-size: 0.8125rem; font-weight: 500; color: var(--primary); }

    /* FLASH MESSAGES */
    .flash-container { padding: 12px 16px 0; max-width: 520px; margin: 0 auto; }
</style>
@stack('mobile-styles')
@endpush

@section('content')
<div class="mobile-layout">
    <!-- Header -->
    <header class="mobile-header">
        <div class="brand">
            <div class="logo">E</div>
            <span class="brand-name">EduExam</span>
        </div>
        <div class="header-actions">
            <button class="notif-btn" onclick="window.location='#notifications'" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>
           
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info d-flex align-items-center gap-2"><i class="bi bi-info-circle-fill"></i> {{ session('info') }}</div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="page-content" style="padding-top: 16px;">
        @yield('student-content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <div class="nav-dot"></div>
            <i class="bi bi-house-door-fill nav-icon"></i>
            <span class="nav-label">Beranda</span>
        </a>
        <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.exam.*') ? 'active' : '' }}">
            <div class="nav-dot"></div>
            <i class="bi bi-journal-text nav-icon"></i>
            <span class="nav-label">Ujian</span>
        </a>
        <a href="{{ route('student.history') }}" class="nav-item {{ request()->routeIs('student.history') ? 'active' : '' }}">
            <div class="nav-dot"></div>
            <i class="bi bi-bar-chart-line-fill nav-icon"></i>
            <span class="nav-label">Riwayat</span>
        </a>
        <a href="{{ route('student.profile') }}" class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <div class="nav-dot"></div>
            <i class="bi bi-person-circle nav-icon"></i>
            <span class="nav-label">Profil</span>
        </a>
    </nav>
</div>
@endsection
