@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}" class="text-decoration-none">Permissions</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3><i class="ti ti-lock-edit me-1"></i> Edit Permission</h3>
</div>

<form action="{{ route('permissions.update', $permission) }}" method="POST">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $permission->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Group <span class="text-danger">*</span></label>
                <input type="text" name="group" value="{{ old('group', $permission->group) }}" class="form-control @error('group') is-invalid @enderror" required>
                @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $permission->description) }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $permission->is_active) ? 'checked' : '' }} id="is_active" class="form-check-input">
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection
