@extends('layouts.admin')

@section('title', 'Semua Ujian — EduExam')
@section('page_title', 'Pantau Semua Ujian')

@section('admin-content')

<div class="card mb-6">
    <div class="card-header flex justify-between items-center flex-wrap gap-4">
        <h3 class="card-title flex items-center gap-2">
            <i class="bi bi-file-earmark-text text-primary me-2"></i> Daftar Semua Ujian
        </h3>
        <div class="flex items-center gap-3 flex-wrap">
            <form action="{{ route('admin.exams') }}" method="GET" class="flex gap-2">
                <select name="status" class="form-control" style="width: 200px; padding: 8px 12px; height: 38px;" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </form>
            <!-- Di halaman ini Admin tidak menambah ujian, ujian dibuat oleh Guru. Admin hanya memantau. -->
            <button type="button" class="btn btn-secondary" style="height: 38px;" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> Segarkan Data
            </button>
        </div>
    </div>
    
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="25%">Judul Ujian</th>
                    <th>Informasi Kelas & Mapel</th>
                    <th>Guru Pengampu</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                <tr>
                    <td>
                        <div class="font-bold text-gray-900" style="font-size: 1.05rem;">{{ $exam->title }}</div>
                        <div class="text-xs text-muted mt-1 flex items-center gap-1">
                            <i class="bi bi-clock me-1"></i> Dibuat: {{ $exam->created_at ? $exam->created_at->format('d M Y') : '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="badge badge-gray" style="font-size: 0.75rem;"><i class="bi bi-book me-1"></i> {{ $exam->subject->name ?? 'Mapel Terhapus' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="badge badge-gray" style="font-size: 0.75rem;"><i class="bi bi-building me-1"></i> {{ $exam->classroom->name ?? 'Kelas Terhapus' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-light flex items-center justify-center text-primary-dark font-bold text-xs" style="border-radius: 50%; width: 32px; height: 32px; background: var(--primary-light); color: var(--primary-dark);">
                                {{ substr($exam->teacher->name ?? '?', 0, 1) }}
                            </div>
                            <span class="font-medium">{{ $exam->teacher->name ?? 'Guru Terhapus' }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $exam->status_color ?? 'gray' }}" style="padding: 6px 12px; font-size: 0.8rem;">
                            {{ $exam->status_label ?? ucfirst($exam->status) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Tombol Arsip jika status belum diarsipkan -->
                            @if($exam->status != 'archived')
                            <form action="{{ url('admin/ujian/' . $exam->id . '/arsip') }}" method="POST" class="inline" onsubmit="return confirm('Arsipkan ujian ini? Ujian yang diarsipkan tidak akan muncul di halaman utama siswa.')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning" title="Arsipkan" style="background: var(--warning-light); color: var(--warning); border-color: var(--warning-border);">
                                    <i class="bi bi-archive-fill me-1"></i> Arsipkan
                                </button>
                            </form>
                            @endif
                            
                            <!-- Tombol Hapus (Admin bisa menghapus ujian kapan saja) -->
                            <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN! Apakah Anda yakin ingin menghapus ujian ini secara permanen? Data yang dihapus tidak bisa dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permanen">
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
                            <div class="empty-icon" style="font-size: 4rem;"><i class="bi bi-file-earmark-x text-muted"></i></div>
                            <h3 style="font-size: 1.25rem;">Belum Ada Data Ujian</h3>
                            <p style="font-size: 1rem; color: var(--gray-500);">Data ujian yang dibuat oleh guru akan muncul di sini.</p>
                            @if(request('status'))
                                <a href="{{ route('admin.exams') }}" class="btn btn-secondary mt-4">Tampilkan Semua Status</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($exams->hasPages())
<div class="flex justify-between items-center mt-4">
    <div class="text-sm text-muted font-medium">
        Menampilkan data {{ $exams->firstItem() }} hingga {{ $exams->lastItem() }} dari total {{ $exams->total() }} ujian
    </div>
    <div>
        {{ $exams->links() }}
    </div>
</div>
@endif

@endsection
