@extends('layouts.admin')

@section('title', 'Invoices <span class="urdu">(انوائسز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Invoices <span class="urdu">(انوائسز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Invoices <span class="urdu">(انوائسز)</span></h3>
        <div class="page-header-sub">{{ $invoices->total() }} <span class="urdu">کل انوائسز</span></div>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn btn-dark"><i class="ti ti-plus"></i> Add Invoice <span class="urdu">(انوائس شامل)</span></a>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-0">Search <span class="urdu">(تلاش)</span></label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Invoice # or client..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Status <span class="urdu">(کیفیت)</span></label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Payment <span class="urdu">(ادائیگی)</span></label>
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Client <span class="urdu">(گاہک)</span></label>
                <select name="client_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">From <span class="urdu">(سے)</span></label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">To <span class="urdu">(تک)</span></label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-dark btn-sm"><i class="ti ti-filter"></i> Filter</button>
                @if(request()->anyFilled(['search', 'status', 'payment_status', 'client_id', 'date_from', 'date_to']))
                <a href="{{ route('invoices.index') }}" class="small text-decoration-none ms-2">Clear filters <span class="urdu">(فلٹر صاف)</span></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th># <span class="urdu">(نمبر)</span></th>
                    <th>Client <span class="urdu">(گاہک)</span></th>
                    <th class="d-none d-sm-table-cell">Date <span class="urdu">(تاریخ)</span></th>
                    <th class="d-none d-sm-table-cell">Due Date <span class="urdu">(آخری تاریخ)</span></th>
                    <th>Total <span class="urdu">(کل)</span></th>
                    <th class="d-none d-sm-table-cell">Paid <span class="urdu">(ادا شدہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->client->name }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $inv->created_at->format('d M Y') }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $inv->due_date ? $inv->due_date->format('d M Y') : '-' }}</td>
                    <td class="fw-medium">{{ number_format($inv->total, 0) }}</td>
                    <td class="d-none d-sm-table-cell">{{ number_format($inv->paid_amount, 0) }}</td>
                    <td>
                        @php $ps = ['pending' => 'status-pending', 'partial' => 'status-partial', 'paid' => 'status-paid']; @endphp
                        <span class="badge {{ $ps[$inv->payment_status] ?? 'status-pending' }}">{{ ucfirst($inv->payment_status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('invoices.pdf', $inv) }}" class="btn btn-sm btn-outline-secondary" title="PDF">
                                <i class="ti ti-file-download"></i>
                            </a>
                            <form action="{{ route('invoices.destroy', $inv) }}" method="POST" onsubmit="return confirm('Delete this invoice?')">
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
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="ti ti-file-invoice"></i>
                            <p>No invoices yet. <span class="urdu">(کوئی انوائس نہیں)</span></p>
                            <a href="{{ route('invoices.create') }}" class="text-decoration-none fw-medium">Create your first invoice <span class="urdu">(اپنی پہلی انوائس بنائیں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="p-3 border-top">{{ $invoices->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
