@extends('layouts.auth')

@section('title', 'Register <span class="urdu">(رجسٹر)</span>')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Create Account <span class="urdu">(اکاؤنٹ بنائیں)</span></h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name <span class="urdu">(نام)</span></label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Your name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password <span class="urdu">(پاس ورڈ)</span></label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password <span class="urdu">(پاس ورڈ کی تصدیق)</span></label>
                <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Confirm password">
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Register <span class="urdu">(رجسٹر)</span></button>
        </form>
        <p class="text-center text-secondary mt-3 mb-0">
            Already have an account? <span class="urdu">(پہلے سے اکاؤنٹ ہے؟)</span> <a href="{{ route('login') }}">Sign In <span class="urdu">(سائن ان)</span></a>
        </p>
    </div>
</div>
@endsection