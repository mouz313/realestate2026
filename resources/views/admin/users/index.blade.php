@extends('layouts.admin')

@section('title', 'Users')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-users me-1"></i> Users & Roles</h3>
        <div class="page-header-sub">Assign roles and direct permissions to your team members.</div>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-dark">
        <i class="ti ti-user-plus"></i> Add User
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Roles</th>
                    <th class="d-none d-md-table-cell">Primary Role</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-semibold">
                        <div class="d-flex align-items-center gap-2">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-uppercase" style="width:32px;height:32px;">
                                    <span class="fw-semibold">{{ $user->name[0] ?? 'U' }}</span>
                                </div>
                            @endif
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Disabled</span>
                        @endif
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                        @empty
                            <span class="text-secondary text-xs">No roles assigned</span>
                        @endforelse
                    </td>
                    <td class="d-none d-md-table-cell">
                        <code>{{ $user->role }}</code>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Assign Roles & Permissions">
                            <i class="ti ti-user-cog"></i> Roles & Permissions
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>No users found.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection