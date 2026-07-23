@extends('layouts.admin')

@section('title', 'Agents')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Agents</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Agents</h3>
        <div class="page-header-sub">{{ $agents->total() }} total agents</div>
    </div>
    <a href="{{ route('agents.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Agent
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Experience</th>
                    <th>Languages</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                <tr>
                    <td class="fw-semibold">{{ $agent->name }}</td>
                    <td class="text-secondary">{{ $agent->role ?? '-' }}</td>
                    <td>{{ $agent->phone ?? '-' }}</td>
                    <td><a href="mailto:{{ $agent->email }}" class="text-decoration-none">{{ $agent->email ?? '-' }}</a></td>
                    <td>{{ $agent->experience_years ? $agent->experience_years . ' yrs' : '-' }}</td>
                    <td>{{ $agent->languages ?? '-' }}</td>
                    <td>
                        @php $sc = ['active' => 'status-active', 'inactive' => 'status-draft', 'suspended' => 'status-cancelled']; @endphp
                        <span class="badge {{ $sc[$agent->status] ?? 'status-draft' }}">{{ ucfirst($agent->status ?? 'inactive') }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Delete this agent?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="ti ti-briefcase"></i>
                            <p>No agents yet.</p>
                            <a href="{{ route('agents.create') }}" class="text-decoration-none fw-medium">Add your first agent</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agents->hasPages())
    <div class="p-3 border-top">
        {{ $agents->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection