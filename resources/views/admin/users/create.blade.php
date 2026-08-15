@extends('layouts.admin')

@section('title', 'Add User')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none">Users</a></li>
        <li class="breadcrumb-item active">Add User</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-user-plus me-1"></i> Add User</h3>
        <div class="page-header-sub">Create a back-office login (e.g. staff) or link an account to an existing agent.</div>
    </div>
</div>

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-2">Account</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>
                        @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Link to Agent</label>
                        <select class="form-select @error('agent_id') is-invalid @enderror" name="agent_id">
                            <option value="">— None —</option>
                            @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-secondary">Optional. Only agents without a login are listed.</small>
                        @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-2">Assigned Roles <span class="text-danger">*</span></h5>
            <p class="text-secondary small mb-3">Select at least one role. The highest-privilege role becomes the primary badge.</p>
            <div class="row">
                @foreach($roles as $role)
                <div class="col-md-4 col-lg-3 mb-2">
                    <label class="btn btn-outline-primary w-100 text-start role-toggle">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                            class="form-check-input me-2">
                        {{ $role->name }}
                        @if($role->is_system)
                            <span class="badge bg-info ms-1">System</span>
                        @endif
                    </label>
                </div>
                @endforeach
            </div>
            @error('roles') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection