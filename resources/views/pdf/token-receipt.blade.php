@extends('pdf.document-layout')

@section('title', 'Token Receipt '.$deal->deal_number)

@section('doc-type', 'Token Receipt')
@section('doc-number')
    #{{ $deal->deal_number }}-TKN &bull; {{ $token->received_date ? $token->received_date->format('d M Y') : now()->format('d M Y') }}
@endsection

@section('content')
<div class="sec-title">Receipt</div>
<table class="info">
    <tr><td>Received From</td><td>{{ $deal->buyer->name ?? 'N/A' }}</td></tr>
    <tr><td>Property</td><td>{{ $deal->property->title ?? 'N/A' }}, {{ $deal->property->location_address ?? '' }}</td></tr>
    <tr><td>Sale Price</td><td>Rs. {{ number_format($deal->sale_price, 2) }}</td></tr>
    <tr><td>Token Amount</td><td>Rs. {{ number_format($token->amount, 2) }}</td></tr>
    <tr><td>In Words</td><td class="amount-words">Rs. {{ number_format($token->amount, 2) }} only</td></tr>
    <tr><td>Payment Method</td><td>{{ $token->payment_method ?? 'N/A' }}</td></tr>
    <tr><td>Reference</td><td>{{ $token->reference_no ?? 'N/A' }}</td></tr>
</table>

<div class="sec-title">Terms</div>
<ol>
    <li>This token is part of the total sale consideration.</li>
    <li>If Buyer backs out, this amount may be forfeited.</li>
    <li>If Seller backs out, double shall be refunded to Buyer.</li>
    <li>Balance payment as per agreement.</li>
</ol>

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Receiver</strong><br>_________________________</td>
            <td><strong>Payer</strong><br>_________________________</td>
            <td><strong>Witness</strong><br>_________________________</td>
        </tr>
    </table>
</div>
@endsection
