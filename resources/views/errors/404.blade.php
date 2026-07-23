@extends('public.layouts.app')
@section('title', 'Page Not Found')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">404</h1>
    <p class="lead">The page you are looking for could not be found.</p>
    <a href="{{ route('home') }}" class="btn btn-accent btn-lg rounded-pill px-4">Go Home</a>
</div>
@endsection
