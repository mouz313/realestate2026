@extends('layouts.admin')

@section('title', 'Edit Staff <span class="urdu">(عملہ میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('team.index', ['type' => 'staff']) }}" class="text-decoration-none">Team</a></li>
        <li class="breadcrumb-item active">Edit Staff</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-user-cog me-1"></i> Edit Staff Member <span class="urdu">(عملہ میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('team.staff.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="urdu">(نیا پاس ورڈ)</span> <span class="text-secondary fw-normal">(leave blank to keep)</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="urdu">(پاس ورڈ کی تصدیق)</span></label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Staff</button>
            <a href="{{ route('team.index', ['type' => 'staff']) }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </div>
    </form>
</div>
@endsection
