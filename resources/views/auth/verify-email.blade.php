@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="text-center mb-4">
    <div class="icon-box-circle mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
        <i class="ti ti-mail-check"></i>
    </div>
    <h2>Verify Your Email</h2>
    <div class="auth-subtitle">A verification link has been sent to your email. Click the link to activate your account.</div>
</div>

<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit" class="btn btn-amber btn-lg w-100" style="border-radius:10px;">
        <i class="ti ti-send me-1"></i> Resend Verification Email
    </button>
</form>

<div class="auth-bottom-text mt-3">
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="auth-link bg-transparent border-0 p-0">
            <i class="ti ti-logout me-1"></i> Logout
        </button>
    </form>
</div>
@endsection
