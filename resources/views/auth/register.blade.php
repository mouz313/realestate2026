@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="mb-4">
    <h2>Create Account</h2>
    <div class="auth-subtitle">Join Skyline and start your real estate journey.</div>
</div>

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Your full name" autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Create a password">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password">
    </div>
    <button type="submit" class="btn btn-amber btn-lg w-100" style="border-radius:10px;">
        <i class="ti ti-user-plus me-1"></i> Create Account
    </button>
</form>

<div class="auth-bottom-text">
    Already have an account? <a href="{{ route('login') }}" class="auth-link fw-semibold">Sign In</a>
</div>
@endsection
