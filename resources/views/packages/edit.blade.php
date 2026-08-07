@extends('layouts.admin')

@section('title', 'Edit Package')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('packages.index') }}" class="text-decoration-none">Packages</a></li>
        <li class="breadcrumb-item active">Edit {{ $package->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0"><i class="ti ti-ticket me-1"></i> Edit {{ $package->name }}</h4>
    </div>
    <form action="{{ route('packages.update', $package) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            @include('packages._form')
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Changes</button>
            <a href="{{ route('packages.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </div>
    </form>
</div>
@endsection
