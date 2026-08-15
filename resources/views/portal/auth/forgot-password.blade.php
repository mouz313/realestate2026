@extends('portal.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-1">Forgot Password <span class="urdu">(پاس ورڈ بھول گئے)</span></h3>
                <p class="text-muted small">Enter your portal email to receive a reset link.</p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('portal.password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Send Reset Link <span class="urdu">(لنک بھیجیں)</span></button>
                </form>

                <div class="text-center mt-3 small">
                    <a href="{{ route('portal.login') }}">Back to login <span class="urdu">(لاگ ان پر واپس)</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
