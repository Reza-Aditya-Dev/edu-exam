@extends('layouts.teacher')

@section('title', 'Data Ujian — EduExam')
@section('page_title', 'Manajemen Ujian')

@section('teacher-content')

<div class="flex justify-between items-center mb-6">
    <div class="flex gap-2">
        <a href="{{ route('teacher.exams.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}">Semua</a>
        <a href="{{ route('teacher.exams.index', ['status' => 'draft']) }}" class="btn {{ request('status') == 'draft' ? 'btn-primary' : 'btn-secondary' }}">Draft</a>
        <a href="{{ route('teacher.exams.index', ['status' => 'scheduled']) }}" class="btn {{ request('status') == 'scheduled' ? 'btn-primary' : 'btn-secondary' }}">Terjadwal</a>
        <a href="{{ route('teacher.exams.index', ['status' => 'active']) }}" class="btn {{ request('status') == 'active' ? 'btn-primary' : 'btn-secondary' }}">Berlangsung</a>
        <a href="{{ route('teacher.exams.index', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-secondary' }}">Selesai</a>
    </div>
    
    <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary btn-lg font-bold"><i class="bi bi-plus-lg me-1"></i> Buat Ujian Baru</a>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    @forelse($exams as $exam)
    <div class="card" style="display: flex; flex-direction: column; overflow: hidden; transition: transform .2s, box-shadow .2s; cursor: default;">
        <!-- Card Header with status color top border -->
        <div style="height: 6px; background: var(--{{ $exam->status_color }});"></div>
        <div class="card-body" style="flex: 1; padding: 20px;">
            <div class="flex justify-between items-start mb-2">
                <span class="badge badge-{{ $exam->status_color }}">{{ $exam->status_label }}</span>
                <div style="font-size: 0.8125rem; font-weight: 600; color: var(--gray-500); background: var(--gray-100); padding: 2px 8px; border-radius: 4px;">
                    {{ $exam->exam_type }}
                </div>
            </div>
            
            <h3 style="font-size: 1.125rem; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; line-height: 1.3;">
                {{ $exam->title }}
            </h3>
            
            <div style="font-size: 0.875rem; color: var(--primary); font-weight: 600; margin-bottom: 16px;">
                {{ $exam->subject->name }} • {{ $exam->classroom->name }}
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div style="font-size: 0.8125rem;">
                    <div class="text-muted mb-1"><i class="bi bi-calendar3 me-1"></i> Jadwal</div>
                    <div class="font-semibold">{{ $exam->exam_date->format('d/m/Y') }}</div>
                </div>
                <div style="font-size: 0.8125rem;">
                    <div class="text-muted mb-1"><i class="bi bi-clock me-1"></i> Waktu</div>
                    <div class="font-semibold">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</div>
                </div>
                <div style="font-size: 0.8125rem;">
                    <div class="text-muted mb-1"><i class="bi bi-file-earmark-text me-1"></i> Soal</div>
                    <div class="font-semibold">{{ $exam->total_questions }} Butir</div>
                </div>
                <div style="font-size: 0.8125rem;">
                    <div class="text-muted mb-1"><i class="bi bi-people me-1"></i> Peserta</div>
                    <div class="font-semibold">{{ $exam->participant_count }} Orang</div>
                </div>
            </div>
        </div>
        
        <div style="padding: 16px 20px; background: var(--gray-50); border-top: 1px solid var(--gray-200); display: flex; gap: 8px;">
            
            @if($exam->status === 'draft')
                <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-secondary flex-1"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                <form action="{{ route('teacher.exams.publish', $exam) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">Terbitkan</button>
                </form>
            @elseif($exam->status === 'scheduled')
                <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-secondary flex-1"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                <form action="{{ route('teacher.exams.activate', $exam) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Mulai ujian ini sekarang?')">Mulai Ujian</button>
                </form>
            @elseif($exam->status === 'active')
                <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-secondary flex-1"><i class="bi bi-activity me-1"></i> Monitor</a>
                <form action="{{ route('teacher.exams.complete', $exam) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Tutup ujian secara paksa sekarang?')">Tutup Ujian</button>
                </form>
            @elseif($exam->status === 'completed')
                <a href="{{ route('teacher.exams.analytics', $exam) }}" class="btn btn-secondary flex-1"><i class="bi bi-bar-chart me-1"></i> Analitik</a>
                <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-primary flex-1"><i class="bi bi-award me-1"></i> Hasil Ujian</a>
            @endif
            
        </div>
    </div>
    @empty
    <div class="card" style="grid-column: 1 / -1;">
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-x" style="font-size: 3rem;"></i></div>
            <h3>Belum Ada Ujian</h3>
            <p>Anda belum membuat jadwal ujian apapun.</p>
            <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary mt-4"><i class="bi bi-plus-lg me-1"></i> Buat Ujian Sekarang</a>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $exams->withQueryString()->links('pagination::bootstrap-4') }}
</div>

@endsection
