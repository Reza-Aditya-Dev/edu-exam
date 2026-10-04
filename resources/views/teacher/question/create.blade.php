@extends('layouts.teacher')

@section('title', 'Buat Butir Soal Baru — EduExam')
@section('page_title', 'Bank Soal')

@section('teacher-content')
<div class="flex flex-col w-full pb-20">

    <!-- Form Tag Wrapping Workspace -->
    <form action="{{ route('teacher.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
        @csrf
        <input type="hidden" name="type" id="questionType" value="{{ old('type', 'multiple_choice') }}">

        <!-- Top Notification Pill / Status Banner -->
        <div class="flex flex-wrap items-center justify-between gap-space-md py-space-sm mb-space-md">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
                    <a href="{{ route('teacher.questions.index') }}" class="hover:text-primary transition-colors">Bank Soal</a>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-on-surface-variant">Modul Evaluasi Guru</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-primary font-semibold">Tambah Soal Baru</span>
                </div>
                <div class="flex items-baseline gap-space-sm">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Buat Butir Soal Baru</h1>
                    <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant font-label-sm text-label-sm font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Siap Diterbitkan
                    </span>
                </div>
            </div>

            <!-- Main Action Buttons -->
            <div class="flex items-center gap-space-sm flex-wrap">
                <a href="{{ route('teacher.questions.index') }}" class="px-space-md py-2.5 rounded-lg bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest transition-colors font-label-lg text-label-lg">
                    Batal
                </a>
                <button type="button" onclick="openSmartPasteModal()" class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-surface-container-lowest text-on-surface shadow-sm hover:bg-surface-container-low transition-all font-label-lg text-label-lg border border-slate-200/80">
                    <span class="material-symbols-outlined text-[18px] text-secondary">content_paste</span>
                    <span>Smart Paste Word</span>
                </button>
                <button type="button" onclick="openPreviewModal()" class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-surface-container-lowest text-primary shadow-sm hover:bg-surface-container-low transition-all font-label-lg text-label-lg border border-slate-200/80">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                    <span>Pratinjau Siswa</span>
                </button>
                <button type="button" onclick="handleFormSubmission()" class="inline-flex items-center gap-2 px-space-lg py-2.5 rounded-lg bg-primary-container text-on-primary shadow-md hover:bg-primary transition-all font-label-lg text-label-lg active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span id="btnMainSaveText">Simpan ke Bank Soal</span>
                </button>
            </div>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="mb-space-md p-space-md rounded-xl bg-error-container text-on-error-container text-xs">
                <div class="font-bold mb-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    Terdapat beberapa isian yang perlu diperiksa:
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MULTI-QUESTION CONTROLLER BAR (Navigasi Multi-Soal 40+ Butir Sekaligus) -->
        <div class="bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-slate-100 mb-space-lg flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-2 overflow-x-auto pb-1.5 lg:pb-0 scrollbar-thin max-w-full lg:max-w-3xl" id="questionTabsContainer">
                <!-- Question tabs generated dynamically via JS: [Soal 1] [Soal 2] ... -->
            </div>
            
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <button type="button" onclick="addNewQuestionSlot()" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md font-semibold transition-all flex items-center gap-1 border border-slate-200/80 active:scale-95" title="Tambah 1 butir soal">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>+ Tambah Soal</span>
                </button>
                <button type="button" onclick="bulkGenerateSlots(40)" class="px-3.5 py-1.5 rounded-lg bg-primary-fixed hover:bg-primary-fixed-dim text-on-primary-fixed-variant font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-95" title="Siapkan 40 slot soal sekaligus">
                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                    <span>Buat 40 Soal Sekaligus</span>
                </button>
                <button type="button" onclick="duplicateCurrentQuestion()" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-secondary transition-colors" title="Duplikasi butir soal aktif ini">
                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                </button>
                <button type="button" onclick="removeCurrentQuestion()" id="btnDeleteCurrentQ" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error transition-colors hidden" title="Hapus butir soal aktif ini">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        </div>

        <!-- Workspace Grid (2 Columns: Main Editor + Live Sidebar Inspector) -->
        <div class="grid grid-cols-12 gap-space-lg items-start">
            
            <!-- LEFT COLUMN: Main Form & Question Canvas (8 cols) -->
            <div class="col-span-12 xl:col-span-8 flex flex-col gap-space-lg">
                
                <!-- Section 1: Metadata Card -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div class="flex items-center justify-between pb-space-xs">
                        <div class="flex items-center gap-space-xs text-primary font-label-lg text-label-lg font-bold">
                            <span class="material-symbols-outlined text-[20px]">tune</span>
                            <span>Metadata &amp; Klasifikasi Soal</span>
                        </div>
                        <span id="activeQuestionIdBadge" class="font-label-sm text-label-sm text-on-surface-variant bg-surface-container-low px-2.5 py-0.5 rounded-md font-mono">Soal #1 • Q-{{ date('Y') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
                        <!-- Mata Pelajaran -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-sm text-label-sm text-on-surface-variant">Mata Pelajaran <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="subject_id" id="subjectSelect" class="w-full h-11 pl-3 pr-8 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface appearance-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none cursor-pointer" required onchange="updateLivePreview()">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($subjects as $sub)
                                        <option value="{{ $sub->id }}" {{ (string)old('subject_id', request('subject_id')) === (string)$sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-2.5 top-3 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Target Kelas -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-sm text-label-sm text-on-surface-variant">Target Kelas <span class="text-on-surface-variant/60 font-normal">(opsional)</span></label>
                            <div class="relative">
                                <select name="classroom_id" id="classroomSelect" class="w-full h-11 pl-3 pr-8 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface appearance-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none cursor-pointer" onchange="updateLivePreview()">
                                    <option value="">-- Semua Kelas (Umum) --</option>
                                    @foreach($classrooms as $cls)
                                        <option value="{{ $cls->id }}" {{ (string)old('classroom_id', request('classroom_id')) === (string)$cls->id ? 'selected' : '' }}>Kelas {{ $cls->name }} (Tingkat {{ $cls->grade }})</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-2.5 top-3 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Topik / Materi Pokok -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-sm text-label-sm text-on-surface-variant">Topik / Materi Pokok <span class="text-on-surface-variant/60 font-normal">(opsional)</span></label>
                            <input name="topic" id="topicInput" class="w-full h-11 px-3 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none" placeholder="Contoh: Persamaan Linier Satu Variabel" type="text" value="{{ old('topic', request('topic')) }}" oninput="updateLivePreview()"/>
                        </div>
                    </div>

                    <!-- Tipe Soal Segmented Selector -->
                    <div class="flex flex-col gap-2 pt-space-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant">Bentuk / Tipe Asesmen <span class="text-error">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-xs p-1 bg-surface-container-low rounded-lg" id="typeSelectorGroup">
                            <button type="button" onclick="setType('multiple_choice')" class="type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg bg-surface-container-lowest text-primary shadow-sm font-label-md text-label-md transition-all font-semibold" data-type="multiple_choice">
                                <span class="material-symbols-outlined text-[18px]">radio_button_checked</span>
                                <span>Pilihan Ganda</span>
                            </button>
                            <button type="button" onclick="setType('true_false')" class="type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all font-label-md text-label-md" data-type="true_false">
                                <span class="material-symbols-outlined text-[18px]">check_box</span>
                                <span>Benar / Salah</span>
                            </button>
                            <button type="button" onclick="setType('short_answer')" class="type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all font-label-md text-label-md" data-type="short_answer">
                                <span class="material-symbols-outlined text-[18px]">short_text</span>
                                <span>Isian Singkat</span>
                            </button>
                            <button type="button" onclick="setType('essay')" class="type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all font-label-md text-label-md" data-type="essay">
                                <span class="material-symbols-outlined text-[18px]">article</span>
                                <span>Essay Terbuka</span>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Question Editor (Rich Text & Media Canvas) -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span id="stepNumberIndicator" class="w-7 h-7 rounded-lg bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-headline-sm text-headline-sm font-bold">1</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold" id="questionEditorHeading">Redaksi Pertanyaan (Stimulus Soal #1)</h2>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Mendukung format teks kaya &amp; lampiran gambar</span>
                    </div>

                    <!-- Editor Toolbar (Quick Formatting Helpers) -->
                    <div class="flex flex-wrap items-center gap-1 p-1.5 bg-surface-container-low rounded-lg text-on-surface-variant border border-slate-100">
                        <button type="button" onclick="wrapText('**', '**')" class="w-8 h-8 rounded flex items-center justify-center hover:bg-surface-container-high text-on-surface transition-colors" title="Tebal (Bold)">
                            <span class="material-symbols-outlined text-[18px]">format_bold</span>
                        </button>
                        <button type="button" onclick="wrapText('*', '*')" class="w-8 h-8 rounded flex items-center justify-center hover:bg-surface-container-high text-on-surface transition-colors" title="Miring (Italic)">
                            <span class="material-symbols-outlined text-[18px]">format_italic</span>
                        </button>
                        <button type="button" onclick="wrapText('<u>', '</u>')" class="w-8 h-8 rounded flex items-center justify-center hover:bg-surface-container-high text-on-surface transition-colors" title="Garis Bawah (Underline)">
                            <span class="material-symbols-outlined text-[18px]">format_underlined</span>
                        </button>
                        <div class="w-px h-5 bg-outline-variant/40 mx-1"></div>
                        <button type="button" onclick="insertFormula()" class="h-8 px-2.5 rounded flex items-center gap-1 bg-surface-container-lowest text-primary shadow-sm font-label-sm text-label-sm hover:bg-surface-container-high transition-colors" title="Sisipkan Rumus Matematika">
                            <span class="material-symbols-outlined text-[18px]">functions</span>
                            <span>Formula fx</span>
                        </button>
                        <button type="button" onclick="insertList()" class="w-8 h-8 rounded flex items-center justify-center hover:bg-surface-container-high text-on-surface transition-colors" title="Daftar Poin">
                            <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
                        </button>
                        <button type="button" onclick="insertQuote()" class="w-8 h-8 rounded flex items-center justify-center hover:bg-surface-container-high text-on-surface transition-colors" title="Kutipan / Stimulus">
                            <span class="material-symbols-outlined text-[18px]">format_quote</span>
                        </button>
                        <div class="ml-auto flex items-center gap-1">
                            <span class="text-xs text-on-surface-variant font-medium pr-1" id="charWordCounter">0 Karakter • 0 Kata</span>
                        </div>
                    </div>

                    <!-- Editable Question Content Area -->
                    <div class="relative">
                        <textarea name="question_text" id="questionTextInput" class="w-full p-4 bg-surface-container-low rounded-lg font-body-lg text-body-lg text-on-surface focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 focus:ring-2 focus:ring-primary/20 outline-none leading-relaxed transition-all resize-y" placeholder="Tuliskan butir soal atau stimulus pertanyaan di sini..." rows="4" required oninput="updateLivePreview()">{{ old('question_text') }}</textarea>
                    </div>

                    <!-- Attached Graphic / Media Dropzone & Preview -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-sm text-label-sm text-on-surface-variant flex items-center justify-between">
                            <span class="flex items-center gap-1 font-semibold">
                                <span class="material-symbols-outlined text-[16px]">image</span>
                                <span>Gambar Pendukung / Grafik Soal</span>
                            </span>
                            <span class="text-on-surface-variant font-normal">Format: JPG, PNG, WebP (Maks. 2MB)</span>
                        </label>

                        <input type="file" name="question_image" id="questionImageInput" class="hidden" accept="image/*" onchange="handleImageUpload(event)">

                        <!-- Empty State Dropzone -->
                        <div id="imageUploadDropzone" onclick="document.getElementById('questionImageInput').click()" class="p-6 rounded-xl bg-surface-container-low border border-dashed border-slate-300 hover:border-primary cursor-pointer flex flex-col items-center justify-center text-center transition-all">
                            <span class="material-symbols-outlined text-3xl text-primary mb-1">add_photo_alternate</span>
                            <span class="font-label-md text-label-md text-on-surface font-semibold">Klik untuk mengunggah gambar grafik / diagram soal</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Tampilan otomatis dioptimalkan untuk layar CBT siswa</span>
                        </div>

                        <!-- Uploaded Preview Card -->
                        <div id="imagePreviewCard" class="hidden p-space-md rounded-xl bg-surface-container-low flex flex-col md:flex-row items-center gap-space-md border border-slate-200">
                            <div class="relative w-full md:w-56 h-36 rounded-lg overflow-hidden shadow-sm flex-shrink-0 bg-surface-container-highest">
                                <img id="previewImageTag" class="w-full h-full object-cover" src="" alt="Lampiran Soal">
                                <span id="previewImageNameBadge" class="absolute top-2 left-2 px-2 py-0.5 rounded bg-on-surface/75 text-on-primary font-label-sm text-label-sm backdrop-blur-sm truncate max-w-[90%]">
                                    Lampiran.png
                                </span>
                            </div>
                            <div class="flex flex-col justify-between h-full w-full gap-space-sm">
                                <div class="flex flex-col gap-1">
                                    <span class="font-label-lg text-label-lg text-on-surface font-semibold" id="previewImageTitle">Gambar Lampiran Butir Soal</span>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant" id="previewImageInfo">Ukuran file siap diproses.</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">Lampiran Aktif</span>
                                        <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm">Siap Tampil di CBT</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-space-sm pt-2">
                                    <button type="button" onclick="document.getElementById('questionImageInput').click()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest text-on-surface shadow-sm hover:bg-surface-container-high transition-colors font-label-md text-label-md">
                                        <span class="material-symbols-outlined text-[16px] text-primary">sync</span>
                                        <span>Ganti Gambar</span>
                                    </button>
                                    <button type="button" onclick="removeImageUpload()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-error-container/40 text-error hover:bg-error-container transition-colors font-label-md text-label-md">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Answer Options (Pilihan Ganda & Benar/Salah) -->
                <section id="multipleChoiceSection" class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 pb-space-xs">
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pilihan Ganda &amp; Kunci Jawaban</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Klik lingkaran huruf pada opsi yang menjadi kunci jawaban benar.</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                            <span class="material-symbols-outlined text-[14px]">auto_awesome</span> Kunci Otomatis Dinilai
                        </span>
                    </div>

                    <!-- Options Container (Dynamic) -->
                    <div id="optionsListContainer" class="flex flex-col gap-space-sm">
                        <!-- Rendered by JavaScript -->
                    </div>

                    <button type="button" id="btnAddOption" onclick="addOptionRow()" class="inline-flex items-center justify-center gap-2 py-2.5 px-space-md rounded-lg bg-surface-container-low hover:bg-surface-container-high text-primary font-label-lg text-label-lg transition-all w-full md:w-auto self-start border border-slate-200/60 font-semibold">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span id="btnAddOptionText">+ Tambah Opsi Jawaban</span>
                    </button>
                </section>

                <!-- True / False Alternative Section -->
                <section id="trueFalseSection" class="hidden bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pernyataan Benar / Salah</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Pilih apakah pernyataan pada pertanyaan di atas bernilai BENAR atau SALAH.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                        <label class="tf-card flex items-center gap-3 p-4 rounded-xl bg-surface-container-low border border-slate-200 hover:border-secondary cursor-pointer transition-all">
                            <input type="radio" name="tf_correct_choice" value="0" checked onchange="setTrueFalseAnswer(0)" class="w-5 h-5 accent-secondary">
                            <div>
                                <div class="font-bold text-base text-secondary">BENAR</div>
                                <div class="text-xs text-on-surface-variant">Pernyataan stimulus valid / benar</div>
                            </div>
                        </label>
                        <label class="tf-card flex items-center gap-3 p-4 rounded-xl bg-surface-container-low border border-slate-200 hover:border-error cursor-pointer transition-all">
                            <input type="radio" name="tf_correct_choice" value="1" onchange="setTrueFalseAnswer(1)" class="w-5 h-5 accent-error">
                            <div>
                                <div class="font-bold text-base text-error">SALAH</div>
                                <div class="text-xs text-on-surface-variant">Pernyataan stimulus salah / keliru</div>
                            </div>
                        </label>
                    </div>
                </section>

                <!-- Short Answer / Essay Alternative Info -->
                <section id="essayInfoSection" class="hidden bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-xs border border-slate-100">
                    <div class="flex items-center gap-2 text-primary font-label-lg text-label-lg font-bold">
                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                        <span id="essayInfoTitle">Format Soal Isian / Essay</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" id="essayInfoDesc">
                        Siswa akan diberikan kotak isian untuk menjawab secara mandiri. Tuliskan kunci atau rubrik penilaian pada bagian pembahasan di bawah.
                    </p>
                </section>

                <!-- Section 4: Pembahasan & Penjelasan Soal -->
                <section class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-tertiary-container text-[22px]">lightbulb</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pembahasan &amp; Kunci Solusi</h2>
                        </div>
                        <span class="px-space-sm py-0.5 rounded bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-semibold">Rilis Pasca Ujian</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant -mt-2">Penjelasan ini akan otomatis ditampilkan kepada siswa saat jadwal pembahasan dibuka atau hasil ujian dirilis.</p>
                    <div class="relative">
                        <textarea name="explanation" id="explanationInput" class="w-full p-4 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 focus:ring-2 focus:ring-primary/20 outline-none transition-all leading-relaxed" placeholder="Tuliskan langkah-langkah penyelesaian rinci atau rujukan materi..." rows="3" oninput="updateLivePreview()">{{ old('explanation') }}</textarea>
                    </div>
                    <div class="flex items-center gap-space-md p-space-sm bg-surface-container-low rounded-lg text-on-surface-variant font-body-sm text-body-sm border border-slate-100">
                        <span class="material-symbols-outlined text-[20px] text-primary">info</span>
                        <span>Tips Guru: Sertakan rujukan bab buku paket atau materi kompetensi dasar pembelajaran.</span>
                    </div>
                </section>
            </div>

            <!-- RIGHT COLUMN: Parameters, Scoring, Tags & Live Preview (4 cols) -->
            <div class="col-span-12 xl:col-span-4 flex flex-col gap-space-lg sticky top-24">
                
                <!-- Scoring & Parameters Inspector Card -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-lg border border-slate-100">
                    <div class="flex items-center justify-between pb-space-xs">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Parameter Asesmen</h3>
                        <span class="material-symbols-outlined text-primary text-[20px]">equalizer</span>
                    </div>

                    <!-- Bobot Nilai (Poin) -->
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Bobot Nilai (Poin) <span class="text-error">*</span></label>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Skala 0 - 100</span>
                        </div>
                        <div class="flex items-center justify-between bg-surface-container-low rounded-xl p-1.5 border border-slate-100">
                            <button type="button" onclick="adjustScore(-0.5)" class="w-10 h-10 rounded-lg bg-surface-container-lowest text-on-surface shadow-sm hover:bg-surface-container-high transition-all flex items-center justify-center font-headline-sm text-headline-sm font-bold">
                                -
                            </button>
                            <div class="flex items-baseline gap-1">
                                <input name="score" id="scoreInput" class="w-20 text-center font-headline-md text-headline-md font-bold bg-transparent text-primary outline-none" type="number" step="0.5" min="0" max="100" value="{{ old('score', 2.5) }}" oninput="updateLivePreview()" required/>
                                <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">poin</span>
                            </div>
                            <button type="button" onclick="adjustScore(0.5)" class="w-10 h-10 rounded-lg bg-surface-container-lowest text-on-surface shadow-sm hover:bg-surface-container-high transition-all flex items-center justify-center font-headline-sm text-headline-sm font-bold">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Tingkat Kesulitan (Radio Pills) -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">Tingkat Kesulitan Soal <span class="text-error">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="diff-card flex flex-col items-center justify-center py-2.5 px-2 rounded-lg bg-surface-container-low hover:bg-surface-container-high cursor-pointer transition-all text-center border border-slate-100">
                                <input name="difficulty" type="radio" value="easy" class="sr-only" {{ old('difficulty') == 'easy' ? 'checked' : '' }} onchange="setDifficulty('easy')"/>
                                <span class="font-label-md text-label-md text-on-surface font-semibold">Mudah</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">LOTS</span>
                            </label>
                            <label class="diff-card flex flex-col items-center justify-center py-2.5 px-2 rounded-lg bg-tertiary-fixed text-on-tertiary-fixed shadow-sm cursor-pointer transition-all text-center ring-2 ring-tertiary-container/30">
                                <input name="difficulty" type="radio" value="medium" class="sr-only" {{ old('difficulty', 'medium') == 'medium' ? 'checked' : '' }} onchange="setDifficulty('medium')" checked/>
                                <span class="font-label-md text-label-md font-bold">Sedang</span>
                                <span class="font-label-sm text-label-sm mt-0.5">MOTS</span>
                            </label>
                            <label class="diff-card flex flex-col items-center justify-center py-2.5 px-2 rounded-lg bg-surface-container-low hover:bg-surface-container-high cursor-pointer transition-all text-center border border-slate-100">
                                <input name="difficulty" type="radio" value="hard" class="sr-only" {{ old('difficulty') == 'hard' ? 'checked' : '' }} onchange="setDifficulty('hard')"/>
                                <span class="font-label-md text-label-md text-on-surface font-semibold">Sulit</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">HOTS</span>
                            </label>
                        </div>
                    </div>

                    <!-- Dimensi Kognitif Bloom -->
                    <div class="flex flex-col gap-1.5 pt-space-xs">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Estimasi Dimensi Kognitif</label>
                        <div class="p-3 rounded-lg bg-surface-container-high/40 flex items-center justify-between border border-slate-100">
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md text-on-surface font-semibold" id="bloomLabel">C3 - Mengaplikasikan</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant" id="bloomDesc">Penerapan konsep &amp; penyelesaian model</span>
                            </div>
                            <span class="material-symbols-outlined text-primary text-[20px]">psychology</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Live Student Card Preview (Pratinjau Cepat Kotak Kartu Siswa) -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-lg flex flex-col gap-space-md border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">devices</span>
                            <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Simulasi Siswa</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-secondary bg-secondary-fixed/50 px-2 py-0.5 rounded-full font-semibold">Live Mode</span>
                    </div>

                    <!-- Student Simulation Frame Container -->
                    <div class="rounded-xl bg-surface p-space-md shadow-inner flex flex-col gap-space-md border border-slate-200/80">
                        <!-- Mini Sticky Header Simulation -->
                        <div class="flex items-center justify-between pb-2 border-b border-surface-container">
                            <div class="flex items-center gap-1.5">
                                <span id="simQuestionNumberBadge" class="w-6 h-6 rounded bg-primary-container text-on-primary font-label-sm text-label-sm flex items-center justify-center font-bold">01</span>
                                <span id="simQuestionNumberTitle" class="font-label-sm text-label-sm text-on-surface font-semibold">Soal No. 1</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant tabular-nums flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-tertiary">timer</span> 01:15:00
                            </span>
                        </div>

                        <!-- Question Prompt Preview -->
                        <p class="font-body-md text-body-md text-on-surface leading-normal font-medium" id="simQuestionText">
                            Belum ada teks pertanyaan. Ketik pada formulir di sebelah kiri...
                        </p>

                        <!-- Embedded Image Thumbnail -->
                        <div id="simImageContainer" class="hidden h-32 rounded-lg overflow-hidden bg-surface-container-highest shadow-sm">
                            <img id="simImageTag" class="w-full h-full object-cover" src="" alt="Grafik Soal">
                        </div>

                        <!-- Answer Option Mini Cards -->
                        <div id="simOptionsList" class="flex flex-col gap-1.5">
                            <!-- Options rendered dynamically -->
                        </div>

                        <!-- Bottom Action Buttons Simulation -->
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div class="py-1.5 px-2 rounded bg-tertiary-fixed text-on-tertiary-fixed text-center font-label-sm text-label-sm flex items-center justify-center gap-1 font-semibold">
                                <span class="material-symbols-outlined text-[14px]">bookmark</span> Ragu-ragu
                            </div>
                            <div class="py-1.5 px-2 rounded bg-primary-container text-on-primary text-center font-label-sm text-label-sm flex items-center justify-center gap-1 font-semibold">
                                <span>Selanjutnya</span>
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </div>
                        </div>
                    </div>

                    <p class="font-label-sm text-label-sm text-on-surface-variant text-center">
                        Tampilan ini merender pratinjau langsung seperti pada layar ujian Chromebook / Android siswa.
                    </p>
                </div>

                <!-- Palette Navigasi Butir Soal (Sidebar Quick Jump) -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-md border border-slate-100 flex flex-col gap-2.5">
                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-100">
                        <span class="font-label-md text-label-md font-bold text-on-surface flex items-center gap-1">
                            <span class="material-symbols-outlined text-primary text-[18px]">grid_view</span>
                            <span>Palet Butir Soal</span>
                        </span>
                        <span id="paletteTotalBadge" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-surface-container text-primary font-mono">1 Soal</span>
                    </div>
                    <div id="sidebarPaletteGrid" class="grid grid-cols-5 gap-1.5 max-h-56 overflow-y-auto pr-1">
                        <!-- Buttons 1, 2, ... 40 populated via JS -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Dynamic Hidden Container for Multi-Question Payload Submission -->
        <div id="bulkPayloadInputs"></div>
    </form>
</div>

<!-- Modal Pratinjau Tampilan Siswa (Full-Screen Preview) -->
<div id="studentPreviewModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
        <div class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">visibility</span>
                <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Pratinjau Layar Siswa</span>
            </div>
            <button type="button" onclick="closePreviewModal()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-space-lg overflow-y-auto flex flex-col gap-space-md">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span id="modalPreviewNumberBadge" class="w-7 h-7 rounded bg-primary-container text-on-primary font-label-md text-label-md flex items-center justify-center font-bold">1</span>
                    <span class="font-label-md text-label-md text-on-surface font-semibold" id="modalPreviewSubject">Matematika</span>
                </div>
                <span class="font-label-sm text-label-sm text-secondary font-semibold" id="modalPreviewScore">Bobot: 2.5 Poin</span>
            </div>
            <div class="font-body-lg text-body-lg text-on-surface leading-relaxed" id="modalPreviewQuestion"></div>
            <div id="modalPreviewImageContainer" class="hidden rounded-xl overflow-hidden border border-slate-200 max-h-72">
                <img id="modalPreviewImage" src="" alt="Lampiran Soal" class="w-full h-full object-contain bg-slate-50">
            </div>
            <div id="modalPreviewOptions" class="flex flex-col gap-2 pt-2"></div>
        </div>
        <div class="p-space-md border-t border-surface-container flex justify-end">
            <button type="button" onclick="closePreviewModal()" class="px-space-md py-2 rounded-lg bg-surface-container-high text-on-surface font-label-md text-label-md hover:bg-surface-container transition-colors">
                Tutup Pratinjau
            </button>
        </div>
    </div>
</div>

<!-- Modal Smart Paste dari Word / Teks -->
<div id="smartPasteModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
        <div class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">content_paste</span>
                <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Smart Paste / Impor Teks dari Word</span>
            </div>
            <button type="button" onclick="closeSmartPasteModal()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-space-lg overflow-y-auto flex flex-col gap-3">
            <div class="p-3 rounded-xl bg-primary-fixed/30 border border-primary-fixed text-xs text-on-surface leading-relaxed">
                <strong>Format Teks yang Dikenali Otomatis:</strong>
                <pre class="mt-1 p-2 rounded bg-white font-mono text-[11px] text-slate-700 border border-slate-200">
1. Pertanyaan butir nomor 1...
A. Pilihan A
B. Pilihan B
C. Pilihan C
D. Pilihan D
E. Pilihan E
Kunci: B

2. Pertanyaan butir nomor 2...
A. Pilihan A
B. Pilihan B
Kunci: A</pre>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-sm text-label-sm text-on-surface font-semibold">Tempel Teks Soal Dokumen di Sini:</label>
                <textarea id="smartPasteInput" rows="10" class="w-full p-3 bg-surface-container-low rounded-xl font-mono text-xs text-on-surface border border-slate-200 outline-none focus:bg-white focus:border-indigo-400" placeholder="Paste naskah soal dari Word di sini..."></textarea>
            </div>
        </div>
        <div class="p-space-md border-t border-surface-container flex items-center justify-between">
            <button type="button" onclick="fillSmartPasteDemo()" class="text-xs font-semibold text-primary hover:underline">
                Isi Contoh Demo 5 Soal
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeSmartPasteModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold">
                    Batal
                </button>
                <button type="button" onclick="executeSmartPaste()" class="px-5 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-container shadow-md">
                    Proses ke Lembar Soal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // State: Array of all questions in this session
    let questionsList = [
        {
            type: 'multiple_choice',
            question_text: '',
            question_image_base64: null,
            image_name: '',
            difficulty: 'medium',
            score: 2.5,
            topic: '',
            explanation: '',
            correct_option: 1,
            options: [
                { label: 'A', text: '3' },
                { label: 'B', text: '5' },
                { label: 'C', text: '7' },
                { label: 'D', text: '10' }
            ]
        }
    ];

    let currentQuestionIndex = 0;
    let defaultOptions = questionsList[0].options;
    let currentType = questionsList[0].type;
    let currentCorrectOption = questionsList[0].correct_option;
    let uploadedImageDataUrl = null;

    document.addEventListener('DOMContentLoaded', function() {
        const oldType = document.getElementById('questionType').value;
        if(oldType) setType(oldType);

        renderQuestionTabs();
        loadQuestionIntoForm(0);
        updateLivePreview();
    });

    /**
     * MULTI-QUESTION MANAGEMENT
     */
    function renderQuestionTabs() {
        const tabsContainer = document.getElementById('questionTabsContainer');
        tabsContainer.innerHTML = '';

        questionsList.forEach((q, idx) => {
            const isActive = (idx === currentQuestionIndex);
            const isFilled = q.question_text && q.question_text.trim() !== '';

            const tabBtn = document.createElement('button');
            tabBtn.type = 'button';
            tabBtn.className = `flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shrink-0 active:scale-95 ${isActive ? 'bg-primary-container text-on-primary shadow-sm ring-1 ring-primary' : 'bg-surface-container-low hover:bg-surface-container-high text-on-surface border border-slate-200/80'}`;
            tabBtn.innerHTML = `
                <span>Soal #${idx + 1}</span>
                ${isFilled ? '<span class="w-2 h-2 rounded-full bg-emerald-500"></span>' : ''}
            `;
            tabBtn.onclick = () => switchQuestion(idx);
            tabsContainer.appendChild(tabBtn);
        });

        // Update delete button visibility
        const delBtn = document.getElementById('btnDeleteCurrentQ');
        if (delBtn) {
            if (questionsList.length > 1) {
                delBtn.classList.remove('hidden');
            } else {
                delBtn.classList.add('hidden');
            }
        }

        // Update main save button label
        const saveBtn = document.getElementById('btnMainSaveText');
        if (saveBtn) {
            if (questionsList.length > 1) {
                saveBtn.innerText = `Simpan Seluruh (${questionsList.length}) Soal ke Bank Soal`;
            } else {
                saveBtn.innerText = 'Simpan ke Bank Soal';
            }
        }

        // Update right palette grid
        renderSidebarPalette();
    }

    function renderSidebarPalette() {
        const grid = document.getElementById('sidebarPaletteGrid');
        if (!grid) return;
        grid.innerHTML = '';

        questionsList.forEach((q, idx) => {
            const isActive = (idx === currentQuestionIndex);
            const isFilled = q.question_text && q.question_text.trim() !== '';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `h-9 rounded-lg text-xs font-bold transition-all flex items-center justify-center ${isActive ? 'ring-2 ring-primary bg-primary-container text-on-primary font-bold shadow-sm' : (isFilled ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-surface-container hover:bg-surface-container-high text-on-surface-variant')}`;
            btn.innerText = idx + 1;
            btn.onclick = () => switchQuestion(idx);
            grid.appendChild(btn);
        });

        document.getElementById('paletteTotalBadge').innerText = `${questionsList.length} Soal`;
    }

    function saveCurrentQuestionToMemory(idx) {
        if (!questionsList[idx]) return;

        questionsList[idx].question_text = document.getElementById('questionTextInput').value;
        questionsList[idx].topic = document.getElementById('topicInput').value;
        questionsList[idx].type = currentType;
        questionsList[idx].score = parseFloat(document.getElementById('scoreInput').value) || 2.5;
        questionsList[idx].difficulty = document.querySelector('input[name="difficulty"]:checked')?.value || 'medium';
        questionsList[idx].explanation = document.getElementById('explanationInput').value;
        questionsList[idx].correct_option = currentCorrectOption;
        questionsList[idx].options = JSON.parse(JSON.stringify(defaultOptions));
        questionsList[idx].question_image_base64 = uploadedImageDataUrl;
    }

    function loadQuestionIntoForm(idx) {
        currentQuestionIndex = idx;
        const q = questionsList[idx];

        // Update headers & badges
        document.getElementById('activeQuestionIdBadge').innerText = `Soal #${idx + 1} • Q-${new Date().getFullYear()}`;
        document.getElementById('stepNumberIndicator').innerText = idx + 1;
        document.getElementById('questionEditorHeading').innerText = `Redaksi Pertanyaan (Stimulus Soal #${idx + 1})`;
        document.getElementById('simQuestionNumberBadge').innerText = String(idx + 1).padStart(2, '0');
        document.getElementById('simQuestionNumberTitle').innerText = `Soal No. ${idx + 1}`;
        document.getElementById('modalPreviewNumberBadge').innerText = idx + 1;

        // Populate fields
        document.getElementById('questionTextInput').value = q.question_text || '';
        document.getElementById('scoreInput').value = q.score || 2.5;
        document.getElementById('explanationInput').value = q.explanation || '';

        // Difficulty
        setDifficulty(q.difficulty || 'medium');

        // Type
        setType(q.type || 'multiple_choice');

        // Options
        defaultOptions = q.options && q.options.length ? JSON.parse(JSON.stringify(q.options)) : [
            { label: 'A', text: '' },
            { label: 'B', text: '' },
            { label: 'C', text: '' },
            { label: 'D', text: '' }
        ];
        currentCorrectOption = (q.correct_option !== undefined) ? q.correct_option : 0;
        renderOptionsList();

        // Image
        uploadedImageDataUrl = q.question_image_base64 || null;
        if (uploadedImageDataUrl) {
            document.getElementById('previewImageTag').src = uploadedImageDataUrl;
            document.getElementById('previewImageNameBadge').textContent = q.image_name || 'Lampiran.png';
            document.getElementById('previewImageTitle').textContent = q.image_name || 'Gambar Lampiran Soal';
            document.getElementById('previewImageInfo').textContent = 'Gambar siap disimpan';
            document.getElementById('imageUploadDropzone').classList.add('hidden');
            document.getElementById('imagePreviewCard').classList.remove('hidden');
        } else {
            removeImageUpload(false);
        }

        renderQuestionTabs();
        updateLivePreview();
    }

    function switchQuestion(newIdx) {
        if (newIdx === currentQuestionIndex) return;
        saveCurrentQuestionToMemory(currentQuestionIndex);
        loadQuestionIntoForm(newIdx);
    }

    function addNewQuestionSlot() {
        saveCurrentQuestionToMemory(currentQuestionIndex);

        const newIndex = questionsList.length;
        const currentScore = parseFloat(document.getElementById('scoreInput').value) || 2.5;
        const currentDiff = document.querySelector('input[name="difficulty"]:checked')?.value || 'medium';

        questionsList.push({
            type: 'multiple_choice',
            question_text: '',
            question_image_base64: null,
            image_name: '',
            difficulty: currentDiff,
            score: currentScore,
            topic: document.getElementById('topicInput').value || '',
            explanation: '',
            correct_option: 0,
            options: [
                { label: 'A', text: '' },
                { label: 'B', text: '' },
                { label: 'C', text: '' },
                { label: 'D', text: '' }
            ]
        });

        loadQuestionIntoForm(newIndex);
    }

    function bulkGenerateSlots(targetTotal) {
        saveCurrentQuestionToMemory(currentQuestionIndex);

        targetTotal = parseInt(targetTotal) || 40;
        if (targetTotal > 100) targetTotal = 100;

        const currentCount = questionsList.length;
        if (currentCount >= targetTotal) {
            alert(`Saat ini sudah ada ${currentCount} butir soal disiapkan.`);
            return;
        }

        const scorePerItem = Math.round((100 / targetTotal) * 100) / 100;
        const diff = document.querySelector('input[name="difficulty"]:checked')?.value || 'medium';
        const topic = document.getElementById('topicInput').value || '';

        for (let i = currentCount; i < targetTotal; i++) {
            questionsList.push({
                type: 'multiple_choice',
                question_text: '',
                question_image_base64: null,
                image_name: '',
                difficulty: diff,
                score: scorePerItem,
                topic: topic,
                explanation: '',
                correct_option: 0,
                options: [
                    { label: 'A', text: '' },
                    { label: 'B', text: '' },
                    { label: 'C', text: '' },
                    { label: 'D', text: '' }
                ]
            });
        }

        // Apply score to all
        questionsList.forEach(q => q.score = scorePerItem);

        loadQuestionIntoForm(currentQuestionIndex);
        alert(`Berhasil menyiapkan ${targetTotal} slot soal sekaligus! Bobot masing-masing otomatis diset ${scorePerItem} poin.`);
    }

    function duplicateCurrentQuestion() {
        saveCurrentQuestionToMemory(currentQuestionIndex);

        const copy = JSON.parse(JSON.stringify(questionsList[currentQuestionIndex]));
        copy.question_text = '[Salinan] ' + (copy.question_text || '');
        const targetIdx = currentQuestionIndex + 1;
        questionsList.splice(targetIdx, 0, copy);

        loadQuestionIntoForm(targetIdx);
    }

    function removeCurrentQuestion() {
        if (questionsList.length <= 1) {
            alert('Minimal harus ada 1 butir soal.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin menghapus butir soal #${currentQuestionIndex + 1}?`)) {
            questionsList.splice(currentQuestionIndex, 1);
            const nextIdx = Math.max(0, currentQuestionIndex - 1);
            loadQuestionIntoForm(nextIdx);
        }
    }

    /**
     * FORM SUBMISSION (Single or Bulk)
     */
    function handleFormSubmission() {
        saveCurrentQuestionToMemory(currentQuestionIndex);

        const subId = document.getElementById('subjectSelect').value;
        if (!subId) {
            alert('Silakan pilih Mata Pelajaran terlebih dahulu.');
            document.getElementById('subjectSelect').focus();
            return;
        }

        const form = document.getElementById('questionForm');
        const hiddenArea = document.getElementById('bulkPayloadInputs');
        hiddenArea.innerHTML = '';

        if (questionsList.length === 1) {
            // Mode Tunggal: Normal submit
            const qText = document.getElementById('questionTextInput').value.trim();
            if (!qText) {
                alert('Tuliskan pertanyaan soal terlebih dahulu.');
                document.getElementById('questionTextInput').focus();
                return;
            }
            form.submit();
        } else {
            // Mode Massal (40+ Soal): Inject into hidden inputs
            const validCount = questionsList.filter(q => q.question_text && q.question_text.trim() !== '').length;
            if (validCount === 0) {
                alert('Belum ada teks pertanyaan yang diisi. Minimal isi 1 butir soal.');
                return;
            }

            if (!confirm(`Simpan seluruh ${questionsList.length} butir soal (${validCount} butir terisi teks) ke Bank Soal sekarang?`)) {
                return;
            }

            questionsList.forEach((q, idx) => {
                // Main question fields
                appendHidden(hiddenArea, `questions[${idx}][question_text]`, q.question_text || '');
                appendHidden(hiddenArea, `questions[${idx}][type]`, q.type || 'multiple_choice');
                appendHidden(hiddenArea, `questions[${idx}][difficulty]`, q.difficulty || 'medium');
                appendHidden(hiddenArea, `questions[${idx}][score]`, q.score || 2.5);
                appendHidden(hiddenArea, `questions[${idx}][topic]`, q.topic || '');
                appendHidden(hiddenArea, `questions[${idx}][explanation]`, q.explanation || '');
                appendHidden(hiddenArea, `questions[${idx}][correct_option]`, q.correct_option ?? 0);

                if (q.question_image_base64) {
                    appendHidden(hiddenArea, `questions[${idx}][question_image_base64]`, q.question_image_base64);
                }

                // Options
                if (q.options && Array.isArray(q.options)) {
                    q.options.forEach((opt, optIdx) => {
                        appendHidden(hiddenArea, `questions[${idx}][options][${optIdx}][label]`, opt.label);
                        appendHidden(hiddenArea, `questions[${idx}][options][${optIdx}][text]`, opt.text || '');
                    });
                }
            });

            form.submit();
        }
    }

    function appendHidden(container, name, val) {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = name;
        inp.value = val;
        container.appendChild(inp);
    }

    /**
     * ORIGINAL TYPES & OPTIONS CONTROLS
     */
    function setType(type) {
        currentType = type;
        document.getElementById('questionType').value = type;

        // Update segmented buttons visual
        document.querySelectorAll('#typeSelectorGroup .type-btn').forEach(btn => {
            const btnType = btn.getAttribute('data-type');
            if(btnType === type) {
                btn.className = 'type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg bg-surface-container-lowest text-primary shadow-sm font-label-md text-label-md transition-all font-semibold';
            } else {
                btn.className = 'type-btn flex items-center justify-center gap-1.5 py-2 px-space-sm rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all font-label-md text-label-md';
            }
        });

        const mcSection = document.getElementById('multipleChoiceSection');
        const tfSection = document.getElementById('trueFalseSection');
        const essaySection = document.getElementById('essayInfoSection');
        const essayTitle = document.getElementById('essayInfoTitle');
        const essayDesc = document.getElementById('essayInfoDesc');

        if(type === 'multiple_choice') {
            mcSection.classList.remove('hidden');
            tfSection.classList.add('hidden');
            essaySection.classList.add('hidden');
        } else if(type === 'true_false') {
            mcSection.classList.add('hidden');
            tfSection.classList.remove('hidden');
            essaySection.classList.add('hidden');
        } else if(type === 'short_answer') {
            mcSection.classList.add('hidden');
            tfSection.classList.add('hidden');
            essaySection.classList.remove('hidden');
            essayTitle.textContent = 'Format Soal Isian Singkat';
            essayDesc.textContent = 'Siswa akan mengetikkan jawaban singkat berupa kata, angka, atau frasa. Masukkan kunci jawaban eksak pada kotak pembahasan.';
        } else if(type === 'essay') {
            mcSection.classList.add('hidden');
            tfSection.classList.add('hidden');
            essaySection.classList.remove('hidden');
            essayTitle.textContent = 'Format Soal Essay Terbuka';
            essayDesc.textContent = 'Siswa akan menguraikan jawaban dalam paragraf deskriptif. Sertakan rubrik kriteria penskoran pada bagian pembahasan untuk panduan koreksi.';
        }

        updateLivePreview();
    }

    function renderOptionsList() {
        const container = document.getElementById('optionsListContainer');
        container.innerHTML = '';

        defaultOptions.forEach((opt, idx) => {
            const isCorrect = (currentCorrectOption === idx);
            const row = document.createElement('div');
            row.className = `group flex items-center gap-space-md p-space-sm md:px-space-md rounded-xl transition-all ${isCorrect ? 'bg-secondary-fixed/30 shadow-sm border border-secondary/40' : 'bg-surface-container-low border border-slate-100'}`;

            row.innerHTML = `
                <button type="button" onclick="setCorrectOption(${idx})" class="w-8 h-8 rounded-full ${isCorrect ? 'bg-secondary text-on-secondary shadow-sm font-bold' : 'bg-surface-container-highest text-on-surface-variant font-semibold hover:bg-primary-fixed hover:text-primary'} flex items-center justify-center font-label-lg text-label-lg flex-shrink-0 transition-colors" title="${isCorrect ? 'Kunci Jawaban Terpilih' : 'Klik untuk jadikan kunci jawaban'}">
                    ${opt.label}
                </button>
                <div class="flex-1 relative">
                    <input type="text" name="options[${idx}][text]" value="${escapeQuotes(opt.text)}" oninput="updateOptionText(${idx}, this.value)" class="w-full h-11 pl-space-md ${isCorrect ? 'pr-28 font-semibold' : 'pr-4'} bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-primary/20 outline-none transition-all border border-slate-200/60" placeholder="Tuliskan teks opsi ${opt.label}..." required/>
                    <input type="hidden" name="options[${idx}][label]" value="${opt.label}">
                    ${isCorrect ? `
                        <div class="absolute right-2 top-2 px-2.5 py-1 rounded-md bg-secondary text-on-secondary font-label-sm text-label-sm inline-flex items-center gap-1 shadow-sm font-semibold">
                            <span class="material-symbols-outlined text-[14px]">check</span> Kunci Benar
                        </div>
                    ` : ''}
                </div>
                <div class="flex items-center gap-1 text-on-surface-variant">
                    ${defaultOptions.length > 2 ? `
                        <button type="button" onclick="removeOptionRow(${idx})" class="p-2 rounded hover:bg-surface-container-high transition-colors hover:text-error" title="Hapus Opsi">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    ` : ''}
                </div>
                <input type="radio" name="correct_option" value="${idx}" class="hidden" ${isCorrect ? 'checked' : ''}>
            `;
            container.appendChild(row);
        });

        // Hide add button if 6 options reached
        const addBtn = document.getElementById('btnAddOption');
        if(defaultOptions.length >= 6) {
            addBtn.classList.add('hidden');
        } else {
            addBtn.classList.remove('hidden');
            const nextLetter = String.fromCharCode(65 + defaultOptions.length);
            document.getElementById('btnAddOptionText').textContent = `+ Tambah Opsi Jawaban (${nextLetter})`;
        }
    }

    function setCorrectOption(idx) {
        currentCorrectOption = idx;
        renderOptionsList();
        updateLivePreview();
    }

    function updateOptionText(idx, val) {
        defaultOptions[idx].text = val;
        updateLivePreview();
    }

    function addOptionRow() {
        if(defaultOptions.length < 6) {
            const nextLetter = String.fromCharCode(65 + defaultOptions.length);
            defaultOptions.push({ label: nextLetter, text: '' });
            renderOptionsList();
            updateLivePreview();
        }
    }

    function removeOptionRow(idx) {
        if(defaultOptions.length > 2) {
            defaultOptions.splice(idx, 1);
            defaultOptions.forEach((o, i) => o.label = String.fromCharCode(65 + i));
            if(currentCorrectOption >= defaultOptions.length) {
                currentCorrectOption = 0;
            }
            renderOptionsList();
            updateLivePreview();
        }
    }

    function setTrueFalseAnswer(val) {
        currentCorrectOption = val;
        updateLivePreview();
    }

    /**
     * IMAGE UPLOAD & PREVIEW (Original Dropzone + Stored in memory)
     */
    function handleImageUpload(e) {
        const file = e.target.files[0];
        if(!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            uploadedImageDataUrl = evt.target.result;
            questionsList[currentQuestionIndex].question_image_base64 = uploadedImageDataUrl;
            questionsList[currentQuestionIndex].image_name = file.name;

            document.getElementById('previewImageTag').src = uploadedImageDataUrl;
            document.getElementById('previewImageNameBadge').textContent = file.name;
            document.getElementById('previewImageTitle').textContent = file.name;
            document.getElementById('previewImageInfo').textContent = `Ukuran: ${Math.round(file.size / 1024)} KB • Format: ${file.type}`;

            document.getElementById('imageUploadDropzone').classList.add('hidden');
            document.getElementById('imagePreviewCard').classList.remove('hidden');

            updateLivePreview();
        };
        reader.readAsDataURL(file);
    }

    function removeImageUpload(updateState = true) {
        document.getElementById('questionImageInput').value = '';
        uploadedImageDataUrl = null;
        if (updateState && questionsList[currentQuestionIndex]) {
            questionsList[currentQuestionIndex].question_image_base64 = null;
            questionsList[currentQuestionIndex].image_name = '';
        }
        document.getElementById('previewImageTag').src = '';
        document.getElementById('imageUploadDropzone').classList.remove('hidden');
        document.getElementById('imagePreviewCard').classList.add('hidden');
        updateLivePreview();
    }

    function adjustScore(delta) {
        const input = document.getElementById('scoreInput');
        let val = parseFloat(input.value) || 0;
        val = Math.max(0, Math.min(100, val + delta));
        input.value = val;
        updateLivePreview();
    }

    function setDifficulty(diff) {
        const cards = document.querySelectorAll('.diff-card');
        cards.forEach(card => {
            const input = card.querySelector('input');
            if(input.value === diff) {
                card.className = 'diff-card flex flex-col items-center justify-center py-2.5 px-2 rounded-lg bg-tertiary-fixed text-on-tertiary-fixed shadow-sm cursor-pointer transition-all text-center ring-2 ring-tertiary-container/30 font-bold';
                input.checked = true;
            } else {
                card.className = 'diff-card flex flex-col items-center justify-center py-2.5 px-2 rounded-lg bg-surface-container-low hover:bg-surface-container-high cursor-pointer transition-all text-center border border-slate-100 font-semibold';
            }
        });

        const bloomLabel = document.getElementById('bloomLabel');
        const bloomDesc = document.getElementById('bloomDesc');
        if(diff === 'easy') {
            bloomLabel.textContent = 'C1/C2 - Mengingat & Memahami';
            bloomDesc.textContent = 'Mengenal definisi, formula, dan konsep dasar';
        } else if(diff === 'medium') {
            bloomLabel.textContent = 'C3/C4 - Mengaplikasikan & Menganalisis';
            bloomDesc.textContent = 'Penerapan konsep & penyelesaian model masalah';
        } else {
            bloomLabel.textContent = 'C5/C6 - Mengevaluasi & Mengkreasi (HOTS)';
            bloomDesc.textContent = 'Penalaran kritis, sintesis data kontekstual';
        }
    }

    function updateLivePreview() {
        const text = document.getElementById('questionTextInput').value.trim();
        const simText = document.getElementById('simQuestionText');
        const simImageContainer = document.getElementById('simImageContainer');
        const simImageTag = document.getElementById('simImageTag');
        const simOptions = document.getElementById('simOptionsList');

        // Text & Counters
        if(text) {
            simText.textContent = text;
            const words = text.split(/\s+/).filter(w => w.length > 0).length;
            document.getElementById('charWordCounter').textContent = `${text.length} Karakter • ${words} Kata`;
        } else {
            simText.textContent = 'Jika 2x + 5 = 15, maka berapakah nilai x yang memenuhi persamaan linier tersebut?';
            document.getElementById('charWordCounter').textContent = '0 Karakter • 0 Kata';
        }

        // Image
        if(uploadedImageDataUrl) {
            simImageTag.src = uploadedImageDataUrl;
            simImageContainer.classList.remove('hidden');
        } else {
            simImageContainer.classList.add('hidden');
        }

        // Options
        simOptions.innerHTML = '';

        if(currentType === 'multiple_choice') {
            defaultOptions.forEach((opt, idx) => {
                const isCorrect = (currentCorrectOption === idx);
                const optDiv = document.createElement('div');
                optDiv.className = `flex items-center gap-2 p-2 rounded-lg shadow-sm ${isCorrect ? 'bg-primary-fixed text-on-primary-fixed font-semibold' : 'bg-surface-container-lowest text-on-surface'}`;
                optDiv.innerHTML = `
                    <span class="w-6 h-6 rounded-full ${isCorrect ? 'bg-primary-container text-on-primary' : 'bg-surface-container-high text-on-surface-variant'} flex items-center justify-center font-label-sm text-label-sm font-bold">${opt.label}</span>
                    <span class="font-body-sm text-body-sm truncate">${opt.text || ('Opsi ' + opt.label)}</span>
                    ${isCorrect ? '<span class="material-symbols-outlined text-[16px] ml-auto text-primary">check_circle</span>' : ''}
                `;
                simOptions.appendChild(optDiv);
            });
        } else if(currentType === 'true_false') {
            const isBenar = (currentCorrectOption === 0);
            simOptions.innerHTML = `
                <div class="flex items-center gap-2 p-2 rounded-lg shadow-sm ${isBenar ? 'bg-primary-fixed text-on-primary-fixed font-semibold' : 'bg-surface-container-lowest text-on-surface'}">
                    <span class="w-6 h-6 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm font-bold">1</span>
                    <span class="font-body-sm text-body-sm">BENAR</span>
                    ${isBenar ? '<span class="material-symbols-outlined text-[16px] ml-auto text-primary">check_circle</span>' : ''}
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg shadow-sm ${!isBenar ? 'bg-primary-fixed text-on-primary-fixed font-semibold' : 'bg-surface-container-lowest text-on-surface'}">
                    <span class="w-6 h-6 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm font-bold">2</span>
                    <span class="font-body-sm text-body-sm">SALAH</span>
                    ${!isBenar ? '<span class="material-symbols-outlined text-[16px] ml-auto text-primary">check_circle</span>' : ''}
                </div>
            `;
        } else {
            simOptions.innerHTML = `
                <div class="p-3 bg-surface-container-lowest rounded-lg border border-dashed border-slate-300 text-center">
                    <span class="text-xs text-on-surface-variant italic">[Area Input Isian Siswa]</span>
                </div>
            `;
        }
    }

    // Quick formatting helper functions
    function wrapText(prefix, suffix) {
        const textarea = document.getElementById('questionTextInput');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selected = text.substring(start, end);
        textarea.value = text.substring(0, start) + prefix + selected + suffix + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + prefix.length, end + prefix.length);
        updateLivePreview();
    }

    function insertFormula() {
        wrapText('\\(', ' = ...\\)');
    }

    function insertList() {
        wrapText('\n1. ', '\n2. ');
    }

    function insertQuote() {
        wrapText('\n> "', '"\n');
    }

    // Modal Preview functions
    function openPreviewModal() {
        const qText = document.getElementById('questionTextInput').value.trim() || 'Jika 2x + 5 = 15, maka berapakah nilai x yang memenuhi persamaan linier tersebut?';
        const subjSelect = document.getElementById('subjectSelect');
        const subjName = subjSelect.selectedIndex > 0 ? subjSelect.options[subjSelect.selectedIndex].text : 'Matematika';
        const scoreVal = document.getElementById('scoreInput').value || '2.5';

        document.getElementById('modalPreviewQuestion').textContent = qText;
        document.getElementById('modalPreviewSubject').textContent = subjName;
        document.getElementById('modalPreviewScore').textContent = `Bobot: ${scoreVal} Poin`;

        const imgContainer = document.getElementById('modalPreviewImageContainer');
        const imgTag = document.getElementById('modalPreviewImage');
        if(uploadedImageDataUrl) {
            imgTag.src = uploadedImageDataUrl;
            imgContainer.classList.remove('hidden');
        } else {
            imgContainer.classList.add('hidden');
        }

        const modalOpts = document.getElementById('modalPreviewOptions');
        modalOpts.innerHTML = '';

        if(currentType === 'multiple_choice') {
            defaultOptions.forEach((o, i) => {
                const optDiv = document.createElement('div');
                optDiv.className = 'flex items-center gap-3 p-3 rounded-xl bg-surface-container-low border border-slate-200';
                optDiv.innerHTML = `
                    <span class="w-7 h-7 rounded-full bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center justify-center font-bold">${o.label}</span>
                    <span class="font-body-md text-body-md text-on-surface">${o.text || ('Opsi ' + o.label)}</span>
                `;
                modalOpts.appendChild(optDiv);
            });
        }

        document.getElementById('studentPreviewModal').classList.remove('hidden');
    }

    function closePreviewModal() {
        document.getElementById('studentPreviewModal').classList.add('hidden');
    }

    function escapeQuotes(str) {
        return (str || '').replace(/"/g, '&quot;');
    }

    /**
     * SMART PASTE DARI WORD / TEKS
     */
    function openSmartPasteModal() {
        document.getElementById('smartPasteModal').classList.remove('hidden');
    }

    function closeSmartPasteModal() {
        document.getElementById('smartPasteModal').classList.add('hidden');
    }

    function fillSmartPasteDemo() {
        document.getElementById('smartPasteInput').value = 
`1. Di antara besaran berikut, manakah yang merupakan besaran turunan?
A. Massa
B. Panjang
C. Kecepatan
D. Waktu
E. Kuat Arus
Kunci: C

2. Satuan internasional (SI) untuk suhu adalah...
A. Celcius
B. Fahrenheit
C. Reamur
D. Kelvin
E. Rankine
Kunci: D

3. Alat ukur yang memiliki ketelitian hingga 0.01 mm adalah...
A. Penggaris
B. Jangka Sorong
C. Mikrometer Sekrup
D. Neraca Ohaus
E. Stopwatch
Kunci: C

4. Dimensi dari besaran gaya (F) adalah...
A. [M][L][T]^-1
B. [M][L][T]^-2
C. [M][L]^-1[T]^-2
D. [M][L]^2[T]^-2
E. [M][T]^-2
Kunci: B

5. Perubahan wujud dari gas langsung menjadi padat disebut...
A. Mengembun
B. Menyublim
C. Mengkristal
D. Membeku
E. Mencair
Kunci: C`;
    }

    function executeSmartPaste() {
        const raw = document.getElementById('smartPasteInput').value.trim();
        if (!raw) {
            alert('Teks soal masih kosong.');
            return;
        }

        const lines = raw.split(/\r?\n/);
        const parsed = [];
        let current = null;

        lines.forEach(line => {
            const trimmed = line.trim();
            if (!trimmed) return;

            const qMatch = trimmed.match(/^(\d+)[\.\)]\s*(.+)$/);
            if (qMatch) {
                if (current) parsed.push(current);
                current = {
                    type: 'multiple_choice',
                    question_text: qMatch[2],
                    question_image_base64: null,
                    image_name: '',
                    difficulty: 'medium',
                    score: parseFloat(document.getElementById('scoreInput').value) || 2.5,
                    topic: document.getElementById('topicInput').value || '',
                    explanation: '',
                    correct_option: 0,
                    options: [
                        { label: 'A', text: '' },
                        { label: 'B', text: '' },
                        { label: 'C', text: '' },
                        { label: 'D', text: '' },
                        { label: 'E', text: '' }
                    ]
                };
                return;
            }

            const optMatch = trimmed.match(/^([A-Ea-e])[\.\)]\s*(.+)$/);
            if (optMatch && current) {
                const letter = optMatch[1].toUpperCase();
                const optIdx = letter.charCodeAt(0) - 65;
                if (optIdx >= 0 && optIdx < 5) {
                    current.options[optIdx] = { label: letter, text: optMatch[2] };
                }
                return;
            }

            const keyMatch = trimmed.match(/^(?:Kunci|Jawaban|Kunci Jawaban|Key)[\s\:]+([A-Ea-e])/i);
            if (keyMatch && current) {
                current.correct_option = keyMatch[1].toUpperCase().charCodeAt(0) - 65;
                return;
            }

            if (current && current.options.filter(o => o.text !== '').length === 0) {
                current.question_text += ' ' + trimmed;
            }
        });

        if (current) parsed.push(current);

        if (parsed.length === 0) {
            alert('Format tidak dikenali. Pastikan soal diawali angka seperti "1. Pertanyaan..."');
            return;
        }

        questionsList = parsed;
        loadQuestionIntoForm(0);
        closeSmartPasteModal();
        alert(`Berhasil memuat ${parsed.length} butir soal ke lembar kerja! Gunakan bilah tab di atas untuk berpindah soal.`);
    }
</script>
@endpush
@endsection
