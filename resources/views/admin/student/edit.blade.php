@extends('layouts.admin')

@section('title', 'Edit Data Siswa — EduExam')
@section('page_title', 'Edit Profil Siswa')

@section('admin-content')

<div class="card" style="max-width: 850px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05);">
    <div class="card-header" style="background: var(--white); border-bottom: 1px solid var(--gray-200); padding: 24px 32px;">
        <div class="flex items-center gap-4">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--primary-light); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800;">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="card-title" style="font-size: 1.25rem; font-weight: 800; color: var(--gray-900);">Edit Data: {{ $student->name }}</h3>
                <div class="text-sm text-muted mt-1">
                    Pastikan NIS dan Alamat Email tidak sama dengan siswa lain.
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body" style="padding: 32px;">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 16px;">
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="nis">NIS (Nomor Induk Siswa) <span class="text-danger">*</span></label>
                    <input type="text" name="nis" id="nis" class="form-control" style="padding: 12px 16px;" value="{{ old('nis', $student->nis) }}" required>
                    @error('nis') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="username">Username Sistem</label>
                    <input type="text" name="username" id="username" class="form-control" style="padding: 12px 16px; background: var(--gray-50);" value="{{ old('username', $student->username) }}">
                    @error('username') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group mb-6">
                <label class="form-label font-bold text-gray-700" for="name">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" style="padding: 12px 16px;" value="{{ old('name', $student->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 16px;">
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="email">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" style="padding: 12px 16px;" value="{{ old('email', $student->email) }}" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-control" style="padding: 12px 16px; height: auto;">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 16px;">
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="phone">No. HP / WhatsApp</label>
                    <input type="text" name="phone" id="phone" class="form-control" style="padding: 12px 16px;" value="{{ old('phone', $student->phone) }}" placeholder="Contoh: 081234567890">
                    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label font-bold text-gray-700" for="is_active">Status Akun</label>
                    <select name="is_active" id="is_active" class="form-control" style="padding: 12px 16px; height: auto;">
                        <option value="1" {{ old('is_active', $student->is_active) == 1 ? 'selected' : '' }}>Aktif (Dapat Login)</option>
                        <option value="0" {{ old('is_active', $student->is_active) == 0 ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
                    </select>
                    @error('is_active') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group mb-6">
                <label class="form-label font-bold text-gray-700" for="address">Alamat Lengkap</label>
                <textarea name="address" id="address" class="form-control" rows="3" style="padding: 12px 16px;" placeholder="Alamat tempat tinggal saat ini...">{{ old('address', $student->address) }}</textarea>
                @error('address') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <div style="background: var(--gray-50); border: 1px dashed var(--gray-300); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 24px;">
                <div class="font-bold text-gray-700 mb-2">🏫 Info Kelas Terdaftar:</div>
                <div class="flex flex-wrap gap-2">
                    @forelse($student->classrooms as $classroom)
                        <span class="badge badge-primary" style="padding: 6px 12px;">{{ $classroom->name }} ({{ $classroom->academicYear->name ?? 'Tahun Ajaran Tidak Diketahui' }})</span>
                    @empty
                        <span class="text-sm text-gray-500 font-medium">Siswa ini belum terdaftar di kelas manapun. Anda bisa mendaftarkannya melalui halaman Edit Kelas.</span>
                    @endforelse
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--gray-200); margin: 32px 0 24px 0;">

            <div class="flex justify-between items-center">
                <!-- Using generic URL if standard route name is unavailable -->
                <a href="{{ url('admin/siswa') }}" class="btn btn-secondary" style="height: 44px; padding: 0 24px;">
                    <span>⬅️</span> Kembali
                </a>
                <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 32px; font-size: 1rem;">
                    <span>💾</span> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('admin-styles')
<style>
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@endsection
