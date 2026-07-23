@extends('public.layouts.app')
@section('title', 'Too Many Requests')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">429</h1>
    <p class="lead">You have made too many requests. Please wait a moment and try again.</p>
    <a href="{{ route('home') }}" class="btn btn-accent btn-lg rounded-pill px-4">Go Home</a>
</div>
@endsection
