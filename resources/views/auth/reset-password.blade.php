@extends('layouts.auth')

@section('title', 'Reset Password <span class="urdu">(پاس ورڈ ری سیٹ)</span>')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Set New Password <span class="urdu">(نیا پاس ورڈ سیٹ کریں)</span></h2>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email', request('email')) }}" readonly>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">New Password <span class="urdu">(نیا پاس ورڈ)</span></label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="New password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password <span class="urdu">(پاس ورڈ کی تصدیق)</span></label>
                <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Confirm password">
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Reset Password <span class="urdu">(پاس ورڈ ری سیٹ)</span></button>
        </form>
    </div>
</div>
@endsection