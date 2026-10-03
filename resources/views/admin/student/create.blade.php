@extends('layouts.admin')

@section('title', 'Tambah Siswa — EduExam')
@section('page_title', 'Tambah Siswa Baru')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden">
        <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined" style="font-size: 22px;">person_add</span>
                </div>
                <div>
                    <h3 class="font-headline font-bold text-slate-900 text-lg">Form Tambah Siswa</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi data identitas siswa dan buat akun akses sistem ujian CBT.</p>
                </div>
            </div>
            <a href="{{ route('admin.students') }}" class="btn btn-secondary text-xs px-3 py-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Kembali
            </a>
        </div>
        
        <div class="card-body p-6 md:p-8">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="name">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control text-sm" value="{{ old('name') }}" placeholder="Contoh: Muhammad Rizky" required>
                    @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="nis">
                            NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nis" id="nis" class="form-control text-sm font-mono" value="{{ old('nis') }}" placeholder="Contoh: 100200300" required>
                        @error('nis') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="gender">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="gender" id="gender" class="form-control text-sm" required>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                        @error('gender') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="classroom_id">
                        Kelas Terdaftar <span class="text-rose-500">*</span>
                    </label>
                    <select name="classroom_id" id="classroom_id" class="form-control text-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->academicYear->name ?? 'Tahun Berjalan' }})
                            </option>
                        @endforeach
                    </select>
                    @error('classroom_id') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 mb-6">
                    <h4 class="font-headline font-bold text-indigo-950 text-sm mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-600" style="font-size: 18px;">lock</span>
                        Kredensial Login Siswa
                    </h4>
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="email">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control text-sm bg-white" value="{{ old('email') }}" placeholder="siswa@eduexam.com" required>
                        @error('email') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="username">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" id="username" class="form-control text-sm bg-white" value="{{ old('username') }}" placeholder="rizky123" required>
                            @error('username') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="password">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" class="form-control text-sm bg-white" placeholder="Minimal 6 karakter" required minlength="6">
                            @error('password') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.students') }}" class="btn btn-secondary text-xs px-4 py-2.5">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                        Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
