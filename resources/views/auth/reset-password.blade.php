@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="mb-4">
    <div class="icon-box-circle mb-3" style="width:52px;height:52px;font-size:1.3rem;">
        <i class="ti ti-key"></i>
    </div>
    <h2>Set New Password</h2>
    <div class="auth-subtitle">Choose a strong password for your account.</div>
</div>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', request('email')) }}" readonly>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter new password" autofocus>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
    </div>
    <button type="submit" class="btn btn-amber btn-lg w-100" style="border-radius:10px;">
        <i class="ti ti-check me-1"></i> Reset Password
    </button>
</form>
@endsection
