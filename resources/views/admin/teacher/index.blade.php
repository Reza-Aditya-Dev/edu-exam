@extends('layouts.admin')

@section('title', 'Manajemen Guru — EduExam')
@section('page_title', 'Manajemen Guru')

@section('admin-content')

<!-- Action & Search Bar -->
<div class="card mb-6">
    <div class="card-body p-4 flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.teachers') }}" method="GET" class="flex gap-3 items-center flex-wrap flex-1">
            <div class="relative" style="min-width: 280px;">
                <input type="text" name="search" class="form-control pl-9 text-sm" placeholder="Cari nama, NIP, atau email..." value="{{ request('search') }}">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size: 18px;">search</span>
            </div>
            <button type="submit" class="btn btn-secondary text-xs font-semibold px-4 py-2.5">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.teachers') }}" class="btn text-slate-500 hover:text-slate-800 text-xs px-2 py-2">
                    Reset
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary text-xs font-bold px-4 py-2.5 flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
            Tambah Guru
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card overflow-hidden">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Guru</th>
                    <th>NIP</th>
                    <th>Mata Pelajaran (Diampu)</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr class="hover:bg-slate-50/70 transition">
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $teacher->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $teacher->name }}</div>
                                <div class="text-xs text-slate-400">{{ $teacher->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="font-mono text-xs text-slate-600 font-semibold">{{ $teacher->nip }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @forelse($teacher->subjects as $subject)
                                <span class="badge badge-gray text-xs">{{ $subject->name }}</span>
                            @empty
                                <span class="text-slate-400 text-xs italic">Belum diatur</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="text-xs text-slate-600 font-medium">
                        {{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                    <td>
                        @if($teacher->is_active)
                            <span class="badge badge-success text-xs font-semibold">Aktif</span>
                        @else
                            <span class="badge badge-danger text-xs font-semibold">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end items-center gap-1.5">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-secondary btn-sm text-xs font-medium px-3 py-1.5" title="Edit Guru">
                                Edit
                            </a>
                            <form action="{{ route('admin.teachers.toggle', $teacher) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn {{ $teacher->is_active ? 'btn-danger' : 'btn-success' }} btn-sm text-xs font-medium px-3 py-1.5">
                                    {{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-slate-400 py-12 text-sm">
                        <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">person_off</span>
                        Tidak ada data guru ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($teachers->hasPages())
    <div class="card-body p-4 border-t border-slate-100 flex justify-between items-center flex-wrap gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan data {{ $teachers->firstItem() }} - {{ $teachers->lastItem() }} dari total {{ $teachers->total() }} guru
        </div>
        <div>
            {{ $teachers->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@endsection
