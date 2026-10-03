@extends('layouts.admin')

@section('title', 'Edit Data Siswa — EduExam')
@section('page_title', 'Edit Profil Siswa')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden">
        <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-extrabold text-sm border border-indigo-200">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-headline font-bold text-slate-900 text-lg">Edit Data: {{ $student->name }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi profil dan kredensial siswa.</p>
                </div>
            </div>
            <a href="{{ route('admin.students') }}" class="btn btn-secondary text-xs px-3 py-1.5 flex items-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Kembali
            </a>
        </div>
        
        <div class="card-body p-6 md:p-8">
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="nis">
                            NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nis" id="nis" class="form-control text-sm font-mono" value="{{ old('nis', $student->nis) }}" required>
                        @error('nis') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="username">
                            Username Sistem
                        </label>
                        <input type="text" name="username" id="username" class="form-control text-sm" value="{{ old('username', $student->username) }}">
                        @error('username') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="name">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control text-sm" value="{{ old('name', $student->name) }}" required>
                    @error('name') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="email">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control text-sm" value="{{ old('email', $student->email) }}" required>
                        @error('email') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="gender">
                            Jenis Kelamin
                        </label>
                        <select name="gender" id="gender" class="form-control text-sm">
                            <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                            <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                        @error('gender') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="phone">
                            No. HP / WhatsApp <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <input type="text" name="phone" id="phone" class="form-control text-sm" value="{{ old('phone', $student->phone) }}" placeholder="081234567890">
                        @error('phone') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="is_active">
                            Status Akun
                        </label>
                        <select name="is_active" id="is_active" class="form-control text-sm">
                            <option value="1" {{ old('is_active', $student->is_active) == 1 ? 'selected' : '' }}>Aktif (Dapat Mengikuti Ujian)</option>
                            <option value="0" {{ old('is_active', $student->is_active) == 0 ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
                        </select>
                        @error('is_active') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="address">
                        Alamat Domisili <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="address" id="address" class="form-control text-sm" rows="3" placeholder="Alamat lengkap...">{{ old('address', $student->address) }}</textarea>
                    @error('address') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl mb-6">
                    <div class="font-headline font-bold text-xs text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-indigo-600" style="font-size: 18px;">meeting_room</span>
                        <span>Kelas Terdaftar Saat Ini:</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($student->classrooms as $classroom)
                            <span class="badge badge-primary text-xs font-semibold">{{ $classroom->name }} ({{ $classroom->academicYear->name ?? 'Tahun Berjalan' }})</span>
                        @empty
                            <span class="text-xs text-slate-400 italic">Siswa ini belum terdaftar di kelas manapun.</span>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.students') }}" class="btn btn-secondary text-xs px-4 py-2.5">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
