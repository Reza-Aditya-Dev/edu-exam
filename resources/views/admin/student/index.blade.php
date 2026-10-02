@extends('layouts.admin')

@section('title', 'Manajemen Siswa — EduExam')
@section('page_title', 'Manajemen Siswa')

@section('admin-content')

<div class="card mb-6">
    <div class="card-body flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.students') }}" method="GET" class="flex gap-4 items-center">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIS, atau email..." value="{{ request('search') }}" style="width: 250px;">
            
            <select name="classroom_id" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->academicYear->name }})</option>
                @endforeach
            </select>
            
            <select name="status" class="form-control" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
        
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary font-bold">+ Tambah Siswa</a>
    </div>
</div>

<div class="card">
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
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->avatar_url }}" alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div class="font-bold">{{ $student->name }}</div>
                                <div class="text-xs text-muted">{{ $student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="font-monospace text-muted">{{ $student->nis }}</td>
                    <td>
                        @if($student->classrooms->isNotEmpty())
                            @foreach($student->classrooms as $c)
                                <span class="badge badge-primary">{{ $c->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted text-xs">Belum di set</span>
                        @endif
                    </td>
                    <td>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        @if($student->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('admin.students.toggle', $student) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $student->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                                    {{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($students->hasPages())
    <div class="card-body border-t border-gray-100">
        {{ $students->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
