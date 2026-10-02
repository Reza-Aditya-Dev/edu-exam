@extends('layouts.admin')

@section('title', 'Tambah Kelas — EduExam')
@section('page_title', 'Tambah Kelas Baru')

@section('admin-content')

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title flex items-center gap-2">
            <span>🏫</span> Form Tambah Kelas
        </h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.classrooms.store') }}" method="POST">
            @csrf
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Tahun Ajaran -->
                <div class="form-group">
                    <label class="form-label" for="academic_year_id">Tahun Ajaran <span class="text-danger">*</span></label>
                    <select name="academic_year_id" id="academic_year_id" class="form-control" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} - Smt {{ $year->semester }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tingkat / Grade -->
                <div class="form-group">
                    <label class="form-label" for="grade">Tingkat Kelas <span class="text-danger">*</span></label>
                    <select name="grade" id="grade" class="form-control" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                    @error('grade')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Nama Kelas -->
            <div class="form-group">
                <label class="form-label" for="name">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: X IPA 1, XI IPS 2, dll." value="{{ old('name') }}" required maxlength="50">
                <div class="text-xs text-muted mt-1">Maksimal 50 karakter.</div>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Wali Kelas -->
                <div class="form-group">
                    <label class="form-label" for="homeroom_teacher_id">Wali Kelas <span class="text-muted">(Opsional)</span></label>
                    <select name="homeroom_teacher_id" id="homeroom_teacher_id" class="form-control">
                        <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div class="form-group">
                    <label class="form-label" for="capacity">Kapasitas Maksimal Siswa <span class="text-muted">(Opsional)</span></label>
                    <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Contoh: 36" value="{{ old('capacity', 36) }}" min="1" max="60">
                    <div class="text-xs text-muted mt-1">Rentang: 1 - 60 siswa.</div>
                    @error('capacity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--gray-200); margin: 24px 0;">

            <div class="flex justify-between items-center">
                <a href="{{ route('admin.classrooms') }}" class="btn btn-secondary">
                    <span>⬅️</span> Kembali
                </a>
                <button type="submit" class="btn btn-primary" style="padding-left: 24px; padding-right: 24px;">
                    <span>💾</span> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
