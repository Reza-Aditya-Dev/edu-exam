@extends('layouts.teacher')

@section('title', 'Bank Soal — EduExam')
@section('page_title', 'Bank Soal')

@section('teacher-content')

<div class="card mb-6">
    <div class="card-body">
        <form action="{{ route('teacher.questions.index') }}" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-4 flex-1">
                <input type="text" name="search" class="form-control" placeholder="Cari soal..." value="{{ request('search') }}" style="max-width: 300px;">
                
                <select name="subject_id" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
                
                <select name="type" class="form-control" style="max-width: 180px;" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                    <option value="short_answer" {{ request('type') == 'short_answer' ? 'selected' : '' }}>Isian Singkat</option>
                    <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Esai</option>
                </select>
            </div>
            
            <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Soal</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Pertanyaan</th>
                    <th>Mata Pelajaran</th>
                    <th>Tipe / Tingkat</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                <tr>
                    <td>
                        <div class="font-medium" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ strip_tags($question->question_text) }}
                        </div>
                        @if($question->question_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $question->question_image) }}" alt="Gambar Soal" style="max-height: 60px; border-radius: 6px; border: 1px solid var(--gray-300);">
                            </div>
                        @endif
                    </td>
                    <td>{{ $question->subject->name }}</td>
                    <td>
                        <div>{{ $question->type_label }}</div>
                        <span class="text-xs text-muted">{{ $question->difficulty_label }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex gap-2" style="justify-content: flex-end;">
                            <a href="{{ route('teacher.questions.edit', $question) }}" class="btn btn-secondary btn-sm" title="Edit"><i class="bi bi-pencil-square me-1"></i> Edit</a>
                            
                            <form action="{{ route('teacher.questions.duplicate', $question) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" title="Duplikat"><i class="bi bi-copy"></i></button>
                            </form>
                            
                            <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <div class="empty-icon"><i class="bi bi-journal-album" style="font-size: 3rem;"></i></div>
                        <h3>Bank Soal Kosong</h3>
                        <p>Anda belum membuat soal apapun.</p>
                        <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary mt-4"><i class="bi bi-plus-lg me-1"></i> Buat Soal Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($questions->hasPages())
    <div class="card-body border-t border-gray-100">
        {{ $questions->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
