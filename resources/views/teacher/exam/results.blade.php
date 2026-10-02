@extends('layouts.teacher')

@section('title', 'Hasil Ujian: ' . $exam->title)
@section('page_title', 'Hasil Ujian')

@section('teacher-content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-bold text-xl">{{ $exam->title }}</h2>
        <div class="text-sm text-muted mt-1">{{ $exam->subject->name }} • {{ $exam->classroom->name }}</div>
    </div>
    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<!-- STATS SUMMARY -->
<div class="grid" style="grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 0.875rem; color: var(--gray-500); font-weight: 600; margin-bottom: 8px;">TOTAL PESERTA</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--gray-900); line-height: 1;">{{ $stats['total'] }}</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center;">
        <div style="font-size: 0.875rem; color: var(--gray-500); font-weight: 600; margin-bottom: 8px;">RATA-RATA NILAI</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--primary); line-height: 1;">{{ $stats['avg'] }}</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center; background: var(--success-light); border-color: var(--success-border);">
        <div style="font-size: 0.875rem; color: var(--success); font-weight: 600; margin-bottom: 8px;">LULUS (>= {{ $exam->passing_grade }})</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--success); line-height: 1;">{{ $stats['pass'] }}</div>
    </div>
    <div class="card" style="padding: 20px; text-align: center; background: var(--danger-light); border-color: var(--danger-border);">
        <div style="font-size: 0.875rem; color: var(--danger); font-weight: 600; margin-bottom: 8px;">TIDAK LULUS</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--danger); line-height: 1;">{{ $stats['fail'] }}</div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header flex justify-between items-center">
        <h3 class="card-title">Daftar Nilai Siswa</h3>
        <div class="flex gap-2">
            <a href="{{ route('teacher.exams.analytics', $exam) }}" class="btn btn-primary btn-sm">Lihat Analitik Lengkap</a>
        </div>
    </div>
    
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Waktu Pengerjaan</th>
                    <th>Benar / Salah</th>
                    <th>Nilai Akhir</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $result)
                <tr>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $result->student->name }}</div>
                    </td>
                    <td class="font-monospace text-sm text-muted">{{ $result->student->nis }}</td>
                    <td>
                        <div class="text-sm">
                            ⏱ {{ $result->time_spent_minutes }} Menit
                        </div>
                        <div class="text-xs text-muted">
                            Dikumpulkan: {{ $result->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        <span class="text-success font-bold">{{ $result->correct_answers }}</span> /
                        <span class="text-danger font-bold">{{ $result->wrong_answers }}</span> /
                        <span class="text-muted">{{ $result->unanswered }}</span>
                    </td>
                    <td>
                        <div style="font-size: 1.125rem; font-weight: 800; color: {{ $result->pass_status === 'pass' ? 'var(--success)' : 'var(--danger)' }}">
                            {{ round($result->total_score) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $result->pass_status === 'pass' ? 'success' : 'danger' }}">
                            {{ $result->pass_label }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('teacher.exams.student_detail', ['exam' => $exam->id, 'studentId' => $result->student_id]) }}" class="btn btn-secondary btn-sm">Lihat Jawaban</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                        Belum ada siswa yang mengumpulkan ujian ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
