@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="mb-4">
    <h2>Sign In</h2>
    <div class="auth-subtitle">Welcome back! Please enter your credentials.</div>
</div>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com" autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
    </div>
    <button type="submit" class="btn btn-amber btn-lg w-100" style="border-radius:10px;">
        <i class="ti ti-login me-1"></i> Sign In
    </button>
</form>

<div class="auth-bottom-text">
    Don't have an account? <a href="{{ route('register') }}" class="auth-link fw-semibold">Create one</a>
</div>
@endsection
