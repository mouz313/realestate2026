@extends('layouts.admin')

@section('title', 'Add Package')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('packages.index') }}" class="text-decoration-none">Packages</a></li>
        <li class="breadcrumb-item active">Add Package</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0"><i class="ti ti-ticket me-1"></i> Add Package</h4>
    </div>
    <form action="{{ route('packages.store') }}" method="POST">
        <div class="card-body">
            @include('packages._form')
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Package</button>
            <a href="{{ route('packages.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </div>
    </form>
</div>
@endsection
