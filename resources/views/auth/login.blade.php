@extends('layouts.auth')

@section('title', 'Login <span class="urdu">(لاگ ان)</span>')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Sign In <span class="urdu">(سائن ان)</span></h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password <span class="urdu">(پاس ورڈ)</span></label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="mt-1 text-end">
                    <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot password? <span class="urdu">(پاس ورڈ بھول گئے؟)</span></a>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me <span class="urdu">(مجھے یاد رکھیں)</span></label>
                </div>
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Sign In <span class="urdu">(سائن ان)</span></button>
        </form>
        <p class="text-center text-secondary mt-3 mb-0">
            Don't have an account? <span class="urdu">(اکاؤنٹ نہیں ہے؟)</span> <a href="{{ route('register') }}">Register <span class="urdu">(رجسٹر)</span></a>
        </p>
    </div>
</div>
@endsection