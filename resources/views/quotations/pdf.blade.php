@extends('pdf.document-layout')

@section('title', 'Quotation '.$quotation->quote_number)

@section('doc-type', 'QUOTATION')
@section('doc-number')
    #{{ $quotation->quote_number }} &bull; {{ $quotation->created_at->format('d M Y') }}
@endsection

@section('content')
<div class="info-row">
    <div class="ir-left">
        <div class="bill-label">QUOTATION TO:</div>
        <div class="bill-name">{{ $quotation->client->name }}</div>
        <div class="bill-detail">
            @if($quotation->client->company){{ $quotation->client->company }}<br>@endif
            @if($quotation->client->address){{ $quotation->client->address }}<br>@endif
            {{ $quotation->client->email }} &nbsp;|&nbsp; {{ $quotation->client->phone }}
        </div>
    </div>
    <div class="ir-right">
        <div class="info-box">
            <div class="ib-row">
                <div class="ib-label">Status</div>
                <div class="ib-value">
                    <span class="badge badge-{{ $quotation->status }}">{{ ucfirst($quotation->status) }}</span>
                </div>
            </div>
            <div class="ib-row">
                <div class="ib-label">Expiry Date</div>
                <div class="ib-value">{{ $quotation->expiry_date ? $quotation->expiry_date->format('d M Y') : '-' }}</div>
            </div>
        </div>
    </div>
</div>

@if($quotation->property)
<div class="prop-card">
    <div class="prop-card-header">Property Details</div>
    <div class="prop-card-body">
        <table class="prop-table">
            <tr>
                <td style="width: 50%;">
                    <div class="f-label">Title</div>
                    <div class="f-value">{{ $quotation->property->title }}</div>
                </td>
                <td style="width: 50%;">
                    <div class="f-label">Location</div>
                    <div class="f-value">{{ $quotation->property->location_address ?? '' }}{{ $quotation->property->city ? ', '.$quotation->property->city : '' }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="f-label">Price</div>
                    <div class="f-value">Rs. {{ number_format($quotation->property->price, 0) }}</div>
                </td>
                <td>
                    @if($quotation->property->plot_size)
                        <div class="f-label">Size</div>
                        <div class="f-value">{{ $quotation->property->plot_size }} {{ $quotation->property->plot_size_unit ?? '' }}</div>
                    @elseif($quotation->property->type)
                        <div class="f-label">Type</div>
                        <div class="f-value">{{ ucfirst($quotation->property->type) }}</div>
                    @endif
                </td>
            </tr>
            @if($quotation->property->plot_size && $quotation->property->type)
            <tr>
                <td>
                    <div class="f-label">Type</div>
                    <div class="f-value">{{ ucfirst($quotation->property->type) }}</div>
                </td>
                <td></td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endif

<div class="items-wrap">
    <table class="items">
        <thead>
            <tr>
                <th class="col-no">#</th>
                <th style="width:40%;">Item Description</th>
                <th style="width:12%;" class="ta-r">Price</th>
                <th style="width:8%;" class="ta-c">Qty</th>
                <th style="width:14%;" class="ta-r">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotation->items as $idx => $item)
            <tr>
                <td class="ta-c" style="color:#8A8A8A;">{{ $idx + 1 }}</td>
                <td>
                    <div class="item-name">{{ $item->item_name }}</div>
                    @if($item->description)<div class="item-desc">{{ $item->description }}</div>@endif
                </td>
                <td class="ta-r">{{ number_format($item->unit_price, 2) }}</td>
                <td class="ta-c">{{ $item->quantity }}</td>
                <td class="ta-r">{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="ta-c" style="color:#8A8A8A;padding:16px;">No items listed.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bottom">
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 15px;">
        <tr>
            <td style="width: 52%; vertical-align: top; padding-right: 15px;">
                @if(!empty($settings['quotation_terms']))
                <div class="info-heading">Terms &amp; Conditions</div>
                <div class="info-text">{{ $settings['quotation_terms'] }}</div>
                @endif
                @if($quotation->notes)
                <div class="notes-wrap">
                    <div class="notes-label">Notes</div>
                    <div class="notes-text">{{ $quotation->notes }}</div>
                </div>
                @endif
            </td>
            <td style="width: 48%; vertical-align: top;">
                <div class="totals-box">
                    <table>
                        <tr>
                            <td class="lbl">Subtotal</td>
                            <td class="val">{{ number_format($quotation->subtotal, 2) }}</td>
                        </tr>
                        @if($quotation->discount_amount > 0)
                        <tr>
                            <td class="lbl" style="color:#dc2626;">Discount</td>
                            <td class="val" style="color:#dc2626;">-{{ number_format($quotation->discount_amount, 2) }}
                                @if($quotation->discount_type === 'percentage')
                                    <small>({{ $quotation->discount_value }}%)</small>
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="lbl">{{ $settings['tax_label'] ?? 'GST' }} ({{ $quotation->tax_rate }}%)</td>
                            <td class="val">{{ number_format($quotation->tax_amount, 2) }}</td>
                        </tr>
                        <tr class="sep"><td colspan="2"><div></div></td></tr>
                        <tr class="grand">
                            <td class="lbl">Grand Total</td>
                            <td class="val">{{ number_format($quotation->total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="ss-table">
        <tr>
            <td style="width: 48%;">
                <div class="ss-box-div">
                    <div class="ss-label">Sign</div>
                    <div class="ss-line"></div>
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%;">
                <div class="ss-box-div">
                    <div class="ss-label">Stamp</div>
                    <div class="ss-line"></div>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="qr-wrap">
    <img src="{{ $qrCode }}" alt="QR">
    <div class="qr-label">Scan to verify</div>
</div>
@endsection
