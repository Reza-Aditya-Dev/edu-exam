@extends('layouts.student')

@section('title', 'Profil Saya — EduExam')

@section('student-content')
@php
    $user = auth()->user();
    $classroom = $user->currentClassroom();
@endphp

<div class="flex flex-col w-full pb-8">
    <!-- Profile Hero Card -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container overflow-hidden relative mb-4">
        <div class="h-24 bg-gradient-to-r from-primary to-primary-container relative">
            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-md bg-white">
                    <span class="absolute bottom-1 right-1 w-4 h-4 bg-secondary rounded-full ring-2 ring-white"></span>
                </div>
            </div>
        </div>

        <div class="pt-12 pb-5 px-4 flex flex-col items-center text-center">
            <h2 class="font-headline-sm text-base md:text-lg font-bold text-on-surface">
                {{ $user->name }}
            </h2>
            <div class="flex items-center gap-1.5 mt-1">
                <span class="text-xs font-mono bg-surface-container px-2 py-0.5 rounded text-on-surface-variant">
                    NIS: {{ $user->nis ?? '-' }}
                </span>
                <span class="inline-flex items-center gap-1 bg-primary-fixed text-on-primary-fixed text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $classroom ? $classroom->name : 'Belum Terdaftar Kelas' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Account Details Card -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container p-4 mb-4">
        <h3 class="font-headline-sm text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
            <span>Informasi Biodata & Akun</span>
        </h3>
        <div class="flex flex-col divide-y divide-surface-container text-xs">
            <div class="py-2.5 flex justify-between items-center">
                <span class="text-on-surface-variant font-medium">Username</span>
                <span class="font-semibold text-on-surface">{{ $user->username }}</span>
            </div>
            <div class="py-2.5 flex justify-between items-center">
                <span class="text-on-surface-variant font-medium">Email</span>
                <span class="font-semibold text-on-surface">{{ $user->email }}</span>
            </div>
            <div class="py-2.5 flex justify-between items-center">
                <span class="text-on-surface-variant font-medium">Jenis Kelamin</span>
                <span class="font-semibold text-on-surface">{{ $user->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</span>
            </div>
            <div class="py-2.5 flex justify-between items-center">
                <span class="text-on-surface-variant font-medium">Tingkat / Jurusan</span>
                <span class="font-semibold text-on-surface">{{ $classroom ? 'Kelas ' . $classroom->grade . ' • ' . $classroom->major : '-' }}</span>
            </div>
            <div class="py-2.5 flex justify-between items-center">
                <span class="text-on-surface-variant font-medium">Status Akun</span>
                <span class="inline-flex items-center gap-1 text-secondary font-bold bg-secondary-container/60 px-2 py-0.5 rounded-full text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    Aktif
                </span>
            </div>
        </div>
    </div>

    <!-- Logout Button Form -->
    <form method="POST" action="{{ route('logout') }}" class="mt-1">
        @csrf
        <button type="submit" class="w-full h-12 bg-error/10 hover:bg-error/20 text-error border border-error/30 rounded-xl font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
            <span class="material-symbols-outlined text-[18px]">logout</span>
            <span>Keluar dari Aplikasi</span>
        </button>
    </form>
</div>
@endsection
