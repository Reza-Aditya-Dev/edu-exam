@extends('layouts.admin')

@section('title', 'Mata Pelajaran — EduExam')
@section('page_title', 'Manajemen Mata Pelajaran')

@section('admin-content')

<div class="grid" style="grid-template-columns: 1fr 2.5fr; gap: 24px; align-items: start;">
    
    <!-- Kolom Kiri: Form Tambah -->
    <div class="card" style="position: sticky; top: 90px;">
        <div class="card-header" style="background: var(--primary-light);">
            <h3 class="card-title flex items-center gap-2" style="color: var(--primary-dark);">
                <span>➕</span> Tambah Mapel Baru
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="code">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: MTK, IPA" value="{{ old('code') }}" required maxlength="10" style="text-transform: uppercase;">
                    <div class="text-xs text-muted mt-1">Kode harus unik. Maksimal 10 karakter.</div>
                    @error('code')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mt-4">
                    <label class="form-label" for="name">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Matematika Wajib" value="{{ old('name') }}" required maxlength="100">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-size: 1rem;">
                        <span>💾</span> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Data -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h3 class="card-title flex items-center gap-2">
                <span>📚</span> Daftar Mata Pelajaran
            </h3>
            <span class="badge badge-gray font-bold">{{ count($subjects) }} Total</span>
        </div>
        <div class="table-wrap" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg); border: none;">
            <table>
                <thead>
                    <tr>
                        <th width="15%">Kode</th>
                        <th>Mata Pelajaran</th>
                        <th class="text-center" width="15%">Bank Soal</th>
                        <th class="text-center" width="15%">Ujian</th>
                        <th class="text-right" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td>
                            <span class="badge badge-gray font-bold text-sm" style="letter-spacing: 0.5px; border: 1px solid var(--gray-300);">
                                {{ strtoupper($subject->code) }}
                            </span>
                        </td>
                        <td>
                            <div class="font-bold text-gray-900" style="font-size: 1.05rem;">{{ $subject->name }}</div>
                            <div class="text-xs text-muted mt-1 flex items-center gap-1">
                                <span>🕒</span> Ditambahkan {{ $subject->created_at ? $subject->created_at->diffForHumans() : '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="badge badge-primary" style="background: var(--primary-light); color: var(--primary-dark); font-size: 0.85rem;">
                                📝 {{ $subject->questions_count ?? 0 }} Soal
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="badge badge-warning" style="background: var(--warning-light); color: var(--warning); font-size: 0.85rem;">
                                📋 {{ $subject->exams_count ?? 0 }} Ujian
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?\n\nPERINGATAN: Semua bank soal dan data ujian yang menggunakan mata pelajaran ini juga akan ikut terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus Mata Pelajaran">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon" style="font-size: 4rem;">📚</div>
                                <h3 style="font-size: 1.25rem;">Belum Ada Mata Pelajaran</h3>
                                <p style="font-size: 1rem; color: var(--gray-500);">Silakan tambah mata pelajaran pertama Anda melalui form di sebelah kiri.</p>
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
