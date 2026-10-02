@extends('layouts.app')

@section('title', 'Masuk — EduExam')

@push('styles')
<style>
    body { background: linear-gradient(135deg, #eef2ff 0%, #f9fafb 50%, #f0fdf4 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }

    .login-wrapper { width: 100%; max-width: 420px; }

    .login-brand {
        text-align: center; margin-bottom: 32px;
    }
    .login-brand .logo-mark {
        width: 56px; height: 56px; background: var(--primary); border-radius: var(--radius-lg);
        display: inline-flex; align-items: center; justify-content: center;
        color: white; font-size: 1.5rem; font-weight: 800; margin-bottom: 12px;
        box-shadow: 0 8px 24px rgba(79,70,229,.3);
    }
    .login-brand h1 { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); }
    .login-brand .tagline { color: var(--gray-500); font-size: 0.875rem; margin-top: 2px; }

    .login-card {
        background: var(--white); border-radius: var(--radius-xl); padding: 32px;
        box-shadow: 0 20px 60px rgba(0,0,0,.1); border: 1px solid var(--gray-100);
    }
    .login-card h2 { font-size: 1.25rem; font-weight: 700; color: var(--gray-800); margin-bottom: 6px; }
    .login-card .subtitle { color: var(--gray-500); font-size: 0.875rem; margin-bottom: 28px; }

    .input-group { position: relative; }
    .input-group .form-control { padding-left: 44px; }
    .input-group .input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--gray-400); font-size: 1rem; pointer-events: none;
    }
    .input-group .input-toggle {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        cursor: pointer; color: var(--gray-400); background: none; border: none; padding: 4px;
    }
    .input-group .input-toggle:hover { color: var(--primary); }

    .login-extras {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; font-size: 0.875rem;
    }
    .login-extras label { display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--gray-600); }
    .login-extras input[type="checkbox"] { accent-color: var(--primary); width: 16px; height: 16px; }

    .login-btn {
        width: 100%; padding: 14px; font-size: 1rem; font-weight: 700;
        background: var(--primary); color: white; border: none; border-radius: var(--radius-md);
        cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .login-btn:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(79,70,229,.3); }
    .login-btn:active { transform: translateY(0); }

    .school-footer { text-align: center; margin-top: 24px; font-size: 0.8125rem; color: var(--gray-400); }
    .school-footer span { color: var(--gray-600); font-weight: 600; }

    .role-tabs {
        display: flex; gap: 4px; background: var(--gray-100); padding: 4px;
        border-radius: var(--radius-md); margin-bottom: 24px;
    }
    .role-tab {
        flex: 1; text-align: center; padding: 8px; border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 600; cursor: pointer;
        color: var(--gray-500); transition: all .15s; border: none; background: none;
    }
    .role-tab.active { background: var(--white); color: var(--primary); box-shadow: var(--shadow-sm); }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <!-- Brand -->
    <div class="login-brand">
        <div class="logo-mark">E</div>
        <h1>EduExam</h1>
        <p class="tagline">Platform Ujian Digital Sekolah</p>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <h2>Selamat Datang</h2>
        <p class="subtitle">Masuk untuk mengikuti ujian sekolah</p>

        <!-- Session alerts -->
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label class="form-label" for="login">Username / Email</label>
                <div class="input-group">
                    <span class="input-icon">👤</span>
                    <input type="text" name="login" id="login"
                           class="form-control @error('login') is-error @enderror"
                           value="{{ old('login') }}"
                           placeholder="Masukkan username atau email"
                           autocomplete="username" autofocus required>
                </div>
                @error('login')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-error @enderror"
                           placeholder="Masukkan password"
                           autocomplete="current-password" required>
                    <button type="button" class="input-toggle" onclick="togglePassword()" title="Tampilkan password">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="login-extras">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                <a href="#">Lupa password?</a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span id="btnText">Masuk</span>
                <span id="btnLoader" style="display:none">⏳ Memproses...</span>
            </button>
        </form>
    </div>

    <div class="school-footer">
        🏫 <span>SMA Nusantara</span> &nbsp;•&nbsp; EduExam v1.0
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈';
    } else {
        input.type = 'password';
        icon.textContent = '👁️';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnLoader').style.display = 'inline';
    document.getElementById('loginBtn').disabled = true;
});
</script>
@endpush
