@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem — EduExam')
@section('page_title', 'Audit & Log Aktivitas')

@push('admin-styles')
<style>
    /* Desain Elegan Khusus Log Aktivitas */
    .filter-section {
        background: var(--white);
        padding: 24px;
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }
    
    .timeline-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .timeline-container {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: -20px;
        left: 19px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-border), var(--gray-200));
        border-radius: 2px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 28px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--white);
        border: 2px solid var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .icon-create { color: var(--success); background: var(--success-light); }
    .icon-update { color: var(--primary); background: var(--primary-light); }
    .icon-delete { color: var(--danger); background: var(--danger-light); }
    .icon-default { color: var(--gray-600); background: var(--gray-100); }
    
    .timeline-content {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .timeline-content:hover {
        transform: translateY(-3px) translateX(6px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border-color: var(--primary-border);
    }
    
    .log-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .log-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--gray-900);
        text-transform: capitalize;
    }
    
    .log-meta {
        font-size: 0.85rem;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }
    
    .log-body {
        font-size: 0.95rem;
        color: var(--gray-700);
        line-height: 1.6;
        background: var(--gray-50);
        padding: 14px 18px;
        border-radius: var(--radius-md);
        border: 1px dashed var(--gray-300);
        font-family: monospace;
    }
    
    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gray-100);
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gray-800);
        border: 1px solid var(--gray-200);
    }
</style>
@endpush

@section('admin-content')

<div class="filter-section">
    <form action="{{ route('admin.logs') }}" method="GET" class="flex items-end gap-4 flex-wrap">
        <div class="flex-1 min-w-[200px]">
            <label class="form-label font-bold text-gray-700 mb-2">Filter Pengguna</label>
            <select name="user_id" class="form-control" style="padding: 10px 14px;">
                <option value="">-- Semua Pengguna --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ ucfirst($user->role) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="form-label font-bold text-gray-700 mb-2">Cari Aktivitas</label>
            <input type="text" name="action" class="form-control" placeholder="Contoh: create, delete, subject..." value="{{ request('action') }}" style="padding: 10px 14px;">
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px; height: 42px;">
                <i class="bi bi-search me-1"></i> Terapkan Filter
            </button>
            @if(request('user_id') || request('action'))
                <a href="{{ route('admin.logs') }}" class="btn btn-secondary ml-2" style="height: 42px;">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="timeline-wrapper">
    <div class="timeline-container">
        @forelse($logs as $log)
            @php
                $actionType = strtolower($log->action);
                
                // Menentukan icon dan style berdasarkan action string
                $iconClass = 'icon-default';
                $icon = 'bi bi-pin-angle-fill';
                
                if (str_contains($actionType, 'create') || str_contains($actionType, 'store') || str_contains($actionType, 'add')) {
                    $iconClass = 'icon-create';
                    $icon = 'bi bi-plus-circle-fill';
                } elseif (str_contains($actionType, 'update') || str_contains($actionType, 'edit') || str_contains($actionType, 'modify')) {
                    $iconClass = 'icon-update';
                    $icon = 'bi bi-pencil-fill';
                } elseif (str_contains($actionType, 'delete') || str_contains($actionType, 'remove') || str_contains($actionType, 'destroy')) {
                    $iconClass = 'icon-delete';
                    $icon = 'bi bi-trash-fill';
                } elseif (str_contains($actionType, 'login') || str_contains($actionType, 'auth')) {
                    $iconClass = 'icon-update';
                    $icon = 'bi bi-box-arrow-in-right';
                } elseif (str_contains($actionType, 'archive')) {
                    $iconClass = 'icon-update';
                    $icon = 'bi bi-archive-fill';
                }
            @endphp
            
            <div class="timeline-item">
                <div class="timeline-icon {{ $iconClass }}">
                    <i class="{{ $icon }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="log-header">
                        <div class="log-title">
                            {{ str_replace('_', ' ', $log->action) }}
                        </div>
                        <div class="log-meta">
                            <i class="bi bi-clock me-1"></i> {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }} 
                            <span class="text-xs">({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})</span>
                        </div>
                    </div>
                    
                    <div class="user-badge mb-2 mt-1">
                        <div style="width: 20px; height: 20px; border-radius: 50%; background: var(--gray-300); display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        {{ $log->user->name ?? 'Sistem' }}
                        <span class="badge badge-gray text-xs" style="padding: 2px 6px; margin-left: 4px;">{{ $log->user->role ?? 'Sistem' }}</span>
                    </div>

                    @if($log->description)
                        <div class="log-body">
                            {{ $log->description }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding: 60px 20px;">
                <div class="empty-icon" style="font-size: 4rem;">📭</div>
                <h3 style="font-size: 1.25rem;">Tidak Ada Aktivitas</h3>
                <p style="font-size: 1rem; color: var(--gray-500);">Belum ada rekam jejak aktivitas yang ditemukan.</p>
            </div>
        @endforelse
    </div>
    
    @if($logs->hasPages())
    <div class="flex justify-between items-center mt-8 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="text-sm text-muted font-medium">
            Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
        </div>
        <div>
            {{ $logs->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
