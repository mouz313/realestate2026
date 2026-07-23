@extends('portal.layouts.app')

@section('title', $quotation->quote_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $quotation->quote_number }}</h4>
    <a href="{{ route('portal.quotations') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-6">
                <h5 class="mb-1">{{ $settings['business_name'] ?? config('app.name') }}</h5>
                <p class="text-secondary mb-0 small">{{ $settings['business_address'] ?? '' }}</p>
                <p class="text-secondary mb-0 small">{{ $settings['business_email'] ?? '' }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-0 small">Date: {{ $quotation->created_at->format('d M Y') }}</p>
                @if($quotation->expiry_date)
                    <p class="mb-0 small">Expiry: {{ $quotation->expiry_date->format('d M Y') }}</p>
                @endif
                <p class="mb-0 small">
                    @php $sc = ['draft' => 'secondary', 'sent' => 'primary', 'approved' => 'success', 'rejected' => 'danger']; @endphp
                    <span class="badge bg-{{ $sc[$quotation->status] ?? 'secondary' }}">{{ ucfirst($quotation->status) }}</span>
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
                    <tr>
                        <td><span class="fw-semibold">{{ $item->item_name }}</span></td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-end fw-semibold">Subtotal:</td>
                        <td class="text-end" style="width:120px">{{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->tax_rate > 0)
                    <tr>
                        <td class="text-end fw-semibold">{{ $settings['tax_label'] ?? 'GST' }} ({{ $quotation->tax_rate }}%):</td>
                        <td class="text-end">{{ number_format($quotation->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="fw-bold fs-5">
                        <td class="text-end">Total:</td>
                        <td class="text-end">{{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($quotation->notes)
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-secondary mb-1">Notes:</h6>
                <p class="mb-0">{{ $quotation->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@if($action)
<div class="alert alert-info">
    <i class="ti ti-history me-1"></i>
    You <strong>{{ $action->action }}</strong> this quotation on {{ $action->created_at->format('d M Y, h:i A') }}.
</div>
@endif

<div class="d-flex gap-2">
    <a href="{{ route('portal.quotations.pdf', $quotation) }}" class="btn btn-outline-dark"><i class="ti ti-file-download"></i> Download PDF</a>
    @if(in_array($quotation->status, ['sent', 'draft']))
        <form action="{{ route('portal.quotations.approve', $quotation) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this quotation?')"><i class="ti ti-check"></i> Approve</button>
        </form>
        <form action="{{ route('portal.quotations.reject', $quotation) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this quotation?')"><i class="ti ti-x"></i> Reject</button>
        </form>
    @endif
</div>
@endsection
