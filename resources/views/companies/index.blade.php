@extends('layouts.admin')

@section('title', 'Companies')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Companies</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Companies</h3>
        <div class="page-header-sub">{{ $companies->count() }} total</div>
    </div>
    <a href="{{ route('companies.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Company
    </a>
</div>

@if(session('company_id'))
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>
            <i class="ti ti-building"></i> Currently viewing data for
            <strong>{{ \App\Models\Company::find(session('company_id'))?->name }}</strong>
        </span>
        <span class="text-secondary small">Use the arrow buttons below to switch company.</span>
    </div>
@endif

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th class="d-none d-md-table-cell">Contact</th>
                    <th>Users</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td class="fw-semibold">{{ $company->name }}</td>
                    <td><code>{{ $company->slug }}</code></td>
                    <td class="d-none d-md-table-cell">
                        @if($company->email)<div>{{ $company->email }}</div>@endif
                        @if($company->phone)<div class="text-secondary small">{{ $company->phone }}</div>@endif
                    </td>
                    <td>{{ $company->users_count }}</td>
                    <td>
                        <span class="badge {{ $company->is_active ? 'status-active' : 'status-draft' }}">{{ $company->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#switchModal{{ $company->id }}" title="Switch">
                                <i class="ti ti-arrow-swap"></i>
                            </button>
                            <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Delete this company? Its data will be orphaned.')">
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
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ti ti-building"></i>
                            <p>No companies yet.</p>
                            <a href="{{ route('companies.create') }}" class="text-decoration-none fw-medium">Add your first company</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach($companies as $company)
<div class="modal fade" id="switchModal{{ $company->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('companies.switch', $company) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Switch to {{ $company->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    You are about to switch your active company to <strong>{{ $company->name }}</strong>. All admin screens will now show this company's data.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Switch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
