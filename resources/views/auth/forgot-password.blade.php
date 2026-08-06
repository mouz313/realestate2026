@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="mb-4">
    <div class="icon-box-circle mb-3" style="width:52px;height:52px;font-size:1.3rem;">
        <i class="ti ti-lock"></i>
    </div>
    <h2>Forgot Password?</h2>
    <div class="auth-subtitle">No worries. Enter your email and we'll send you a reset link.</div>
</div>

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com" autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-amber btn-lg w-100" style="border-radius:10px;">
        <i class="ti ti-send me-1"></i> Send Reset Link
    </button>
</form>

<div class="auth-bottom-text">
    <a href="{{ route('login') }}" class="auth-link"><i class="ti ti-arrow-left me-1"></i> Back to Login</a>
</div>
@endsection
