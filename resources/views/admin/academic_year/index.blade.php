@extends('layouts.admin')

@section('title', 'Tahun Ajaran — EduExam')
@section('page_title', 'Manajemen Tahun Ajaran')

@section('admin-content')

<div class="grid" style="grid-template-columns: 1fr 2.5fr; gap: 24px; align-items: start;">
    
    <!-- Kolom Kiri: Form Tambah -->
    <div class="card" style="position: sticky; top: 90px;">
        <div class="card-header" style="background: var(--primary-light);">
            <h3 class="card-title flex items-center gap-2" style="color: var(--primary-dark);">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tahun Ajaran
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.academic-years') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Nama Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: 2025/2026" value="{{ old('name') }}" required maxlength="20">
                    <div class="text-xs text-muted mt-1">Format penamaan bebas.</div>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
                    <div class="form-group mb-0">
                        <label class="form-label" for="start_year">Tahun Mulai <span class="text-danger">*</span></label>
                        <input type="number" name="start_year" id="start_year" class="form-control" placeholder="2025" value="{{ old('start_year', date('Y')) }}" min="2020" max="2050" required>
                        @error('start_year')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label" for="end_year">Tahun Selesai <span class="text-danger">*</span></label>
                        <input type="number" name="end_year" id="end_year" class="form-control" placeholder="2026" value="{{ old('end_year', date('Y') + 1) }}" min="2020" max="2050" required>
                        @error('end_year')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label" for="semester">Semester <span class="text-danger">*</span></label>
                    <select name="semester" id="semester" class="form-control" required>
                        <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ganjil (1)</option>
                        <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Genap (2)</option>
                    </select>
                    @error('semester')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-size: 1rem;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Data -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h3 class="card-title flex items-center gap-2">
                <i class="bi bi-calendar-week text-primary me-1"></i> Daftar Tahun Ajaran
            </h3>
            <span class="badge badge-gray font-bold">{{ count($years) }} Total</span>
        </div>
        <div class="table-wrap" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg); border: none;">
            <table>
                <thead>
                    <tr>
                        <th width="30%">Tahun Ajaran</th>
                        <th class="text-center" width="15%">Semester</th>
                        <th class="text-center" width="20%">Kelas</th>
                        <th class="text-center" width="20%">Ujian</th>
                        <th class="text-right" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($years as $year)
                    <tr>
                        <td>
                            <div class="font-bold text-gray-900" style="font-size: 1.05rem;">{{ $year->name }}</div>
                            <div class="text-xs text-muted mt-1">Periode: {{ $year->start_year }} - {{ $year->end_year }}</div>
                        </td>
                        <td class="text-center">
                            @if($year->semester == 1)
                                <span class="badge badge-success">Ganjil</span>
                            @else
                                <span class="badge badge-primary">Genap</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="badge badge-gray" style="font-size: 0.85rem;">
                                <i class="bi bi-building me-1"></i> {{ $year->classrooms_count ?? 0 }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="badge badge-warning" style="background: var(--warning-light); color: var(--warning); font-size: 0.85rem;">
                                <i class="bi bi-file-earmark-text me-1"></i> {{ $year->exams_count ?? 0 }}
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ url('admin/tahun-ajaran/' . $year->id) }}" method="POST" class="inline" onsubmit="return confirm('Menghapus tahun ajaran ini akan ikut menghapus semua kelas dan ujian yang berada di dalamnya!\n\nApakah Anda benar-benar yakin ingin melanjutkan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus Tahun Ajaran">
                                        <i class="bi bi-trash-fill me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon" style="font-size: 4rem;"><i class="bi bi-calendar-x text-muted"></i></div>
                                <h3 style="font-size: 1.25rem;">Belum Ada Data Tahun Ajaran</h3>
                                <p style="font-size: 1rem; color: var(--gray-500);">Silakan tambah data pertama Anda melalui form di sebelah kiri.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('admin-styles')
<style>
    @media (max-width: 1024px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
        .card[style*="position: sticky"] {
            position: relative !important;
            top: 0 !important;
        }
    }
    
    .btn-icon {
        padding: 6px 12px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@endsection
