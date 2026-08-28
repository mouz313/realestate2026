@extends('layouts.admin')

@section('title', 'Add Permission')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}" class="text-decoration-none">Permissions</a></li>
        <li class="breadcrumb-item active">Add</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3><i class="ti ti-lock-plus me-1"></i> Add Permission</h3>
</div>

<form action="{{ route('permissions.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Group <span class="text-danger">*</span></label>
                <input type="text" name="group" value="{{ old('group') }}" class="form-control @error('group') is-invalid @enderror" required placeholder="e.g. Properties">
                @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Create Permission</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection
