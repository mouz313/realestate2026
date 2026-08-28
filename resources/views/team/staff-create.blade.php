@extends('layouts.admin')

@section('title', 'Add Staff')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('team.index', ['type' => 'staff']) }}" class="text-decoration-none">Team</a></li>
        <li class="breadcrumb-item active">Add Staff</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-user-plus me-1"></i> Add Staff Member <span class="urdu">(نیا عملہ)</span></h4>
    </div>
    <form action="{{ route('team.staff.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Password <span class="urdu">(پاس ورڈ)</span> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="urdu">(پاس ورڈ کی تصدیق)</span> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Staff</button>
            <a href="{{ route('team.index', ['type' => 'staff']) }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </div>
    </form>
</div>
@endsection
