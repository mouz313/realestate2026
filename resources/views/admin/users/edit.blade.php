@extends('layouts.admin')

@section('title', 'Roles & Permissions — {{ $user->name }}')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none">Users</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-user-cog me-1"></i> Roles & Permissions — {{ $user->name }}</h3>
        <div class="page-header-sub">{{ $user->email }}</div>
    </div>
</div>

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf @method('PUT')

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-2">Assigned Roles</h5>
            <p class="text-secondary small mb-3">A user can hold multiple roles. The highest-privilege role becomes their primary badge.</p>
            <div class="row">
                @foreach($roles as $role)
                <div class="col-md-4 col-lg-3 mb-2">
                    <label class="btn btn-outline-primary w-100 text-start role-toggle">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                            {{ $assignedRoleIds->contains($role->id) ? 'checked' : '' }}
                            class="form-check-input me-2">
                        {{ $role->name }}
                        @if($role->is_system)
                            <span class="badge bg-info ms-1">System</span>
                        @endif
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-2">Direct Permission Overrides</h5>
            <p class="text-secondary small mb-3">Grant or deny individual permissions for this user regardless of their roles.</p>

            @foreach($permissions as $group => $groupPermissions)
            <h6 class="mt-3 mb-2 text-uppercase text-xs text-secondary fw-bold">{{ $group }}</h6>
            <table class="table table-sm align-middle">
                @foreach($groupPermissions as $permission)
                <tr>
                    <td class="w-50">{{ $permission->name }}</td>
                    <td><code>{{ $permission->slug }}</code></td>
                    <td class="text-end text-nowrap">
                        <label class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" name="permissions_granted[]" value="{{ $permission->id }}"
                                {{ ($directPermissions[$permission->id] ?? null) === true ? 'checked' : '' }}>
                            <span class="form-check-label small">Grant</span>
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" name="permissions_denied[]" value="{{ $permission->id }}"
                                {{ ($directPermissions[$permission->id] ?? null) === false ? 'checked' : '' }}>
                            <span class="form-check-label small">Deny</span>
                        </label>
                    </td>
                </tr>
                @endforeach
            </table>
            @endforeach
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection