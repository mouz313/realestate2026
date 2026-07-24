@extends('layouts.admin')

@section('title', 'Quotation <span class="urdu">(کوٹیشن)</span> ' . $quotation->quote_number)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}" class="text-decoration-none">Quotations <span class="urdu">(کوٹیشنز)</span></a></li>
        <li class="breadcrumb-item active">{{ $quotation->quote_number }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>{{ $quotation->quote_number }}</h3>
    <div class="page-header-sub">
        <span class="badge status-{{ $quotation->status ?? 'draft' }}">{{ ucfirst($quotation->status ?? 'draft') }}</span>
    </div>
    <div class="action-btns">
        @if($quotation->status === 'draft')
            <form action="{{ route('quotations.mark-sent', $quotation) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i> Mark Sent <span class="urdu">(بھیجا گیا نشان زد)</span></button>
            </form>
            <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-outline-secondary"><i class="ti ti-edit"></i> Edit <span class="urdu">(ترمیم)</span></a>
        @endif
        @if(in_array($quotation->status, ['sent', 'approved']))
            <a href="{{ route('invoices.convert', $quotation) }}" class="btn btn-success"><i class="ti ti-file-invoice"></i> Convert to Invoice <span class="urdu">(انوائس میں تبدیل)</span></a>
        @endif
        @if($quotation->status === 'invoiced')
            <span class="btn btn-outline-secondary disabled"><i class="ti ti-check"></i> Already Invoiced <span class="urdu">(پہلے ہی انوائس شدہ)</span></span>
        @endif
        <a href="{{ route('quotations.pdf', $quotation) }}" class="btn btn-dark"><i class="ti ti-file-download"></i> PDF <span class="urdu">(پی ڈی ایف)</span></a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-6">
                <h5 class="mb-1">{{ $settings['business_name'] ?? config('app.name') }}</h5>
                <p class="text-secondary mb-0 small">{{ $settings['business_address'] ?? '' }}</p>
                <p class="text-secondary mb-0 small">{{ $settings['business_email'] ?? '' }}</p>
                <p class="text-secondary mb-0 small">{{ $settings['business_phone'] ?? '' }}</p>
            </div>
            <div class="col-6 text-end">
                <h5 class="mb-1">QUOTATION <span class="urdu">(کوٹیشن)</span></h5>
                <p class="mb-0 small"># {{ $quotation->quote_number }}</p>
                <p class="mb-0 small">Date: <span class="urdu">(تاریخ)</span> {{ $quotation->created_at->format('d M Y') }}</p>
                @if($quotation->expiry_date)
                    <p class="mb-0 small">Expiry: <span class="urdu">(میعاد ختم)</span> {{ $quotation->expiry_date->format('d M Y') }}</p>
                @endif
                <p class="mb-0 small">
                    <span class="badge status-{{ $quotation->status ?? 'draft' }}">{{ ucfirst($quotation->status ?? 'draft') }}</span>
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-secondary mb-1">Bill To: <span class="urdu">(بل وصول کنندہ)</span></h6>
                <p class="mb-0 fw-semibold">{{ $quotation->client->name ?? '-' }}</p>
                <p class="mb-0 small text-secondary">{{ $quotation->client->company ?? '' }}</p>
                <p class="mb-0 small text-secondary">{{ $quotation->client->address ?? '' }}</p>
                <p class="mb-0 small text-secondary">{{ $quotation->client->email ?? '' }}</p>
                <p class="mb-0 small text-secondary">{{ $quotation->client->phone ?? '' }}</p>
            </div>
            @if($quotation->property)
            <div class="col-6">
                <h6 class="text-secondary mb-1">Property: <span class="urdu">(پراپرٹی)</span></h6>
                <p class="mb-0 fw-semibold">{{ $quotation->property->title }}</p>
                <p class="mb-0 small text-secondary">{{ $quotation->property->location_address ?? '' }}, {{ $quotation->property->city ?? '' }}</p>
                @if($quotation->property->plot_size)
                <p class="mb-0 small text-secondary">{{ ucfirst($quotation->property->type) }} — {{ $quotation->property->plot_size }} {{ $quotation->property->plot_size_unit ?? '' }}</p>
                @endif
                <p class="mb-0 small text-secondary">Price: Rs. {{ number_format($quotation->property->price, 0) }}</p>
            </div>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Item <span class="urdu">(آئٹم)</span></th>
                        <th class="text-center">Qty <span class="urdu">(مقدار)</span></th>
                        <th class="text-center d-none d-sm-table-cell">Unit <span class="urdu">(یونٹ)</span></th>
                        <th class="text-end">Price <span class="urdu">(قیمت)</span></th>
                        <th class="text-end">Total <span class="urdu">(کل)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $item->item_name }}</span>
                            @if($item->description)
                                <br><small class="text-secondary">{{ $item->description }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center d-none d-sm-table-cell">{{ $item->unit ?? '-' }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-3">
            <div class="col-md-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-end fw-semibold">Subtotal: <span class="urdu">(ذیلی کل)</span></td>
                        <td class="text-end" style="width: 120px;">{{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-end fw-semibold">{{ $settings['tax_label'] ?? 'GST' }} ({{ $quotation->tax_rate }}%):</td>
                        <td class="text-end">{{ number_format($quotation->tax_amount, 2) }}</td>
                    </tr>
                    <tr class="fw-bold fs-5">
                        <td class="text-end">Total: <span class="urdu">(کل)</span></td>
                        <td class="text-end">{{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($quotation->notes)
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-secondary mb-1">Notes: <span class="urdu">(نوٹس)</span></h6>
                <p class="mb-0">{{ $quotation->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
