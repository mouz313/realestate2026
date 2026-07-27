@extends('pdf.document-layout')

@section('title', 'Invoice '.$invoice->invoice_number)

@section('doc-type', 'INVOICE')
@section('doc-number')
    #{{ $invoice->invoice_number }} &bull; {{ $invoice->created_at->format('d M Y') }}
@endsection

@section('content')
@php
    $statusClass = $invoice->payment_status === 'paid' ? 'paid' : ($invoice->payment_status === 'partial' ? 'partial' : 'pending');
@endphp

<div class="info-row">
    <div class="ir-left">
        <div class="bill-label">INVOICE TO:</div>
        <div class="bill-name">{{ $invoice->client->name }}</div>
        <div class="bill-detail">
            @if($invoice->client->company){{ $invoice->client->company }}<br>@endif
            @if($invoice->client->address){{ $invoice->client->address }}<br>@endif
            {{ $invoice->client->email }} &nbsp;|&nbsp; {{ $invoice->client->phone }}
        </div>
    </div>
    <div class="ir-right">
        <div class="info-box">
            <div class="ib-row">
                <div class="ib-label">Payment Status</div>
                <div class="ib-value">
                    <span class="badge badge-{{ $statusClass }}">{{ ucfirst($invoice->payment_status) }}</span>
                    @if($invoice->due_date && $invoice->due_date->isPast() && $invoice->payment_status !== 'paid')
                    <span class="badge badge-overdue" style="margin-left:3px;">Overdue</span>
                    @endif
                </div>
            </div>
            <div class="ib-row">
                <div class="ib-label">Due Date</div>
                <div class="ib-value">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</div>
            </div>
        </div>
    </div>
</div>

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
            @forelse($invoice->items as $idx => $item)
            <tr>
                <td class="ta-c" style="color:#8A8A8A;">{{ $idx + 1 }}</td>
                <td>
                    <div class="item-name">{{ $item->item_name }}</div>
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
                @if(!empty($settings['bank_name']) || !empty($settings['bank_account']))
                <div class="info-heading">Payment Method</div>
                <div class="info-text">
                    @if(!empty($settings['bank_name']))<strong>Bank:</strong> {{ $settings['bank_name'] }}<br>@endif
                    @if(!empty($settings['bank_account']))<strong>A/C:</strong> {{ $settings['bank_account'] }}<br>@endif
                    @if(!empty($settings['bank_iban']))<strong>IBAN:</strong> {{ $settings['bank_iban'] }}<br>@endif
                    @if(!empty($settings['bank_swift']))<strong>SWIFT:</strong> {{ $settings['bank_swift'] }}@endif
                </div>
                @endif
                @if(!empty($settings['invoice_terms']))
                <div class="info-heading">Terms &amp; Conditions</div>
                <div class="info-text">{{ $settings['invoice_terms'] }}</div>
                @endif
                @if($invoice->notes)
                <div class="notes-wrap">
                    <div class="notes-label">Notes</div>
                    <div class="notes-text">{{ $invoice->notes }}</div>
                </div>
                @endif
            </td>
            <td style="width: 48%; vertical-align: top;">
                <div class="totals-box">
                    <table>
                        <tr>
                            <td class="lbl">Subtotal</td>
                            <td class="val">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount_amount > 0)
                        <tr>
                            <td class="lbl" style="color:#dc2626;">Discount</td>
                            <td class="val" style="color:#dc2626;">-{{ number_format($invoice->discount_amount, 2) }}
                                @if($invoice->discount_type === 'percentage')
                                    <small>({{ $invoice->discount_value }}%)</small>
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="lbl">{{ $settings['tax_label'] ?? 'GST' }} ({{ $invoice->tax_rate }}%)</td>
                            <td class="val">{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr class="sep"><td colspan="2"><div></div></td></tr>
                        <tr class="grand">
                            <td class="lbl">Grand Total</td>
                            <td class="val">{{ number_format($invoice->total, 2) }}</td>
                        </tr>
                        @if($invoice->paid_amount > 0)
                        <tr>
                            <td class="lbl" style="color:#16a34a;">Paid</td>
                            <td class="val" style="color:#16a34a;">-{{ number_format($invoice->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="lbl" style="color:#dc2626;font-weight:700;">Balance Due</td>
                            <td class="val" style="color:#dc2626;font-weight:700;">{{ number_format($invoice->total - $invoice->paid_amount, 2) }}</td>
                        </tr>
                        @endif
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
