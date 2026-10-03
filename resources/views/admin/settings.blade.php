@extends('layouts.admin')

@section('title', 'Pengaturan Sekolah — EduExam')
@section('page_title', 'Pengaturan Sistem & Sekolah')

@section('admin-content')
<div class="max-w-3xl mx-auto">
    <div class="card overflow-hidden">
        <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="font-headline font-bold text-slate-900 text-lg">Pengaturan Identitas & CBT</h3>
                <p class="text-xs text-slate-500 mt-0.5">Konfigurasi nama institusi, logo, dan preferensi ujian sekolah.</p>
            </div>
        </div>
        
        <div class="card-body p-6 md:p-8">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                @if(isset($settings) && count($settings) > 0)
                    @foreach($settings as $group => $items)
                        <div class="mb-8">
                            <h4 class="font-headline font-bold text-slate-800 text-sm uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                <span class="material-symbols-outlined text-indigo-600" style="font-size: 18px;">tune</span>
                                Bagian: {{ ucfirst($group) }}
                            </h4>
                            
                            <div class="space-y-4">
                                @foreach($items as $setting)
                                    <div>
                                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block" for="setting_{{ $setting->key }}">
                                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                        </label>
                                        <input type="text" name="{{ $setting->key }}" id="setting_{{ $setting->key }}" class="form-control text-sm" value="{{ old($setting->key, $setting->value) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="space-y-5">
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Nama Sekolah / Institusi
                            </label>
                            <input type="text" name="school_name" class="form-control text-sm" value="{{ old('school_name', 'SMA Negeri 1 EduExam') }}" required>
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Alamat Sekolah
                            </label>
                            <textarea name="school_address" class="form-control text-sm" rows="3">{{ old('school_address', 'Jl. Pendidikan No. 123, Jakarta') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                    No. Telepon / Hotline
                                </label>
                                <input type="text" name="school_phone" class="form-control text-sm" value="{{ old('school_phone', '021-5551234') }}">
                            </div>
                            <div>
                                <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                    Email Administrator
                                </label>
                                <input type="email" name="school_email" class="form-control text-sm" value="{{ old('school_email', 'admin@sekolah.sch.id') }}">
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="flex justify-end pt-6 border-t border-slate-100 mt-6">
                    <button type="submit" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
