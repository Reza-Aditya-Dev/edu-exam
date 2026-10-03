@extends('layouts.admin')

@section('title', 'Tahun Ajaran — EduExam')
@section('page_title', 'Manajemen Tahun Ajaran')

@section('admin-content')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Kolom Kiri: Form Tambah (4 cols) -->
    <div class="lg:col-span-4 sticky top-24">
        <div class="card overflow-hidden">
            <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">calendar_add_on</span>
                <h3 class="font-headline font-bold text-slate-900 text-sm">Tambah Tahun Ajaran</h3>
            </div>
            <div class="card-body p-5 md:p-6">
                <form action="{{ route('admin.academic-years.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="name">
                            Nama Periode <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control text-sm" placeholder="Contoh: 2025/2026" value="{{ old('name') }}" required maxlength="20">
                        @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="start_year">
                                Mulai <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="start_year" id="start_year" class="form-control text-sm" value="{{ old('start_year', date('Y')) }}" min="2020" max="2050" required>
                            @error('start_year') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="end_year">
                                Selesai <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="end_year" id="end_year" class="form-control text-sm" value="{{ old('end_year', date('Y') + 1) }}" min="2020" max="2050" required>
                            @error('end_year') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="semester">
                            Semester <span class="text-rose-500">*</span>
                        </label>
                        <select name="semester" id="semester" class="form-control text-sm" required>
                            <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ganjil (Semester 1)</option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Genap (Semester 2)</option>
                        </select>
                        @error('semester') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full text-xs font-bold py-3 flex items-center justify-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                        Simpan Tahun Ajaran
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
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">calendar_month</span>
                    <h3 class="font-headline font-bold text-slate-900 text-sm">Daftar Tahun Ajaran</h3>
                </div>
                <span class="badge badge-gray text-xs font-semibold">{{ count($years) }} Periode</span>
            </div>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($years as $year)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td>
                                <div class="font-bold text-slate-900 text-sm">{{ $year->name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Periode: {{ $year->start_year }} - {{ $year->end_year }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($year->semester == 1)
                                    <span class="badge badge-success text-xs font-semibold">Ganjil (1)</span>
                                @else
                                    <span class="badge badge-primary text-xs font-semibold">Genap (2)</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-slate-500" style="font-size: 14px;">meeting_room</span>
                                    {{ $year->classrooms_count ?? 0 }} Kelas
                                </span>
                            </td>
                            <td class="text-center">
                                @if($year->is_active)
                                    <span class="badge badge-success text-xs font-semibold flex items-center justify-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <form action="{{ route('admin.academic-years.activate', $year) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm text-xs font-semibold px-2.5 py-1" title="Jadikan Tahun Ajaran Aktif">
                                            Aktifkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="flex items-center justify-end gap-1.5">
                                    <span class="text-xs text-slate-400">ID: {{ $year->id }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-12 text-sm">
                                <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">calendar_today</span>
                                Belum ada data tahun ajaran.
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
