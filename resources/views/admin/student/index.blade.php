@extends('layouts.admin')

@section('title', 'Manajemen Siswa — EduExam')
@section('page_title', 'Manajemen Siswa')

@section('admin-content')

<!-- Filter & Actions Bar -->
<div class="card mb-6">
    <div class="card-body p-4 flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.students') }}" method="GET" class="flex gap-3 items-center flex-wrap flex-1">
            <div class="relative" style="min-width: 260px;">
                <input type="text" name="search" class="form-control pl-9 text-sm" placeholder="Cari nama, NIS, atau email..." value="{{ request('search') }}">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size: 18px;">search</span>
            </div>
            
            <select name="classroom_id" class="form-control text-sm" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->academicYear->name }})</option>
                @endforeach
            </select>
            
            <select name="status" class="form-control text-sm" style="width: auto; min-width: 140px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            
            <button type="submit" class="btn btn-secondary text-xs font-semibold px-4 py-2.5">
                Filter
            </button>
            
            @if(request('search') || request('classroom_id') || request('status') !== null)
                <a href="{{ route('admin.students') }}" class="btn text-slate-500 hover:text-slate-800 text-xs px-2 py-2">
                    Reset
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary text-xs font-bold px-4 py-2.5 flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
            Tambah Siswa
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card overflow-hidden">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr class="hover:bg-slate-50/70 transition">
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $student->name }}</div>
                                <div class="text-xs text-slate-400">{{ $student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="font-mono text-xs text-slate-600 font-semibold">{{ $student->nis }}</td>
                    <td>
                        @if($student->classrooms->isNotEmpty())
                            @foreach($student->classrooms as $c)
                                <span class="badge badge-primary text-xs font-medium">{{ $c->name }}</span>
                            @endforeach
                        @else
                            <span class="text-slate-400 text-xs italic">Belum di-set</span>
                        @endif
                    </td>
                    <td class="text-xs text-slate-600 font-medium">
                        {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                    <td>
                        @if($student->is_active)
                            <span class="badge badge-success text-xs font-semibold">Aktif</span>
                        @else
                            <span class="badge badge-danger text-xs font-semibold">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end items-center gap-1.5">
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary btn-sm text-xs font-medium px-3 py-1.5" title="Edit Siswa">
                                Edit
                            </a>
                            <form action="{{ route('admin.students.toggle', $student) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn {{ $student->is_active ? 'btn-danger' : 'btn-success' }} btn-sm text-xs font-medium px-3 py-1.5">
                                    {{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-slate-400 py-12 text-sm">
                        <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">person_off</span>
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($students->hasPages())
    <div class="card-body p-4 border-t border-slate-100 flex justify-between items-center flex-wrap gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan data {{ $students->firstItem() }} - {{ $students->lastItem() }} dari total {{ $students->total() }} siswa
        </div>
        <div>
            {{ $students->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@endsection
