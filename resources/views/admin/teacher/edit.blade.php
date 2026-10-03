@extends('layouts.admin')

@section('title', 'Edit Guru — EduExam')
@section('page_title', 'Edit Data Guru')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden">
        <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="font-headline font-bold text-slate-900 text-lg">Edit Data: {{ $teacher->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui biodata pengajar atau atur ulang password.</p>
            </div>
            <a href="{{ route('admin.teachers') }}" class="btn btn-secondary text-xs px-3 py-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Kembali
            </a>
        </div>
        
        <div class="card-body p-6 md:p-8">
            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                        Nama Lengkap beserta Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required placeholder="Contoh: Budi Santoso, S.Pd., M.Kom.">
                    @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            NIP <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nip" class="form-control" value="{{ old('nip', $teacher->nip) }}" required placeholder="Nomor Induk Pegawai">
                        @error('nip') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="gender" class="form-control" required>
                            <option value="L" {{ old('gender', $teacher->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $teacher->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                @php
                    $selectedSubjectIds = old('subject_ids', $teacher->subjects->pluck('id')->toArray());
                @endphp
                <div class="mb-6">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                        Mata Pelajaran yang Diampu <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-48 overflow-y-auto">
                        @foreach($subjects as $s)
                            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white transition cursor-pointer text-xs font-medium text-slate-700">
                                <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}" {{ in_array($s->id, $selectedSubjectIds) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span>{{ $s->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Centang satu atau beberapa mata pelajaran yang diajarkan oleh guru.</p>
                    @error('subject_ids') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 mb-6">
                    <h4 class="font-headline font-bold text-indigo-950 text-sm mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-600" style="font-size: 18px;">key</span>
                        Kredensial & Akun
                    </h4>
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" class="form-control bg-white" value="{{ old('email', $teacher->email) }}" required>
                        @error('email') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Username <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" class="form-control bg-white" value="{{ old('username', $teacher->username) }}" required>
                            @error('username') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Password Baru <span class="text-slate-400 font-normal lowercase">(kosongkan bila tidak diubah)</span>
                            </label>
                            <input type="password" name="password" class="form-control bg-white" minlength="6" placeholder="Biarkan kosong jika tetap">
                            @error('password') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Status Akun
                        </label>
                        <select name="is_active" class="form-control bg-white">
                            <option value="1" {{ old('is_active', $teacher->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('is_active', $teacher->is_active) ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.teachers') }}" class="btn btn-secondary text-xs px-4 py-2.5">
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
