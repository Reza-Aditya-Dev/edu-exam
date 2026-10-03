@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem — EduExam')
@section('page_title', 'Audit & Log Aktivitas')

@section('admin-content')

<!-- Filter Bar -->
<div class="card mb-6">
    <div class="card-body p-5">
        <form action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-4">
                <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">Filter Pengguna</label>
                <select name="user_id" class="form-control text-sm">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-5">
                <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">Cari Aksi / Kata Kunci</label>
                <div class="relative">
                    <input type="text" name="action" class="form-control pl-9 text-sm" placeholder="Contoh: create, delete, exam..." value="{{ request('action') }}">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size: 18px;">search</span>
                </div>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="btn btn-primary text-xs font-semibold px-4 py-2.5 flex-1 flex items-center justify-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined" style="font-size: 16px;">filter_alt</span>
                    Terapkan
                </button>
                @if(request('user_id') || request('action'))
                    <a href="{{ route('admin.logs') }}" class="btn btn-secondary text-xs px-3 py-2.5">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Timeline List -->
<div class="max-w-4xl mx-auto">
    <div class="space-y-4">
        @forelse($logs as $log)
            @php
                $actionType = strtolower($log->action);
                $badgeBg = 'bg-slate-100 text-slate-700';
                $iconName = 'info';
                
                if (str_contains($actionType, 'create') || str_contains($actionType, 'store') || str_contains($actionType, 'add')) {
                    $badgeBg = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $iconName = 'add_circle';
                } elseif (str_contains($actionType, 'update') || str_contains($actionType, 'edit')) {
                    $badgeBg = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                    $iconName = 'edit';
                } elseif (str_contains($actionType, 'delete') || str_contains($actionType, 'destroy')) {
                    $badgeBg = 'bg-rose-50 text-rose-700 border-rose-200';
                    $iconName = 'delete';
                } elseif (str_contains($actionType, 'archive')) {
                    $badgeBg = 'bg-amber-50 text-amber-700 border-amber-200';
                    $iconName = 'archive';
                }
            @endphp
            
            <div class="card p-4 md:p-5 hover:border-indigo-200 transition">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $badgeBg }} border flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined" style="font-size: 20px;">{{ $iconName }}</span>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1.5">
                            <span class="font-headline font-bold text-slate-900 text-sm capitalize">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }} ({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})
                            </span>
                        </div>
                        
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold mb-2">
                            <span class="material-symbols-outlined text-slate-500" style="font-size: 14px;">person</span>
                            {{ $log->user->name ?? 'Sistem' }}
                            <span class="text-[10px] text-slate-400 font-normal">({{ ucfirst($log->user->role ?? 'Sistem') }})</span>
                        </div>
                        
                        @if($log->description)
                            <div class="text-xs text-slate-700 bg-slate-50 border border-slate-200/80 rounded-xl p-3 font-mono leading-relaxed">
                                {{ $log->description }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center text-slate-400 text-sm">
                <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">history</span>
                Belum ada rekam jejak aktivitas yang tercatat.
            </div>
        @endforelse
    </div>
    
    @if($logs->hasPages())
    <div class="mt-6 flex justify-between items-center p-4 bg-white rounded-2xl border border-slate-200">
        <div class="text-xs text-slate-500">
            Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
        </div>
        <div>
            {{ $logs->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
