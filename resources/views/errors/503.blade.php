@extends('public.layouts.app')
@section('title', 'Service Unavailable')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">503</h1>
    <p class="lead">We are currently undergoing maintenance. Please check back shortly.</p>
    <a href="{{ route('home') }}" class="btn btn-accent btn-lg rounded-pill px-4">Go Home</a>
</div>
@endsection
