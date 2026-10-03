@extends('layouts.admin')

@section('title', 'Semua Ujian — EduExam')
@section('page_title', 'Pantau Semua Ujian')

@section('admin-content')

<!-- Filter & Actions Bar -->
<div class="card mb-6">
    <div class="card-body p-4 flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.exams') }}" method="GET" class="flex gap-3 items-center flex-wrap">
            <select name="status" class="form-control text-sm" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Status Ujian</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Berlangsung (Live)</option>
                <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
            </select>
            @if(request('status'))
                <a href="{{ route('admin.exams') }}" class="btn text-slate-500 hover:text-slate-800 text-xs px-2 py-2">
                    Reset
                </a>
            @endif
        </form>
        
        <button type="button" class="btn btn-secondary text-xs font-semibold px-4 py-2.5 flex items-center gap-1.5" onclick="window.location.reload()">
            <span class="material-symbols-outlined" style="font-size: 16px;">refresh</span>
            Segarkan Data
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="card overflow-hidden">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Judul Ujian</th>
                    <th>Kelas & Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th class="text-center">Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                <tr class="hover:bg-slate-50/70 transition">
                    <td>
                        <div class="font-bold text-slate-900 text-sm">{{ $exam->title }}</div>
                        <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size: 12px;">calendar_today</span>
                            {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1.5 text-xs text-slate-700 font-medium">
                                <span class="material-symbols-outlined text-slate-400" style="font-size: 14px;">menu_book</span>
                                {{ $exam->subject->name ?? 'Mapel Terhapus' }}
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                                <span class="material-symbols-outlined text-slate-400" style="font-size: 14px;">meeting_room</span>
                                {{ $exam->classroom->name ?? 'Kelas Terhapus' }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                {{ substr($exam->teacher->name ?? '?', 0, 1) }}
                            </div>
                            <span class="text-xs font-semibold text-slate-800">{{ $exam->teacher->name ?? 'Guru Terhapus' }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $exam->status_color ?? 'gray' }} text-xs font-semibold">
                            {{ $exam->status_label ?? ucfirst($exam->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-1.5">
                            @if($exam->status != 'archived')
                            <form action="{{ url('admin/ujian/' . $exam->id . '/arsip') }}" method="POST" class="inline" onsubmit="return confirm('Arsipkan ujian ini?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm text-xs font-medium px-2.5 py-1" title="Arsipkan Ujian">
                                    Arsipkan
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN! Hapus permanen ujian ini? Seluruh hasil dan rekaman siswa akan hilang.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm text-xs font-medium px-2.5 py-1" title="Hapus Permanen">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-12 text-sm">
                        <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">assignment_late</span>
                        Belum ada jadwal ujian yang tersimpan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($exams->hasPages())
    <div class="card-body p-4 border-t border-slate-100 flex justify-between items-center flex-wrap gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan data {{ $exams->firstItem() }} - {{ $exams->lastItem() }} dari total {{ $exams->total() }} ujian
        </div>
        <div>
            {{ $exams->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@endsection
