@extends('layouts.teacher')

@section('title', 'Detail Jawaban Siswa')
@section('page_title', 'Detail Jawaban Siswa')

@push('teacher-styles')
<style>
    .student-header { display: flex; align-items: center; gap: 16px; padding: 20px; background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); margin-bottom: 24px; }
    .student-avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; }
    .student-info { flex: 1; }
    .student-name { font-size: 1.125rem; font-weight: 800; color: var(--gray-900); }
    .student-nis { font-family: monospace; color: var(--gray-500); font-size: 0.875rem; background: var(--gray-100); padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; }
    .student-score { font-size: 2.5rem; font-weight: 800; line-height: 1; }
    .student-score.pass { color: var(--success); }
    .student-score.fail { color: var(--danger); }
    
    .q-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); margin-bottom: 16px; box-shadow: var(--shadow-sm); overflow: hidden; }
    .q-card-header { padding: 12px 16px; background: var(--gray-50); border-bottom: 1px solid var(--gray-100); display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 0.9375rem; }
    .q-card-body { padding: 16px; }
    
    .status-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-badge.correct { background: var(--success-light); color: var(--success); border: 1px solid var(--success-border); }
    .status-badge.wrong { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger-border); }
    .status-badge.empty { background: var(--gray-100); color: var(--gray-500); border: 1px solid var(--gray-300); }
    
    .opt-row { display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; border-radius: var(--radius-md); margin-bottom: 8px; border: 1px solid transparent; }
    .opt-char { width: 28px; height: 28px; border-radius: 50%; background: var(--gray-100); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; color: var(--gray-600); flex-shrink: 0; }
    .opt-text { flex: 1; font-size: 0.9375rem; padding-top: 4px; }
    
    /* Highlight for selected and correct */
    .opt-row.selected { background: var(--danger-light); border-color: var(--danger-border); }
    .opt-row.selected .opt-char { background: var(--danger); color: white; }
    
    .opt-row.correct-ans { background: var(--success-light); border-color: var(--success-border); }
    .opt-row.correct-ans .opt-char { background: var(--success); color: white; }
    
    /* If selected is also correct */
    .opt-row.selected.correct-ans { background: var(--success-light); border-color: var(--success-border); }
</style>
@endpush

@section('teacher-content')
<div class="mb-4">
    <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Nilai</a>
</div>

<div class="student-header">
    <img src="{{ $student->avatar_url }}" alt="Avatar" class="student-avatar">
    <div class="student-info">
        <div class="student-name">{{ $student->name }}</div>
        <div class="student-nis">{{ $student->nis }} • {{ $exam->classroom->name }}</div>
        <div class="text-sm text-muted mt-2">
            <i class="bi bi-clock me-1"></i> Waktu Pengerjaan: {{ $participant->time_spent_minutes }} Menit | 
            Dikumpulkan: {{ $participant->submitted_at->format('d M Y, H:i') }}
        </div>
    </div>
    <div class="student-score {{ $result->pass_status }}">
        {{ round($result->total_score) }}
    </div>
</div>

<div class="mb-4">
    <h3 class="font-bold text-lg text-gray-800">Rincian Jawaban</h3>
</div>

@foreach($answers as $index => $answer)
    @php
        $q = $answer->examQuestion->question;
        $statusClass = 'empty';
        $statusText = 'KOSONG';
        if ($answer->selected_option_id) {
            $statusClass = $answer->is_correct ? 'correct' : 'wrong';
            $statusText  = $answer->is_correct ? 'BENAR' : 'SALAH';
        }
    @endphp
    <div class="q-card">
        <div class="q-card-header">
            <div>Soal No. {{ $index + 1 }}</div>
            <div class="status-badge {{ $statusClass }}">{{ $statusText }}</div>
        </div>
        <div class="q-card-body">
            <div style="font-size: 1rem; line-height: 1.6; margin-bottom: 20px; color: var(--gray-800);">
                {!! nl2br(e($q->question_text)) !!}
            </div>
            
            @if(in_array($q->type, ['multiple_choice', 'true_false']))
                <div>
                    @foreach($q->options as $opt)
                        @php
                            $isSelected = $answer->selected_option_id == $opt->id;
                            $isCorrectAns = $opt->is_correct;
                            $rowClass = '';
                            if ($isSelected) $rowClass .= ' selected';
                            if ($isCorrectAns) $rowClass .= ' correct-ans';
                        @endphp
                        <div class="opt-row {{ $rowClass }}">
                            <div class="opt-char">{{ $opt->label }}</div>
                            <div class="opt-text">
                                {{ $opt->option_text }}
                                @if($isSelected)
                                    <span class="text-xs ml-2 font-bold {{ $answer->is_correct ? 'text-success' : 'text-danger' }}">(Jawaban Siswa)</span>
                                @endif
                                @if($isCorrectAns && !$isSelected)
                                    <span class="text-xs ml-2 font-bold text-success">(Kunci Jawaban)</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endforeach

@endsection
