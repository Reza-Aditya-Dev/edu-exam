@extends('layouts.admin')

@section('title', 'Tambah Siswa — EduExam')
@section('page_title', 'Tambah Siswa Baru')

@section('admin-content')
<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required>
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
                <label class="form-label">Kelas Saat Ini <span class="text-danger">*</span></label>
                <select name="classroom_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->academicYear->name }})</option>
                    @endforeach
                </select>
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
                <a href="{{ route('admin.students') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
            </div>
        </form>
    </div>
</div>
@endsection
