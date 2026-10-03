@extends('layouts.student')

@section('title', 'Profil Saya — EduExam')

@push('mobile-styles')
<style>
    .profile-header { text-align: center; padding: 32px 20px; background: white; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); margin-top: 16px; margin-bottom: 24px; position: relative; overflow: hidden; }
    .profile-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 80px; background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%); }
    
    .profile-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid white; background: white; position: relative; z-index: 1; margin: 0 auto 16px; display: block; object-fit: cover; box-shadow: var(--shadow-sm); }
    .profile-name { font-size: 1.25rem; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; }
    .profile-nis { font-size: 0.875rem; color: var(--gray-500); font-family: monospace; background: var(--gray-100); padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 12px; }
    .profile-class { display: inline-block; background: var(--primary-light); color: var(--primary-dark); padding: 4px 12px; border-radius: 100px; font-size: 0.8125rem; font-weight: 600; }

    .details-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 24px; }
    .details-card .card-header { padding: 16px; border-bottom: 1px solid var(--gray-100); font-weight: 700; background: var(--gray-50); display: flex; align-items: center; gap: 8px; }
    .detail-row { display: flex; flex-direction: column; gap: 4px; padding: 12px 16px; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--gray-500); font-size: 0.75rem; text-transform: uppercase; font-weight: 600; }
    .detail-value { font-weight: 600; color: var(--gray-900); }
</style>
@endpush

@section('student-content')
@php
    $user = auth()->user();
    $classroom = $user->currentClassroom();
@endphp

<div class="profile-header">
    <img src="{{ $user->avatar_url }}" alt="Avatar" class="profile-avatar">
    <div class="profile-name">{{ $user->name }}</div>
    <div class="profile-nis">{{ $user->nis }}</div>
    <div>
        <span class="profile-class"><i class="bi bi-mortarboard-fill me-1"></i> {{ $classroom ? $classroom->name : 'Belum Terdaftar di Kelas' }}</span>
    </div>
</div>

<div class="details-card">
    <div class="card-header">
        <i class="bi bi-person-lines-fill text-primary"></i> Informasi Akun
    </div>
    <div class="detail-row">
        <span class="detail-label">Username</span>
        <span class="detail-value">{{ $user->username }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $user->email }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Jenis Kelamin</span>
        <span class="detail-value">{{ $user->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Status Akun</span>
        <span class="detail-value" style="color: var(--success)"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
    </div>
</div>

<form method="POST" action="{{ route('logout') }}" style="margin-bottom: 24px;">
    @csrf
    <button type="submit" class="btn btn-danger btn-block btn-lg d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
        <i class="bi bi-box-arrow-right"></i> Keluar dari Aplikasi
    </button>
</form>

<div style="height: 16px;"></div>
@endsection
