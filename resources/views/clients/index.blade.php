@extends('layouts.admin')

@section('title', 'Clients <span class="urdu">(گاہک)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Clients <span class="urdu">(گاہک)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Clients <span class="urdu">(گاہک)</span></h3>
        <div class="page-header-sub">{{ $clients->total() }} <span class="urdu">(کل گاہک)</span></div>
    </div>
    <a href="{{ route('clients.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Client <span class="urdu">(نیا گاہک)</span>
    </a>
    <a href="{{ route('clients.export-excel') }}" class="btn btn-outline-success">
        <i class="ti ti-file-spreadsheet"></i> Export Excel <span class="urdu">(اکسل برآمد)</span>
    </a>
</div>

<div class="mb-3">
    <div class="btn-group" role="group" aria-label="Client type filter">
        <a href="{{ route('clients.index') }}" class="btn btn-sm {{ !request('type') ? 'btn-dark' : 'btn-outline-dark' }}">All <span class="urdu">(تمام)</span></a>
        <a href="{{ route('clients.index', ['type' => 'seller']) }}" class="btn btn-sm {{ request('type') == 'seller' ? 'btn-dark' : 'btn-outline-dark' }}">Sellers <span class="urdu">(بیچنے والے)</span></a>
        <a href="{{ route('clients.index', ['type' => 'buyer']) }}" class="btn btn-sm {{ request('type') == 'buyer' ? 'btn-dark' : 'btn-outline-dark' }}">Buyers <span class="urdu">(خریدار)</span></a>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th class="d-none d-md-table-cell">Company <span class="urdu">(کمپنی)</span></th>
                    <th class="d-none d-md-table-cell">Email <span class="urdu">(ای میل)</span></th>
                    <th>Phone <span class="urdu">(فون)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td class="fw-semibold">{{ $client->name }}</td>
                    <td class="text-secondary d-none d-md-table-cell">{{ $client->company ?? '-' }}</td>
                    <td class="d-none d-md-table-cell"><a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email ?? '-' }}</a></td>
                    <td>{{ $client->phone ?? '-' }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client?')">
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
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ti ti-users"></i>
                            <p>No clients yet. <span class="urdu">(کوئی گاہک نہیں)</span></p>
                            <a href="{{ route('clients.create') }}" class="text-decoration-none fw-medium">Add your first client <span class="urdu">(اپنا پہلا گاہک شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="p-3 border-top">
        {{ $clients->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
