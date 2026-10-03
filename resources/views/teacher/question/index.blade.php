@extends('layouts.teacher')

@section('title', 'Bank Soal Pembelajaran — EduExam')
@section('page_title', 'Bank Soal Pembelajaran')

@section('teacher-content')
<div class="flex flex-col w-full pb-space-xl">
    <!-- Top Banner / Header -->
    <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-lg mb-space-lg shadow-sm border border-slate-100">
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary/5 rounded-full pointer-events-none blur-2xl"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md relative z-10">
            <div class="flex flex-col gap-space-xs">
                <div class="flex items-center gap-space-xs">
                    <span class="px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">Modul Evaluasi Guru</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">• Standar Kurikulum Merdeka</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Bank Soal Pembelajaran</h1>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Koleksi master butir soal terstruktur untuk evaluasi harian, UTS, dan UAS SMA Nusantara.</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-space-sm">
                <button type="button" onclick="alert('Fitur Impor Soal format Excel/Word template sedang disiapkan. Gunakan Tambah Soal Baru untuk saat ini.')" class="inline-flex items-center gap-space-xs px-space-md py-2.5 rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-secondary">upload_file</span>
                    <span>Impor Soal Excel / Word</span>
                </button>
                <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:opacity-95 active:scale-95 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>+ Tambah Soal Baru</span>
                </a>
            </div>
        </div>

        <!-- Quick Stats Bento Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-space-md mt-space-lg pt-space-md bg-surface-container-low/60 p-space-md rounded-xl">
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">quiz</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['total'] ?? $questions->total() }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Total Butir Soal</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined text-[22px]">check_circle</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['multiple_choice'] ?? 0 }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Pilihan Ganda</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-lg bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed-variant">
                    <span class="material-symbols-outlined text-[22px]">edit_note</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['short_essay'] ?? 0 }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Isian &amp; Esai</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface">
                    <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['active_in_exams'] ?? 0 }} Paket</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Aktif di Ujian</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="flex flex-col gap-space-md p-space-md rounded-xl bg-surface-container-lowest shadow-sm mb-space-lg border border-slate-100">
        <form action="{{ route('teacher.questions.index') }}" method="GET" class="flex flex-col gap-space-md">
            <!-- Main Search -->
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-[22px] text-on-surface-variant">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full h-12 pl-11 pr-space-md bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all" placeholder="Cari kata kunci butir soal, topik, atau kompetensi dasar..." type="text"/>
            </div>

            <!-- Filter Multi-Select Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-sm items-end">
                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant">Mata Pelajaran</label>
                    <div class="relative">
                        <select name="subject_id" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Tipe Soal -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant">Bentuk Soal</label>
                    <div class="relative">
                        <select name="type" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua Tipe (Pilihan Ganda, Essay, Isian)</option>
                            <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda (PG)</option>
                            <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            <option value="short_answer" {{ request('type') == 'short_answer' ? 'selected' : '' }}>Isian Singkat</option>
                            <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Uraian / Esai</option>
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Tingkat Kesulitan -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant">Tingkat Kesulitan</label>
                    <div class="relative">
                        <select name="difficulty" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua (Mudah, Sedang, Sulit)</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Sulit / HOTS</option>
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-space-xs">
                    <button type="submit" class="flex-1 h-10 inline-flex items-center justify-center gap-1.5 px-space-md bg-primary-container text-on-primary font-label-md text-label-md rounded-lg shadow-sm hover:opacity-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        <span>Terapkan</span>
                    </button>
                    @if(request('search') || request('subject_id') || request('type') || request('difficulty'))
                        <a href="{{ route('teacher.questions.index') }}" class="h-10 px-space-md inline-flex items-center justify-center text-on-surface-variant hover:text-error bg-surface-container-low rounded-lg font-label-md text-label-md transition-colors" title="Reset Filter">
                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Question List Items -->
    <div class="flex flex-col gap-space-md">
        @forelse($questions as $question)
            <div class="flex flex-col rounded-xl bg-surface-container-lowest p-space-md shadow-sm border border-slate-100/90 transition-all hover:shadow-md">
                <!-- Card Header -->
                <div class="flex flex-wrap items-center justify-between gap-space-xs pb-space-sm border-b border-surface-container">
                    <div class="flex flex-wrap items-center gap-space-xs">
                        <span class="px-2.5 py-1 rounded bg-surface-container-high font-label-md text-label-md text-on-surface font-semibold">Soal #{{ str_pad($question->id, 3, '0', STR_PAD_LEFT) }}</span>
                        
                        @if($question->type === 'multiple_choice')
                            <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm">Pilihan Ganda</span>
                        @elseif($question->type === 'true_false')
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-label-sm text-label-sm">Benar / Salah</span>
                        @elseif($question->type === 'short_answer')
                            <span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm">Isian Singkat</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm">Uraian / Esai</span>
                        @endif

                        @if($question->difficulty === 'easy')
                            <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm">Kesulitan: Mudah</span>
                        @elseif($question->difficulty === 'medium')
                            <span class="px-2.5 py-0.5 rounded-full bg-tertiary-fixed-dim text-on-tertiary-fixed-variant font-label-sm text-label-sm">Kesulitan: Sedang</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm">Kesulitan: Sulit (HOTS)</span>
                        @endif

                        <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">Bobot: {{ $question->score }} Poin</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-1">
                        <a href="{{ route('teacher.questions.edit', $question) }}" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors" title="Edit Soal">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <form action="{{ route('teacher.questions.duplicate', $question) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-secondary transition-colors" title="Duplikat Soal">
                                <span class="material-symbols-outlined text-[20px]">content_copy</span>
                            </button>
                        </form>
                        <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus butir soal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error transition-colors" title="Hapus Soal">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Question Meta & Topic -->
                <div class="flex flex-wrap items-center gap-space-xs pt-space-sm text-on-surface-variant">
                    <span class="font-label-sm text-label-sm font-semibold text-primary">{{ $question->subject->name ?? 'Mata Pelajaran' }}</span>
                    <span>•</span>
                    <span class="font-label-sm text-label-sm">Topik: {{ $question->topic ?? 'Umum' }}</span>
                </div>

                <!-- Question Body -->
                <div class="py-space-sm">
                    <div class="font-body-lg text-body-lg text-on-surface font-semibold leading-relaxed">
                        {{ strip_tags($question->question_text) }}
                    </div>
                    @if($question->question_image)
                        <div class="mt-2.5 flex items-center gap-2">
                            <a href="{{ asset('storage/' . $question->question_image) }}" target="_blank" class="inline-flex items-center gap-1.5 px-space-sm py-1 rounded-lg bg-surface-container-low text-primary font-label-sm text-label-sm hover:underline border border-indigo-100">
                                <span class="material-symbols-outlined text-[16px]">image</span>
                                <span>[Terdapat Lampiran Gambar - Klik untuk melihat]</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Options / Answers Preview -->
                @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->options->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-sm pt-space-xs">
                        @foreach($question->options as $opt)
                            @if($opt->is_correct)
                                <div class="flex items-center justify-between gap-space-xs p-2.5 rounded-lg bg-secondary-container/40 text-on-secondary-container font-semibold border border-secondary/20">
                                    <div class="flex items-center gap-space-xs min-w-0">
                                        <span class="w-6 h-6 rounded-full bg-secondary text-on-secondary flex items-center justify-center font-label-sm text-label-sm font-bold shrink-0">{{ $opt->label ?? chr(65 + $loop->index) }}</span>
                                        <span class="font-body-md text-body-md truncate">{{ $opt->option_text }}</span>
                                    </div>
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">check</span> Kunci
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-space-xs p-2.5 rounded-lg bg-surface-container-low text-on-surface">
                                    <span class="w-6 h-6 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm font-bold shrink-0">{{ $opt->label ?? chr(65 + $loop->index) }}</span>
                                    <span class="font-body-md text-body-md truncate">{{ $opt->option_text }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @elseif($question->type === 'short_answer')
                    <div class="flex flex-wrap items-center gap-space-sm pt-space-xs">
                        <div class="flex items-center gap-space-xs px-space-md py-2 rounded-lg bg-surface-container-low">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Kunci Jawaban Definitif:</span>
                            <span class="font-label-lg text-label-lg font-bold text-secondary">{{ $question->explanation ?: '(Tersimpan di evaluasi otomatis)' }}</span>
                        </div>
                    </div>
                @elseif($question->type === 'essay')
                    <div class="flex flex-wrap items-center gap-space-sm pt-space-xs">
                        <div class="flex items-center gap-space-xs px-space-md py-2 rounded-lg bg-surface-container-low">
                            <span class="material-symbols-outlined text-[18px] text-primary">rule</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Panduan Rubrik Penilaian: {{ Str::limit($question->explanation ?: 'Penilaian kualitatif berjenjang oleh guru pengampu', 90) }}</span>
                        </div>
                    </div>
                @endif

                <!-- Footer Usage Info -->
                <div class="mt-space-md pt-space-xs flex flex-wrap items-center justify-between gap-2 text-on-surface-variant border-t border-slate-50">
                    <div class="flex items-center gap-1.5">
                        @if($question->exams && $question->exams->count() > 0)
                            <span class="material-symbols-outlined text-[16px] text-primary">history_edu</span>
                            <span class="font-label-sm text-label-sm">Digunakan di {{ $question->exams->count() }} Ujian ({{ $question->exams->pluck('title')->take(2)->join(', ') }}{{ $question->exams->count() > 2 ? ' ...' : '' }})</span>
                        @else
                            <span class="material-symbols-outlined text-[16px] text-tertiary">warning</span>
                            <span class="font-label-sm text-label-sm">Belum pernah dipakai pada paket ujian manapun</span>
                        @endif
                    </div>
                    <span class="font-label-sm text-label-sm">Terakhir diperbarui: {{ $question->updated_at ? $question->updated_at->diffForHumans() : 'Baru saja' }}</span>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center p-12 bg-surface-container-lowest rounded-xl border border-slate-100 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-4xl">quiz</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1 font-bold">Belum Ada Butir Soal Ditemukan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-md mb-4">
                    @if(request('search') || request('subject_id') || request('type') || request('difficulty'))
                        Tidak ada butir soal yang sesuai dengan kriteria filter pencarian Anda. Coba atur ulang filter pencarian.
                    @else
                        Bank soal Anda masih kosong. Mulai buat soal pertama Anda untuk paket ujian pembelajaran siswa.
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    @if(request('search') || request('subject_id') || request('type') || request('difficulty'))
                        <a href="{{ route('teacher.questions.index') }}" class="px-space-md py-2.5 rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-colors">
                            Reset Filter
                        </a>
                    @endif
                    <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-1.5 px-space-md py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:opacity-95 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>Tambah Soal Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bar -->
    @if($questions->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md mt-space-lg p-space-md rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
            <div class="flex items-center gap-space-xs font-body-sm text-body-sm text-on-surface-variant">
                <span>Menampilkan</span>
                <span class="font-label-md text-label-md text-on-surface font-bold">{{ $questions->firstItem() }} - {{ $questions->lastItem() }}</span>
                <span>dari</span>
                <span class="font-label-md text-label-md text-on-surface font-bold">{{ $questions->total() }}</span>
                <span>butir soal</span>
            </div>
            <div>
                {{ $questions->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    @endif
</div>
@endsection
