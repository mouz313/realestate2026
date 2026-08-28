@extends('layouts.admin')

@section('title', 'Deleted Deals')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('deals.index') }}" class="text-decoration-none">Deals</a></li>
        <li class="breadcrumb-item active">Trash</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Deleted Deals <span class="urdu">(مٹائی گئی ڈیلز)</span></h3>
        <div class="page-header-sub">{{ $deals->total() }} deleted</div>
    </div>
    <a href="{{ route('deals.index') }}" class="btn btn-outline-dark">
        <i class="ti ti-arrow-left"></i> Back to Deals
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Deal #</th>
                    <th>Type</th>
                    <th>Property</th>
                    <th>Buyer</th>
                    <th>Agent</th>
                    <th>Sale Price</th>
                    <th>Deleted At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                <tr>
                    <td class="fw-semibold">{{ $deal->deal_number }}</td>
                    <td>{{ ucfirst($deal->type ?? '-') }}</td>
                    <td>{{ $deal->property->title ?? '-' }}</td>
                    <td>{{ $deal->buyer->name ?? '-' }}</td>
                    <td>{{ $deal->agent->name ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($deal->sale_price, 0) }}</td>
                    <td class="text-secondary">{{ $deal->deleted_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <div class="action-btns d-flex gap-1 flex-nowrap">
                            <form action="{{ route('deals.restore', $deal) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                    <i class="ti ti-restore"></i>
                                </button>
                            </form>
                            <form action="{{ route('deals.force-delete', $deal) }}" method="POST" onsubmit="return confirm('This will permanently delete the deal. Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Permanently Delete">
                                    <i class="ti ti-trash-x"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="ti ti-trash-off"></i>
                            <p>No deleted deals.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deals->hasPages())
    <div class="p-3 border-top">
        {{ $deals->links() }}
    </div>
    @endif
</div>
@endsection
