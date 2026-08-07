@extends('layouts.admin')

@section('title', 'Packages')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Packages</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-ticket me-1"></i> Packages</h3>
        <div class="page-header-sub">Define monthly/yearly plans with employee, client and property limits.</div>
    </div>
    <a href="{{ route('packages.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Package
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th class="d-none d-md-table-cell text-end">Price</th>
                    <th class="d-none d-md-table-cell">Interval</th>
                    <th>Limits</th>
                    <th>Trial</th>
                    <th>Subscribers</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                <tr>
                    <td class="fw-semibold">{{ $package->name }}</td>
                    <td><code>{{ $package->slug }}</code></td>
                    <td class="d-none d-md-table-cell text-end">{{ $package->price > 0 ? number_format($package->price, 2) : 'Free' }}</td>
                    <td class="d-none d-md-table-cell">{{ $package->intervalLabel() }}</td>
                    <td>
                        <span class="text-secondary small">E:{{ $package->limitLabel('max_employees') }} · C:{{ $package->limitLabel('max_clients') }} · P:{{ $package->limitLabel('max_properties') }}</span>
                    </td>
                    <td>{{ $package->trial_days ? $package->trial_days.'d' : '—' }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $package->subscriptions_count ?? $package->subscriptions()->count() }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $package->is_active ? 'status-active' : 'status-draft' }}">{{ $package->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('packages.edit', $package) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="ti ti-edit"></i></a>
                            <form action="{{ route('packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Delete package {{ $package->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state"><p>No packages defined yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
