@extends('layouts.teacher')

@section('title', 'Detail Jawaban Siswa — ' . $student->name)
@section('page_title', 'Detail Jawaban Siswa')

@section('teacher-content')

<div class="mb-5 flex justify-between items-center">
    <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-secondary text-xs px-3.5 py-2 flex items-center gap-1.5">
        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
        Kembali ke Daftar Nilai
    </a>
</div>

<!-- Student Header Card -->
<div class="card p-6 mb-6 flex flex-col sm:flex-row items-center justify-between gap-5 bg-gradient-to-r from-white via-slate-50/50 to-white">
    <div class="flex items-center gap-4 text-center sm:text-left flex-col sm:flex-row">
        <img src="{{ $student->avatar_url }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-slate-200 shadow-sm">
        <div>
            <h2 class="font-headline font-bold text-slate-900 text-lg md:text-xl">{{ $student->name }}</h2>
            <div class="text-xs text-slate-500 font-mono mt-0.5">NIS: {{ $student->nis }} • {{ $exam->classroom->name }}</div>
            <div class="text-xs text-slate-400 mt-2 flex items-center gap-2 flex-wrap justify-center sm:justify-start">
                <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-slate-400" style="font-size: 14px;">timer</span>
                    Durasi: {{ $participant->time_spent_minutes }} Menit
                </span>
                <span>•</span>
                <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-slate-400" style="font-size: 14px;">check_circle</span>
                    Selesai: {{ $participant->submitted_at ? $participant->submitted_at->format('d M Y, H:i') : '-' }} WIB
                </span>
            </div>
        </div>
    </div>
    
    <div class="text-center sm:text-right p-4 px-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex-shrink-0">
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Skor Akhir</div>
        <div class="font-headline font-extrabold text-3xl md:text-4xl {{ $result->pass_status === 'pass' ? 'text-emerald-600' : 'text-rose-600' }}">
            {{ round($result->total_score) }}
        </div>
        <span class="badge {{ $result->pass_status === 'pass' ? 'badge-success' : 'badge-danger' }} text-[11px] font-semibold mt-1 inline-block">
            {{ $result->pass_label }}
        </span>
    </div>
</div>

<div class="mb-4 flex items-center justify-between">
    <h3 class="font-headline font-bold text-slate-900 text-base flex items-center gap-2">
        <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">fact_check</span>
        Rincian Lembar Jawaban
    </h3>
    <span class="text-xs text-slate-500 font-semibold">{{ count($answers) }} Butir Soal</span>
</div>

<!-- Questions Answers Detail -->
<div class="space-y-4">
@foreach($answers as $index => $answer)
    @php
        $q = $answer->examQuestion->question;
        $statusBadge = 'badge-gray';
        $statusText = 'KOSONG';
        if ($answer->selected_option_id) {
            $statusBadge = $answer->is_correct ? 'badge-success' : 'badge-danger';
            $statusText  = $answer->is_correct ? 'BENAR' : 'SALAH';
        }
    @endphp
    <div class="card overflow-hidden">
        <div class="p-4 px-6 bg-slate-50/80 border-b border-slate-100 flex justify-between items-center">
            <div class="font-headline font-bold text-slate-800 text-sm">
                Soal Nomor {{ $index + 1 }}
            </div>
            <span class="badge {{ $statusBadge }} text-xs font-semibold px-2.5 py-0.5">
                {{ $statusText }}
            </span>
        </div>
        
        <div class="p-6">
            <div class="text-sm text-slate-800 leading-relaxed mb-4">
                {!! nl2br(e($q->question_text)) !!}
            </div>
            
            @if($q->question_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $q->question_image) }}" alt="Gambar Soal" class="max-h-48 rounded-xl border border-slate-200 object-cover">
                </div>
            @endif
            
            @if(in_array($q->type, ['multiple_choice', 'true_false']))
                <div class="space-y-2">
                    @foreach($q->options as $opt)
                        @php
                            $isSelected = $answer->selected_option_id == $opt->id;
                            $isCorrectAns = $opt->is_correct;
                            
                            $rowBg = 'bg-white border-slate-200';
                            $charBg = 'bg-slate-100 text-slate-600';
                            
                            if ($isSelected && $isCorrectAns) {
                                $rowBg = 'bg-emerald-50 border-emerald-300';
                                $charBg = 'bg-emerald-600 text-white';
                            } elseif ($isSelected && !$isCorrectAns) {
                                $rowBg = 'bg-rose-50 border-rose-300';
                                $charBg = 'bg-rose-600 text-white';
                            } elseif ($isCorrectAns) {
                                $rowBg = 'bg-emerald-50/60 border-emerald-200';
                                $charBg = 'bg-emerald-600 text-white';
                            }
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-xl border {{ $rowBg }} transition">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5 {{ $charBg }}">
                                {{ $opt->label }}
                            </div>
                            <div class="flex-1 text-xs text-slate-800 pt-1">
                                <span>{{ $opt->option_text }}</span>
                                @if($isSelected)
                                    <span class="ml-2 font-bold text-[11px] {{ $answer->is_correct ? 'text-emerald-700' : 'text-rose-700' }}">
                                        (Jawaban Siswa)
                                    </span>
                                @endif
                                @if($isCorrectAns && !$isSelected)
                                    <span class="ml-2 font-bold text-[11px] text-emerald-700">
                                        (Kunci Jawaban)
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            @if($q->explanation)
                <div class="mt-4 p-3.5 bg-indigo-50/60 rounded-xl border border-indigo-100 text-xs text-slate-700">
                    <strong class="font-bold text-indigo-900 block mb-1">Pembahasan:</strong>
                    {{ $q->explanation }}
                </div>
            @endif
        </div>
    </div>
@endforeach
</div>

@endsection
