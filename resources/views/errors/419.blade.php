@extends('public.layouts.app')
@section('title', 'Session Expired')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">419</h1>
    <p class="lead">Your session has expired. Please log in again.</p>
    <a href="{{ route('login') }}" class="btn btn-accent btn-lg rounded-pill px-4">Login</a>
</div>
@endsection
