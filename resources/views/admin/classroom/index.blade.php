@extends('layouts.admin')

@section('title', 'Manajemen Kelas — EduExam')
@section('page_title', 'Data Kelas & Ruangan')

@section('admin-content')

<!-- Filter & Actions Bar -->
<div class="card mb-6">
    <div class="card-body p-4 flex justify-between items-center flex-wrap gap-4">
        <form action="{{ route('admin.classrooms') }}" method="GET" class="flex gap-3 items-center flex-wrap">
            <select name="academic_year_id" class="form-control text-sm" style="width: auto; min-width: 220px;" onchange="this.form.submit()">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} - Smt {{ $year->semester }}
                    </option>
                @endforeach
            </select>
            @if(request('academic_year_id'))
                <a href="{{ route('admin.classrooms') }}" class="btn text-slate-500 hover:text-slate-800 text-xs px-2 py-2">
                    Reset
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary text-xs font-bold px-4 py-2.5 flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
            Tambah Kelas
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card overflow-hidden">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 140px;">Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                <tr class="hover:bg-slate-50/70 transition">
                    <td>
                        <span class="badge badge-primary text-xs font-semibold px-2.5 py-1">
                            Kelas {{ $classroom->grade }}
                        </span>
                    </td>
                    <td>
                        <div class="font-bold text-slate-900 text-sm">{{ $classroom->name }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            {{ $classroom->academicYear->name ?? '' }} • Smt {{ $classroom->academicYear->semester ?? '' }}
                        </div>
                    </td>
                    <td class="text-xs text-slate-600 font-medium">
                        {{ $classroom->homeroomTeacher->name ?? '-' }}
                    </td>
                    <td>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-xs font-semibold text-slate-700">
                            <span class="material-symbols-outlined text-slate-500" style="font-size: 14px;">groups</span>
                            {{ $classroom->students_count ?? 0 }} Siswa
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn btn-secondary btn-sm text-xs font-medium px-3 py-1.5" title="Edit Kelas">
                                Edit
                            </a>
                            <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm text-xs font-medium px-3 py-1.5" title="Hapus Kelas">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-12 text-sm">
                        <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">meeting_room</span>
                        Belum ada data kelas yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($classrooms->hasPages())
    <div class="card-body p-4 border-t border-slate-100 flex justify-between items-center flex-wrap gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan data {{ $classrooms->firstItem() }} - {{ $classrooms->lastItem() }} dari total {{ $classrooms->total() }} kelas
        </div>
        <div>
            {{ $classrooms->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

@endsection
