@extends('layouts.admin')

@section('title', 'Edit Kelas — EduExam')
@section('page_title', 'Edit Data Kelas')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden">
        <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="font-headline font-bold text-slate-900 text-lg">Edit Kelas: {{ $classroom->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi tingkat, nama, wali kelas, atau kapasitas.</p>
            </div>
            <a href="{{ route('admin.classrooms') }}" class="btn btn-secondary text-xs px-3 py-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Kembali
            </a>
        </div>
        
        <div class="card-body p-6 md:p-8">
            <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="academic_year_id">
                            Tahun Ajaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id" class="form-control" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $classroom->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} - Smt {{ $year->semester }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="grade">
                            Tingkat Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select name="grade" id="grade" class="form-control" required>
                            <option value="10" {{ old('grade', $classroom->grade) == '10' ? 'selected' : '' }}>Kelas 10 (Fase E)</option>
                            <option value="11" {{ old('grade', $classroom->grade) == '11' ? 'selected' : '' }}>Kelas 11 (Fase F)</option>
                            <option value="12" {{ old('grade', $classroom->grade) == '12' ? 'selected' : '' }}>Kelas 12 (Fase F)</option>
                        </select>
                        @error('grade') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="name">
                        Nama Kelas / Rombel <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $classroom->name) }}" required maxlength="50">
                    <p class="text-[11px] text-slate-400 mt-1">Nama rombongan belajar resmi sekolah (maks. 50 karakter).</p>
                    @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="homeroom_teacher_id">
                            Wali Kelas <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <select name="homeroom_teacher_id" id="homeroom_teacher_id" class="form-control">
                            <option value="">-- Belum Ditentukan --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $classroom->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('homeroom_teacher_id') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="capacity">
                            Kapasitas Siswa <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity', $classroom->capacity) }}" min="1" max="60">
                        @error('capacity') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.classrooms') }}" class="btn btn-secondary text-xs px-4 py-2.5">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
