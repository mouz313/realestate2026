@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="text-center">
    <i class="ti ti-mail-check" style="font-size:4rem;color:var(--accent);"></i>
    <h4 class="fw-bold mt-3">Verify Your Email Address</h4>
    <p class="text-secondary small">A verification link has been sent to your email address. Click the link to activate your account.</p>

    <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-accent w-100 py-2 fw-semibold rounded-pill">
            <i class="ti ti-send me-1"></i> Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none small text-secondary">
            <i class="ti ti-logout me-1"></i> Logout
        </button>
    </form>
</div>
@endsection
