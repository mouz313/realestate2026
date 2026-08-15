@extends('portal.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-1">Reset Password <span class="urdu">(پاس ورڈ ری سیٹ)</span></h3>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('portal.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="urdu">(نیا پاس ورڈ)</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="urdu">(پاس ورڈ کی تصدیق)</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Reset Password <span class="urdu">(پاس ورڈ ری سیٹ)</span></button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
