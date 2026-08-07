@extends('layouts.admin')

@section('title', 'Edit Role')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none">Roles</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3><i class="ti ti-shield-edit me-1"></i> Edit Role</h3>
</div>

<form action="{{ route('roles.update', $role) }}" method="POST">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-body">
            @if($role->is_system)
            <div class="alert alert-info mb-3">
                <i class="ti ti-info-circle me-1"></i> This is a system role. You can manage permissions but cannot change system settings.
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control @error('name') is-invalid @enderror" required {{ $role->is_system ? 'readonly' : '' }}>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }} id="is_active" class="form-check-input" {{ $role->is_system ? 'disabled' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('roles.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection
