@extends('layouts.admin')

@section('title', 'Permissions')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Permissions</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-lock me-1"></i> Permissions</h3>
        <div class="page-header-sub">All permissions available in your company.</div>
    </div>
    <div class="ms-auto">
        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Permission
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
                    <th>Group</th>
                    <th>Description</th>
                    <th class="d-none d-md-table-cell text-center">System</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group => $groupPermissions)
                    @foreach($groupPermissions as $permission)
                    <tr>
                        <td class="fw-semibold">{{ $permission->name }}</td>
                        <td><code>{{ $permission->slug }}</code></td>
                        <td>{{ $permission->group }}</td>
                        <td class="text-secondary">{{ $permission->description ?? '—' }}</td>
                        <td class="d-none d-md-table-cell text-center">
                            @if($permission->is_system)
                                <span class="badge bg-info">System</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            @if(! $permission->is_system)
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this permission?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>No permissions found.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
