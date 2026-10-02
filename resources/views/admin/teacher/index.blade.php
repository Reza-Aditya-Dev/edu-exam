@extends('layouts.admin')

@section('title', 'Manajemen Guru — EduExam')
@section('page_title', 'Manajemen Guru')

@section('admin-content')

<div class="card mb-6">
    <div class="card-body flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.teachers') }}" method="GET" class="flex gap-4 items-center">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, atau email..." value="{{ request('search') }}" style="width: 300px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
        
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary font-bold">+ Tambah Guru</a>
    </div>
</div>

<div class="card">
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
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $teacher->avatar_url }}" alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div class="font-bold">{{ $teacher->name }}</div>
                                <div class="text-xs text-muted">{{ $teacher->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="font-monospace text-muted">{{ $teacher->nip }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @forelse($teacher->subjects as $subject)
                                <span class="badge badge-gray">{{ $subject->name }}</span>
                            @empty
                                <span class="text-muted text-xs">Belum di set</span>
                            @endforelse
                        </div>
                    </td>
                    <td>{{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        @if($teacher->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('admin.teachers.toggle', $teacher) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $teacher->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                                    {{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Tidak ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($teachers->hasPages())
    <div class="card-body border-t border-gray-100">
        {{ $teachers->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
