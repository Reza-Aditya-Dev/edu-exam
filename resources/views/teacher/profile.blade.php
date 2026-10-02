@extends('layouts.teacher')

@section('title', 'Profil Guru — EduExam')
@section('page_title', 'Profil Pengguna')

@section('teacher-content')
@php
    $user = auth()->user();
@endphp

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%); height: 120px; border-radius: var(--radius-lg) var(--radius-lg) 0 0;"></div>
    
    <div class="card-body" style="text-align: center; margin-top: -60px; padding-bottom: 40px;">
        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; object-fit: cover; box-shadow: var(--shadow-md); margin-bottom: 16px; background: white;">
        
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 4px;">{{ $user->name }}</h2>
        <div style="color: var(--gray-500); font-family: monospace; font-size: 1rem; margin-bottom: 16px;">NIP. {{ $user->nip }}</div>
        
        <div style="display: inline-block; background: var(--primary-light); color: var(--primary-dark); padding: 6px 16px; border-radius: 100px; font-weight: 600; font-size: 0.875rem; margin-bottom: 32px;">
            Guru Pengajar
        </div>
        
        <div style="text-align: left; max-width: 400px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-100);">
                <span style="color: var(--gray-500); font-weight: 600;">Username</span>
                <span style="font-weight: 700; color: var(--gray-900);">{{ $user->username }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-100);">
                <span style="color: var(--gray-500); font-weight: 600;">Email</span>
                <span style="font-weight: 700; color: var(--gray-900);">{{ $user->email }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-100);">
                <span style="color: var(--gray-500); font-weight: 600;">Jenis Kelamin</span>
                <span style="font-weight: 700; color: var(--gray-900);">{{ $user->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-100);">
                <span style="color: var(--gray-500); font-weight: 600;">Status Akun</span>
                <span style="font-weight: 700; color: var(--success);">Aktif</span>
            </div>
            
            <div style="margin-top: 24px;">
                <div style="color: var(--gray-500); font-weight: 600; margin-bottom: 8px;">Mata Pelajaran yang Diampu:</div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @foreach($user->subjects as $subject)
                        <span class="badge badge-gray">{{ $subject->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
