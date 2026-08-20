@extends('layouts.auth')

@section('title', 'Login')

@push('styles')
<style>
    .login-wrap { width: 100%; max-width: 380px; }
    .login-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary);
        letter-spacing: 0.2px;
        margin-bottom: 1.4rem;
    }
    .login-brand .login-brand-mark {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: var(--primary); color: #fff; font-size: 1.2rem;
        box-shadow: 0 6px 16px rgba(79,70,229,.35);
    }
    .login-head h2 { font-size: 1.55rem; margin-bottom: 0.25rem; }
    .login-head .auth-subtitle { color: var(--text-muted); margin-bottom: 1.6rem; }
    .login-field { margin-bottom: 1rem; }
    .login-field .form-control { padding: 0.65rem 0.9rem; }
    .login-field .input-icon {
        position: relative;
    }
    .login-field .input-icon > i {
        position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: 1rem;
    }
    .login-field .input-icon .form-control { padding-left: 2.4rem; }
    .login-remember { font-size: 0.875rem; color: var(--gray-600); }
    .login-submit { margin-top: 0.5rem; width: 100%; padding: 0.7rem 1rem; font-size: 0.95rem; }
    .login-divider {
        display: flex; align-items: center; gap: 0.75rem;
        color: var(--text-muted); font-size: 0.78rem; margin: 1.4rem 0;
    }
    .login-divider::before, .login-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }
    .login-bottom { text-align: center; font-size: 0.875rem; color: var(--text-muted); }
    @media (max-width: 991.98px) {
        .login-wrap { margin: 0 auto; }
    }
</style>
@endpush

@section('content')
@php $brand = \App\Models\Setting::whereIn('key', ['brand_logo', 'business_name'])->pluck('value', 'key'); @endphp
<div class="login-wrap mx-auto">
    <div class="login-brand">
        @if(($brand['brand_logo'] ?? null))
            <img src="{{ Storage::url($brand['brand_logo']) }}" alt="{{ $brand['business_name'] ?? config('app.name') }}" style="max-height:38px;">
        @else
            <span class="login-brand-mark"><i class="ti ti-building-skyscraper"></i></span>
        @endif
        {{ $brand['business_name'] ?? config('app.name') }}
    </div>

    <div class="login-head">
        <h2>Sign In</h2>
        <div class="auth-subtitle">Welcome back! Please enter your credentials.</div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <i class="ti ti-alert-circle"></i>
            <div>
                @foreach($errors->all() as $message)
                    <div>{{ $message }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <div class="login-field">
            <label class="form-label">Email</label>
            <div class="input-icon">
                <i class="ti ti-mail"></i>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com" autofocus>
            </div>
            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="login-field">
            <label class="form-label">Password</label>
            <div class="input-icon">
                <i class="ti ti-lock"></i>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password">
            </div>
            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-check login-remember">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <span class="form-check-label" for="remember">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="auth-link fw-medium">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary login-submit">
            <i class="ti ti-login me-1"></i> Sign In
        </button>
    </form>
</div>
@endsection
