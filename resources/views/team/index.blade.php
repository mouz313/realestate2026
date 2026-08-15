@extends('layouts.admin')

@section('title', 'Team')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Team</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-users-group me-1"></i> Team</h3>
        <div class="page-header-sub">{{ $type === 'staff' ? $staffCount : $agentCount }} {{ $type === 'staff' ? 'staff members' : 'agents' }}</div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @can('manage_agents')
        <a href="{{ route('agents.create') }}" class="btn btn-dark">
            <i class="ti ti-plus"></i> Add Agent
        </a>
        @endcan
        @can('manage_users')
        <a href="{{ route('users.create') }}" class="btn btn-outline-dark">
            <i class="ti ti-user-plus"></i> Add Staff / User
        </a>
        @endcan
    </div>
</div>

<ul class="nav nav-pills gap-2 mb-3">
    <li class="nav-item">
        <a href="{{ route('team.index', ['type' => 'agents']) }}" class="btn btn-sm {{ $type !== 'staff' ? 'btn-dark' : 'btn-outline-secondary' }}">
            <i class="ti ti-briefcase"></i> Agents <span class="badge text-bg-light ms-1">{{ $agentCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('team.index', ['type' => 'staff']) }}" class="btn btn-sm {{ $type === 'staff' ? 'btn-dark' : 'btn-outline-secondary' }}">
            <i class="ti ti-user-cog"></i> Staff <span class="badge text-bg-light ms-1">{{ $staffCount }}</span>
        </a>
    </li>
</ul>

@if($type === 'staff')
<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $user)
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
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                        @empty
                            <span class="text-secondary text-xs">No roles</span>
                        @endforelse
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge status-active">Active</span>
                        @else
                            <span class="badge status-draft">Disabled</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('manage_users')
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Roles & Permissions">
                            <i class="ti ti-user-cog"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ti ti-user-cog"></i>
                            <p>No staff members yet.</p>
                            @can('manage_users')
                            <a href="{{ route('users.create') }}" class="text-decoration-none fw-medium">Add your first staff member</a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staff->hasPages())
    <div class="p-3 border-top">
        {{ $staff->links() }}
    </div>
    @endif
</div>
@else
<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td class="fw-semibold">
                        <div class="d-flex align-items-center gap-2">
                            @if($agent->photo)
                                <img src="{{ asset('storage/'.$agent->photo) }}" alt="" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-uppercase" style="width:32px;height:32px;">
                                    <span class="fw-semibold">{{ $agent->name[0] ?? 'A' }}</span>
                                </div>
                            @endif
                            <span>{{ $agent->name }}</span>
                        </div>
                    </td>
                    <td>{{ $agent->phone ?? '-' }}</td>
                    <td>{{ $agent->email ?? '-' }}</td>
                    <td class="text-secondary">{{ $agent->role ?? '-' }}</td>
                    <td>
                        @php $sc = \App\Helpers\Status::classes('team'); @endphp
                        <span class="badge {{ $sc[$agent->status] ?? 'status-draft' }}">{{ ucfirst($agent->status ?? 'inactive') }}</span>
                    </td>
                    <td>
                        @if($agent->user)
                            <span class="badge status-active" title="{{ $agent->user->email }}">Login Active</span>
                        @else
                            <span class="badge status-draft">No Login</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('manage_agents')
                            <form action="{{ route('agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Delete this agent?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ti ti-briefcase"></i>
                            <p>No agents yet.</p>
                            @can('manage_agents')
                            <a href="{{ route('agents.create') }}" class="text-decoration-none fw-medium">Add your first agent</a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agents->hasPages())
    <div class="p-3 border-top">
        {{ $agents->links() }}
    </div>
    @endif
</div>
@endif
@endsection