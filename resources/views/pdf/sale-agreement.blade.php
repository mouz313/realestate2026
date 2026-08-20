@extends('pdf.document-layout')

@section('title', 'Sale Agreement '.$deal->deal_number)

@section('doc-type', 'Sale Agreement')
@section('doc-number')
    #{{ $deal->deal_number }} &bull; {{ now()->format('d M Y') }}
@endsection

@section('content')
<div class="sec-title">Parties</div>
<p>THIS AGREEMENT is made on {{ now()->format('d M Y') }} between:</p>
<p><strong>SELLER:</strong> {{ $deal->seller->name ?? 'N/A' }} &mdash; CNIC: {{ $deal->seller->cnic ?? 'N/A' }}</p>
<p><strong>BUYER:</strong> {{ $deal->buyer->name ?? 'N/A' }} &mdash; CNIC: {{ $deal->buyer->cnic ?? 'N/A' }}</p>
<p>WHEREAS the Seller is the lawful owner of the property described below.</p>

<div class="sec-title">Property</div>
<table class="info">
    <tr><td>Title</td><td>{{ $deal->property->title ?? 'N/A' }}</td></tr>
    <tr><td>Location</td><td>{{ $deal->property->location_address ?? 'N/A' }}, {{ $deal->property->city ?? '' }}</td></tr>
    <tr><td>Type / Size</td><td>{{ \App\Helpers\Status::categoryLabel($deal->property->category ?? 'N/A') }} &mdash; {{ $deal->property->plot_size ?? '' }} {{ $deal->property->plot_size_unit ?? '' }}</td></tr>
</table>

<div class="sec-title">Sale Consideration</div>
<p><strong>Sale Price:</strong> Rs. {{ number_format($deal->sale_price, 2) }}  <span class="amount-words">(Rs. {{ number_format($deal->sale_price, 2) }} only)</span></p>
<p><strong>Token Received:</strong> Rs. {{ number_format($deal->token_amount ?? 0, 2) }}</p>
@if($deal->possession_date)<p><strong>Possession:</strong> {{ $deal->possession_date->format('d M Y') }}</p>@endif

<div class="sec-title">Terms</div>
<ol>
    <li>Total consideration: Rs. {{ number_format($deal->sale_price, 2) }}.</li>
    <li>Token advance of Rs. {{ number_format($deal->token_amount ?? 0, 2) }} received.</li>
    <li>Balance due on possession: {{ $deal->possession_date ? $deal->possession_date->format('d M Y') : 'TBD' }}.</li>
    <li>Seller guarantees clear title with no encumbrances.</li>
    <li>Registration / taxes payable by Buyer.</li>
</ol>

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Seller</strong><br>_________________________</td>
            <td><strong>Buyer</strong><br>_________________________</td>
            <td><strong>Agent</strong><br>_________________________</td>
        </tr>
        <tr>
            <td><strong>Witness 1</strong><br>_________________________</td>
            <td><strong>Witness 2</strong><br>_________________________</td>
            <td></td>
        </tr>
    </table>
</div>
@endsection
