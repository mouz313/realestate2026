@extends('layouts.admin')

@section('title', 'Permissions for {{ $role->name }}')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none">Roles</a></li>
        <li class="breadcrumb-item active">{{ $role->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-key me-1"></i> Permissions for {{ $role->name }}</h3>
        <div class="page-header-sub">Assign permissions to this role.</div>
    </div>
</div>

<form action="{{ route('roles.permissions.assign', $role) }}" method="POST">
    @csrf
    <input type="hidden" name="role_id" value="{{ $role->id }}">

    <div class="card table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Permission</th>
                        <th>Slug</th>
                        <th class="text-center">Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $group => $groupPermissions)
                        @foreach($groupPermissions as $permission)
                        <tr>
                            <td>{{ $group }}</td>
                            <td>{{ $permission->name }}</td>
                            <td><code>{{ $permission->slug }}</code></td>
                            <td class="text-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $rolePermissionIds ?? []) ? 'checked' : '' }}
                                    class="form-check-input permission-checkbox">
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Permissions</button>
        <a href="{{ route('roles.index') }}" class="btn btn-link text-secondary">Cancel</a>
    </div>
</form>
@endsection
