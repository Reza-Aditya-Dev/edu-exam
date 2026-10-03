@extends('layouts.admin')

@section('title', 'Admin Dashboard — EduExam')
@section('page_title', 'Dashboard Administrator')

@push('admin-styles')
<style>
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 20px; 
        margin-bottom: 28px; 
    }
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
    .stat-card-modern {
        background: #ffffff;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    .stat-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .content-grid-admin {
        display: grid;
        grid-template-columns: 2fr 1.1fr;
        gap: 24px;
    }
    @media (max-width: 1024px) {
        .content-grid-admin { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('admin-content')

<!-- Welcome Banner -->
<div class="mb-6 rounded-2xl p-6 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.3);">
    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm text-xs font-semibold text-indigo-200 mb-3 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem CBT EduExam Online
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold font-headline tracking-tight text-white">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-indigo-200 text-sm mt-1 max-w-xl">Kelola data sekolah, pantau aktivitas ujian real-time, dan monitor performa akademik siswa secara terpadu.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.students.create') }}" class="btn bg-white text-indigo-900 font-semibold hover:bg-slate-100 text-xs px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
                + Siswa Baru
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="btn bg-white/15 text-white hover:bg-white/20 text-xs px-4 py-2.5 rounded-xl transition border border-white/20 flex items-center gap-1.5">
                <span class="material-symbols-outlined" style="font-size: 18px;">badge</span>
                + Guru Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #eef2ff; color: #4f46e5;">
            <span class="material-symbols-outlined" style="font-size: 28px;">school</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['total_students'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Total Siswa</div>
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #059669;">
            <span class="material-symbols-outlined" style="font-size: 28px;">person_apron</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['total_teachers'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Total Guru</div>
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
            <span class="material-symbols-outlined" style="font-size: 28px;">meeting_room</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['total_classrooms'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Total Kelas</div>
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #f5f3ff; color: #7c3aed;">
            <span class="material-symbols-outlined" style="font-size: 28px;">menu_book</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['total_subjects'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Mata Pelajaran</div>
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626;">
            <span class="material-symbols-outlined" style="font-size: 28px;">pending_actions</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['active_exams'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Ujian Berlangsung</div>
        </div>
    </div>
    
    <div class="stat-card-modern">
        <div class="stat-icon-wrapper" style="background: #f1f5f9; color: #475569;">
            <span class="material-symbols-outlined" style="font-size: 28px;">assignment</span>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $stats['total_exams'] }}</div>
            <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Total Ujian</div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="content-grid-admin">
    <!-- Left Column: Recent Exams -->
    <div>
        <div class="card overflow-hidden">
            <div class="card-header flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">assignment_turned_in</span>
                    <h3 class="font-headline font-bold text-slate-900 text-base">Ujian Terbaru (Semua Guru)</h3>
                </div>
                <a href="{{ route('admin.exams') }}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua
                    <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span>
                </a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Ujian & Mapel</th>
                            <th>Guru Pengampu</th>
                            <th class="text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExams as $exam)
                        <tr>
                            <td>
                                <div class="font-bold text-slate-800 text-sm">{{ $exam->title }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                    <span>{{ $exam->subject->name }}</span>
                                    <span>•</span>
                                    <span>{{ $exam->classroom->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-600">
                                        {{ substr($exam->teacher->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-medium text-slate-700">{{ $exam->teacher->name }}</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-{{ $exam->status_color }} text-xs font-semibold">
                                    {{ $exam->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-slate-400 py-10 text-sm">
                                <span class="material-symbols-outlined text-slate-300 block text-3xl mb-1">quiz</span>
                                Belum ada ujian yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Right Column: System Logs -->
    <div>
        <div class="card overflow-hidden">
            <div class="card-header flex justify-between items-center py-4 px-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">history</span>
                    <h3 class="font-headline font-bold text-slate-900 text-base">Aktivitas Sistem</h3>
                </div>
                <a href="{{ route('admin.logs') }}" class="text-xs text-primary font-semibold hover:underline">Semua Log</a>
            </div>
            <div class="divide-y divide-slate-100 max-h-[460px] overflow-y-auto">
                @forelse($recentLogs as $log)
                <div class="p-4 hover:bg-slate-50/60 transition flex items-start gap-3">
                    <div class="mt-0.5">
                        @if(str_contains($log->action, 'create') || str_contains($log->action, 'add'))
                            <span class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center material-symbols-outlined" style="font-size: 16px;">add_circle</span>
                        @elseif(str_contains($log->action, 'update') || str_contains($log->action, 'edit'))
                            <span class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center material-symbols-outlined" style="font-size: 16px;">edit</span>
                        @elseif(str_contains($log->action, 'delete') || str_contains($log->action, 'destroy'))
                            <span class="w-7 h-7 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center material-symbols-outlined" style="font-size: 16px;">delete</span>
                        @else
                            <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center material-symbols-outlined" style="font-size: 16px;">info</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs text-slate-800 font-medium leading-relaxed">
                            {{ $log->description }}
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1.5">
                            <span class="font-semibold text-slate-600">{{ $log->user->name ?? 'Sistem' }}</span>
                            <span>•</span>
                            <span>{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-sm">
                    <span class="material-symbols-outlined text-slate-300 block text-3xl mb-1">browse_activity</span>
                    Belum ada log aktivitas tercatat.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
