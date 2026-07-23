@extends('layouts.admin')

@section('title', $client->name)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}" class="text-decoration-none">Clients <span class="urdu">(گاہک)</span></a></li>
        <li class="breadcrumb-item active">{{ $client->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>{{ $client->name }}</h3>
        <div class="page-header-sub">Client since <span class="urdu">(گاہک از)</span> {{ $client->created_at->format('M Y') }}</div>
    </div>
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-dark">
        <i class="ti ti-edit"></i> Edit <span class="urdu">(ترمیم)</span>
    </a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-info-circle me-1"></i> Client Details <span class="urdu">(گاہک کی تفصیلات)</span></h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr><th>Name <span class="urdu">(نام)</span></th><td>{{ $client->name }}</td></tr>
                    <tr><th>Company <span class="urdu">(کمپنی)</span></th><td>{{ $client->company ?? '-' }}</td></tr>
                    <tr><th>Email <span class="urdu">(ای میل)</span></th><td>{{ $client->email ?? '-' }}</td></tr>
                    <tr><th>Phone <span class="urdu">(فون)</span></th><td>{{ $client->phone ?? '-' }}</td></tr>
                    <tr><th>Address <span class="urdu">(پتہ)</span></th><td>{{ $client->address ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        @if($client->notes)
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="ti ti-notes me-1"></i> Notes <span class="urdu">(نوٹس)</span></h5>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-0">{{ $client->notes }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5><i class="ti ti-file-description me-1"></i> Recent Quotations <span class="urdu">(حالیہ کوٹیشنز)</span></h5>
                <a href="{{ route('quotations.create') }}?client_id={{ $client->id }}" class="btn btn-sm btn-outline-secondary">+ New <span class="urdu">(نیا)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>#</th><th class="d-none d-sm-table-cell">Date <span class="urdu">(تاریخ)</span></th><th class="d-none d-sm-table-cell">Total <span class="urdu">(کل)</span></th><th>Status <span class="urdu">(کیفیت)</span></th></tr>
                        </thead>
                        <tbody>
                            @forelse($client->quotations as $q)
                            <tr>
                                <td><a href="{{ route('quotations.show', $q) }}" class="text-decoration-none fw-medium">{{ $q->quote_number }}</a></td>
                                <td class="text-secondary d-none d-sm-table-cell">{{ $q->created_at->format('d M Y') }}</td>
                                <td class="fw-medium d-none d-sm-table-cell">{{ number_format($q->total, 2) }}</td>
                                <td>
                                    @php $sc = ['draft'=>'status-draft','sent'=>'status-sent','approved'=>'status-approved','rejected'=>'status-rejected','invoiced'=>'status-invoiced']; @endphp
                                    <span class="badge {{ $sc[$q->status] ?? 'status-draft' }}">{{ ucfirst($q->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">No quotations yet. <span class="urdu">(کوئی کوٹیشن نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5><i class="ti ti-file-invoice me-1"></i> Recent Invoices <span class="urdu">(حالیہ انوائسز)</span></h5>
                <a href="{{ route('invoices.create') }}?client_id={{ $client->id }}" class="btn btn-sm btn-outline-secondary">+ New <span class="urdu">(نیا)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>#</th><th class="d-none d-sm-table-cell">Date <span class="urdu">(تاریخ)</span></th><th class="d-none d-sm-table-cell">Total <span class="urdu">(کل)</span></th><th class="d-none d-sm-table-cell">Paid <span class="urdu">(ادا شدہ)</span></th><th>Status <span class="urdu">(کیفیت)</span></th></tr>
                        </thead>
                        <tbody>
                            @forelse($client->invoices as $inv)
                            <tr>
                                <td><a href="{{ route('invoices.show', $inv) }}" class="text-decoration-none fw-medium">{{ $inv->invoice_number }}</a></td>
                                <td class="text-secondary d-none d-sm-table-cell">{{ $inv->created_at->format('d M Y') }}</td>
                                <td class="fw-medium d-none d-sm-table-cell">{{ number_format($inv->total, 2) }}</td>
                                <td class="d-none d-sm-table-cell">{{ number_format($inv->paid_amount, 2) }}</td>
                                <td>
                                    @php $ps = ['pending'=>'status-pending','partial'=>'status-partial','paid'=>'status-paid']; @endphp
                                    <span class="badge {{ $ps[$inv->payment_status] ?? 'status-pending' }}">{{ ucfirst($inv->payment_status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-secondary py-4">No invoices yet. <span class="urdu">(کوئی انوائس نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
