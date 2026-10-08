@extends('layouts.teacher')

@section('title', 'Hasil Ujian: ' . $exam->title)
@section('page_title', 'Hasil Ujian')

@section('teacher-content')

<!-- Header Info & Actions -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary text-xs font-semibold px-2.5 py-0.5">{{ $exam->subject->name }}</span>
            <span class="badge badge-gray text-xs font-semibold px-2.5 py-0.5">{{ $exam->classroom->name }}</span>
        </div>
        <h2 class="font-headline font-bold text-slate-900 text-xl md:text-2xl">{{ $exam->title }}</h2>
        <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
            <span>Pelaksanaan: {{ $exam->exam_date->format('d M Y') }}</span>
            <span>•</span>
            <span>KKM: <strong class="text-slate-700">{{ $exam->passing_grade }}</strong></span>
        </div>
    </div>
    
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" onclick="window.print()" class="btn btn-secondary text-xs font-semibold px-3 py-2 flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-outlined" style="font-size: 16px;">print</span>
            Cetak Rekap
        </button>
        <a href="{{ route('teacher.exams.analytics', $exam) }}" class="btn btn-secondary text-xs font-semibold px-3.5 py-2 flex items-center gap-1.5">
            <span class="material-symbols-outlined" style="font-size: 16px;">analytics</span>
            Analisis Soal
        </a>
        @if(count($results) > 0)
        <form action="{{ route('teacher.exams.clear_results', $exam) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH hasil pengerjaan siswa pada ujian ini?\n\nSoal ujian tetap tersimpan, tetapi semua nilai dan jawaban siswa akan dihapus/direset.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-300 px-3 py-2 flex items-center gap-1.5" title="Hapus dan Reset Semua Nilai Siswa">
                <span class="material-symbols-outlined" style="font-size: 16px;">restart_alt</span>
                Reset Semua Hasil
            </button>
        </form>
        @endif
        <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN BESAR: Apakah Anda yakin ingin menghapus ujian \'{{ $exam->title }}\' ini secara permanen?\n\nSeluruh konfigurasi ujian, butir soal, dan riwayat nilai siswa akan dihapus bersih sehingga Anda bisa membuat ujian baru.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-2 flex items-center gap-1.5 shadow-sm" title="Hapus Ujian & Hasil Permanen">
                <span class="material-symbols-outlined" style="font-size: 16px;">delete_forever</span>
                Hapus Ujian Ini
            </button>
        </form>
        <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary text-xs px-3 py-2 flex items-center gap-1">
            <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
            Daftar Ujian
        </a>
    </div>
</div>

<!-- Stats Summary Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-5 text-center">
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Peserta</div>
        <div class="font-headline font-extrabold text-2xl md:text-3xl text-slate-900">{{ $stats['total'] }}</div>
        <div class="text-[11px] text-slate-400 mt-0.5">Siswa Mengumpulkan</div>
    </div>
    
    <div class="card p-5 text-center">
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rata-rata Nilai</div>
        <div class="font-headline font-extrabold text-2xl md:text-3xl text-indigo-600">{{ $stats['avg'] }}</div>
        <div class="text-[11px] text-slate-400 mt-0.5">Skor Rerata Kelas</div>
    </div>
    
    <div class="card p-5 text-center bg-emerald-50/50 border-emerald-200">
        <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider mb-1">Lulus (>= {{ $exam->passing_grade }})</div>
        <div class="font-headline font-extrabold text-2xl md:text-3xl text-emerald-600">{{ $stats['pass'] }}</div>
        <div class="text-[11px] text-emerald-700 mt-0.5 font-medium">Siswa Tuntas</div>
    </div>
    
    <div class="card p-5 text-center bg-rose-50/50 border-rose-200">
        <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider mb-1">Remedial (< {{ $exam->passing_grade }})</div>
        <div class="font-headline font-extrabold text-2xl md:text-3xl text-rose-600">{{ $stats['fail'] }}</div>
        <div class="text-[11px] text-rose-700 mt-0.5 font-medium">Belum Tuntas</div>
    </div>
</div>

<!-- Results Table Card -->
<div class="card overflow-hidden">
    <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">assignment_ind</span>
            <h3 class="font-headline font-bold text-slate-900 text-sm">Daftar Rekap Nilai Siswa</h3>
        </div>
        <span class="text-xs text-slate-500 font-semibold">{{ count($results) }} Peserta Ujian</span>
    </div>
    
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Waktu Pengerjaan</th>
                    <th>Benar / Salah / Kosong</th>
                    <th>Nilai Akhir</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $result)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="text-slate-400 font-mono text-xs">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold text-slate-900 text-sm">{{ $result->student->name }}</div>
                    </td>
                    <td class="font-mono text-xs text-slate-600 font-medium">{{ $result->student->nis }}</td>
                    <td>
                        <div class="text-xs text-slate-800 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-slate-400" style="font-size: 14px;">timer</span>
                            {{ $result->time_spent_minutes }} Menit
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            Dikumpulkan: {{ $result->created_at->format('H:i') }} WIB
                        </div>
                    </td>
                    <td>
                        <div class="inline-flex items-center gap-1 font-mono text-xs">
                            <span class="text-emerald-600 font-bold" title="Benar">{{ $result->correct_answers }} B</span>
                            <span class="text-slate-300">/</span>
                            <span class="text-rose-600 font-bold" title="Salah">{{ $result->wrong_answers }} S</span>
                            <span class="text-slate-300">/</span>
                            <span class="text-slate-400 font-medium" title="Kosong">{{ $result->unanswered }} K</span>
                        </div>
                    </td>
                    <td>
                        <div class="font-headline font-extrabold text-base {{ $result->pass_status === 'pass' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ round($result->total_score) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $result->pass_status === 'pass' ? 'badge-success' : 'badge-danger' }} text-xs font-semibold">
                            {{ $result->pass_label }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('teacher.exams.student_detail', ['exam' => $exam->id, 'studentId' => $result->student_id]) }}" class="btn btn-secondary btn-sm text-xs font-medium px-2.5 py-1.5 flex items-center gap-1" title="Periksa Lembar Jawaban">
                                <span>Jawaban</span>
                                <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span>
                            </a>
                            <form action="{{ route('teacher.exams.destroy_result', ['exam' => $exam->id, 'studentId' => $result->student_id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus hasil pengerjaan siswa {{ $result->student->name }}?\n\nNilai dan jawaban siswa ini akan dihapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors" title="Hapus Hasil Siswa Ini">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-slate-400 py-12 text-sm">
                        <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">hourglass_disabled</span>
                        Belum ada siswa yang mengumpulkan ujian ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
