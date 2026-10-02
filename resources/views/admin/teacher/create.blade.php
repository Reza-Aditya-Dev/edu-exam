@extends('layouts.admin')

@section('title', 'Tambah Guru — EduExam')
@section('page_title', 'Tambah Guru Baru')

@section('admin-content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap beserta Gelar <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Pd., M.Kom.">
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="gender" class="form-control" required>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Mata Pelajaran yang Diampu <span class="text-danger">*</span></label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 12px; border: 1px solid var(--gray-200); border-radius: var(--radius-md); max-height: 200px; overflow-y: auto;">
                    @foreach($subjects as $s)
                        <label class="flex items-center gap-2" style="cursor: pointer;">
                            <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}" style="width: 16px; height: 16px; accent-color: var(--primary);">
                            <span class="text-sm font-medium">{{ $s->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-muted mt-1">Dapat memilih lebih dari satu mata pelajaran.</p>
            </div>
            
            <hr class="my-4" style="border-top: 1px solid var(--gray-200); margin: 24px 0;">
            <h4 class="font-bold mb-4">Kredensial Login</h4>
            
            <div class="form-group">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.teachers') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Guru</button>
            </div>
        </form>
    </div>
</div>
@endsection
