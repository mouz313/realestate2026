@extends('public.layouts.app')
@section('title', 'Forbidden')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1 fw-bold text-muted">403</h1>
    <p class="lead">You do not have permission to access this page.</p>
    <a href="{{ route('home') }}" class="btn btn-accent btn-lg rounded-pill px-4">Go Home</a>
</div>
@endsection
