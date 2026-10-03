@extends('layouts.admin')

@section('title', 'Mata Pelajaran — EduExam')
@section('page_title', 'Manajemen Mata Pelajaran')

@section('admin-content')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Kolom Kiri: Form Tambah (4 cols) -->
    <div class="lg:col-span-4 sticky top-24">
        <div class="card overflow-hidden">
            <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">add_circle</span>
                <h3 class="font-headline font-bold text-slate-900 text-sm">Tambah Mapel Baru</h3>
            </div>
            <div class="card-body p-5 md:p-6">
                <form action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="code">
                            Kode Mapel <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" class="form-control font-mono uppercase text-sm" placeholder="MTK, BINA, BING" value="{{ old('code') }}" required maxlength="10">
                        <p class="text-[11px] text-slate-400 mt-1">Kode unik singkatan mapel (maks. 10 karakter).</p>
                        @error('code') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="name">
                            Nama Mata Pelajaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control text-sm" placeholder="Contoh: Matematika Wajib" value="{{ old('name') }}" required maxlength="100">
                        @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full text-xs font-bold py-3 flex items-center justify-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                        Simpan Mata Pelajaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel Data (8 cols) -->
    <div class="lg:col-span-8">
        <div class="card overflow-hidden">
            <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">menu_book</span>
                    <h3 class="font-headline font-bold text-slate-900 text-sm">Daftar Mata Pelajaran</h3>
                </div>
                <span class="badge badge-gray text-xs font-semibold">{{ count($subjects) }} Total Mapel</span>
            </div>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Kode</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center" style="width: 120px;">Bank Soal</th>
                            <th class="text-center" style="width: 100px;">Ujian</th>
                            <th style="text-align: right; width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td>
                                <span class="badge badge-gray font-mono font-bold text-xs uppercase">
                                    {{ strtoupper($subject->code) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-sm">{{ $subject->name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Ditambahkan: {{ $subject->created_at ? $subject->created_at->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">description</span>
                                    {{ $subject->questions_count ?? 0 }} Soal
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">assignment</span>
                                    {{ $subject->exams_count ?? 0 }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm text-xs font-medium px-2.5 py-1" title="Hapus Mata Pelajaran">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-12 text-sm">
                                <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">menu_book</span>
                                Belum ada mata pelajaran terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
