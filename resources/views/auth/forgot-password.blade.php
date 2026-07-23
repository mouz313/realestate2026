@extends('layouts.auth')

@section('title', 'Forgot Password <span class="urdu">(پاس ورڈ بھول گئے)</span>')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Reset Password <span class="urdu">(پاس ورڈ ری سیٹ)</span></h2>
        <p class="text-secondary text-center mb-4">Enter your email and we'll send you a reset link. <span class="urdu">(اپنا ای میل درج کریں اور ہم آپ کو ری سیٹ لنک بھیجیں گے۔)</span></p>
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Send Reset Link <span class="urdu">(ری سیٹ لنک بھیجیں)</span></button>
        </form>
        <p class="text-center text-secondary mt-3 mb-0">
            <a href="{{ route('login') }}">Back to Login <span class="urdu">(لاگ ان پر واپس)</span></a>
        </p>
    </div>
</div>
@endsection