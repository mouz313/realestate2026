@extends('layouts.admin')

@section('title', 'Roles')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-shield me-1"></i> Roles & Permissions</h3>
        <div class="page-header-sub">Manage roles and their permissions for your company.</div>
    </div>
    <div class="ms-auto">
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Role
        </a>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Permissions</th>
                    <th class="d-none d-md-table-cell text-end">System</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td class="fw-semibold">{{ $role->name }}</td>
                    <td><code>{{ $role->slug }}</code></td>
                    <td class="text-secondary">{{ $role->description ?? '—' }}</td>
                    <td>
                        @php
                            $perms = $role->permissions;
                        @endphp
                        <span class="badge bg-secondary">{{ count($perms) }}</span>
                        @if(count($perms) > 0)
                            <span class="text-xs text-secondary">{{ $perms->pluck('name')->join(', ') }}</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-center">
                        @if($role->is_system)
                            <span class="badge bg-info">System</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group" role="group">
                            <a href="{{ route('roles.permissions', $role) }}" class="btn btn-sm btn-outline-primary" title="Manage Permissions">
                                <i class="ti ti-key"></i> Permissions
                            </a>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            @if(! $role->is_system)
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>No roles found.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
