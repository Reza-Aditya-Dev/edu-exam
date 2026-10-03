@extends('layouts.teacher')

@section('title', 'Profil Guru — EduExam')
@section('page_title', 'Profil Pengguna')

@section('teacher-content')
@php
    $user = auth()->user();
@endphp

<div class="max-w-xl mx-auto">
    <div class="card overflow-hidden">
        <!-- Cover Header -->
        <div class="h-32 bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-600 relative"></div>
        
        <div class="card-body text-center -mt-16 pb-8 px-6">
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-28 h-28 rounded-full border-4 border-white shadow-md mx-auto object-cover bg-white">
            
            <h2 class="font-headline font-bold text-slate-900 text-xl mt-3">{{ $user->name }}</h2>
            <div class="font-mono text-slate-500 text-xs mt-0.5">NIP. {{ $user->nip }}</div>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs mt-3 border border-emerald-200">
                <span class="material-symbols-outlined" style="font-size: 14px;">person_apron</span>
                Guru Pengajar
            </div>
            
            <div class="mt-8 text-left max-w-md mx-auto space-y-3">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                    <span class="text-slate-500 font-medium">Username</span>
                    <span class="font-semibold text-slate-900">{{ $user->username }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                    <span class="text-slate-500 font-medium">Alamat Email</span>
                    <span class="font-semibold text-slate-900">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                    <span class="text-slate-500 font-medium">Jenis Kelamin</span>
                    <span class="font-semibold text-slate-900">{{ $user->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                    <span class="text-slate-500 font-medium">Status Akun</span>
                    <span class="badge badge-success text-[11px] font-semibold">Aktif</span>
                </div>
                
                <div class="pt-3">
                    <div class="text-slate-500 font-medium text-xs mb-2">Mata Pelajaran yang Diampu:</div>
                    <div class="flex gap-1.5 flex-wrap">
                        @forelse($user->subjects as $subject)
                            <span class="badge badge-gray text-xs">{{ $subject->name }}</span>
                        @empty
                            <span class="text-xs text-slate-400 italic">Belum ada mata pelajaran terhubung</span>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary text-xs font-semibold px-6 py-2.5 text-rose-600 hover:bg-rose-50 flex items-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size: 16px;">logout</span>
                        Keluar dari Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
