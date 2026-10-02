@extends('layouts.admin')

@section('title', 'Manajemen Kelas — EduExam')
@section('page_title', 'Data Kelas & Ruangan')

@section('admin-content')

<div class="card mb-6">
    <div class="card-header flex justify-between items-center flex-wrap gap-4">
        <h3 class="card-title">Daftar Kelas</h3>
        <div class="flex items-center gap-3 flex-wrap">
            <form action="{{ route('admin.classrooms') }}" method="GET" class="flex gap-2">
                <select name="academic_year_id" class="form-control" style="width: 220px; padding: 8px 12px; height: 38px;" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} - Smt {{ $year->semester }}
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary" style="height: 38px;">
                <span>➕</span> Tambah Kelas
            </a>
        </div>
    </div>
    
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="12%">Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th width="15%" class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                <tr>
                    <td>
                        <span class="badge badge-primary" style="font-size: 0.85rem; padding: 6px 12px;">Kelas {{ $classroom->grade }}</span>
                    </td>
                    <td>
                        <div class="font-bold text-primary" style="font-size: 1.05rem;">{{ $classroom->name }}</div>
                        <div class="text-xs text-muted mt-1">ID Kelas: {{ $classroom->id }}</div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div style="background: var(--gray-100); padding: 8px; border-radius: 8px;">
                                👥
                            </div>
                            <span class="font-medium" style="font-size: 0.95rem;">
                                {{ $classroom->students_count ?? 0 }} Siswa
                            </span>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn btn-sm btn-secondary" title="Edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini? Data yang terkait mungkin akan terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">🏫</div>
                            <h3>Belum Ada Data Kelas</h3>
                            <p>Silakan tambah kelas baru untuk tahun ajaran yang dipilih.</p>
                            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary mt-4">Tambah Kelas Pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($classrooms->hasPages())
<div class="flex justify-between items-center mt-4">
    <div class="text-sm text-muted font-medium">
        Menampilkan data {{ $classrooms->firstItem() }} hingga {{ $classrooms->lastItem() }} dari total {{ $classrooms->total() }} kelas
    </div>
    <div>
        {{ $classrooms->links() }}
    </div>
</div>
@endif

@endsection
