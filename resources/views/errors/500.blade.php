@extends('public.layouts.app')
@section('title', 'Server Error')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">500</h1>
    <p class="lead">Something went wrong on our end. Please try again later.</p>
    <a href="{{ route('home') }}" class="btn btn-accent btn-lg rounded-pill px-4">Go Home</a>
</div>
@endsection
