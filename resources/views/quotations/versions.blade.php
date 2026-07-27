@extends('layouts.admin')

@section('title', 'Version History - ' . $quotation->quote_number)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}" class="text-decoration-none">Quotations <span class="urdu">(کوٹیشنز)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.show', $quotation) }}" class="text-decoration-none">{{ $quotation->quote_number }}</a></li>
        <li class="breadcrumb-item active">Versions <span class="urdu">(ورژنز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3><i class="ti ti-history me-1"></i> Version History <span class="urdu">(ورژن ہسٹری)</span></h3>
        <div class="page-header-sub">{{ $quotation->quote_number }} — {{ $quotation->client->name }} <span class="urdu">(کل ورژن: {{ $quotation->versions->count() }})</span></div>
    </div>
    <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left"></i> Back to Quotation <span class="urdu">(واپس)</span>
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Version <span class="urdu">(ورژن)</span></th>
                        <th>Date <span class="urdu">(تاریخ)</span></th>
                        <th>Subtotal <span class="urdu">(ذیلی کل)</span></th>
                        <th>Discount <span class="urdu">(چھوٹ)</span></th>
                        <th>Tax <span class="urdu">(ٹیکس)</span></th>
                        <th>Total <span class="urdu">(کل)</span></th>
                        <th>Items <span class="urdu">(آئٹمز)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotation->versions as $v)
                    <tr>
                        <td class="fw-semibold">v{{ $v->version_number }}</td>
                        <td class="text-secondary">{{ $v->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ number_format($v->subtotal, 2) }}</td>
                        <td>
                            @if($v->discount_amount > 0)
                                <span class="text-danger">-{{ number_format($v->discount_amount, 2) }}</span>
                                @if($v->discount_type === 'percentage')
                                    <small class="text-secondary">({{ $v->discount_value }}%)</small>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ number_format($v->tax_amount, 2) }}</td>
                        <td class="fw-bold">{{ number_format($v->total, 2) }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#itemsModal-{{ $v->id }}">
                                <i class="ti ti-eye"></i> View <span class="urdu">(دیکھیں)</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="ti ti-history"></i>
                                <p>No version history available. <span class="urdu">(کوئی ورژن ہسٹری نہیں)</span></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Items Modals --}}
@foreach($quotation->versions as $v)
<div class="modal fade" id="itemsModal-{{ $v->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Version v{{ $v->version_number }} — Items <span class="urdu">(آئٹمز)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Item <span class="urdu">(آئٹم)</span></th>
                                <th>Qty <span class="urdu">(مقدار)</span></th>
                                <th>Unit <span class="urdu">(یونٹ)</span></th>
                                <th>Price <span class="urdu">(قیمت)</span></th>
                                <th class="text-end">Total <span class="urdu">(کل)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($v->items_data ?? [] as $item)
                            <tr>
                                <td class="fw-medium">{{ $item['item_name'] ?? '' }}</td>
                                <td>{{ $item['quantity'] ?? 0 }}</td>
                                <td>{{ $item['unit'] ?? '-' }}</td>
                                <td>{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                                <td class="text-end fw-medium">{{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close <span class="urdu">(بند)</span></button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
