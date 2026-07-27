@extends('pdf.document-layout')

@section('title', 'Possession Letter '.$deal->deal_number)

@section('doc-type', 'Possession Letter')
@section('doc-number', $deal->possession_date ? $deal->possession_date->format('d M Y') : now()->format('d M Y'))

@section('content')
<div class="sec-title">Property</div>
<table class="info">
    <tr><td>Title</td><td>{{ $deal->property->title ?? 'N/A' }}</td></tr>
    <tr><td>Location</td><td>{{ $deal->property->location_address ?? 'N/A' }}, {{ $deal->property->city ?? '' }}</td></tr>
</table>

<div class="sec-title">Certification</div>
<p>This is to certify that possession of the above property has been handed over by:</p>
<p><strong>SELLER:</strong> {{ $deal->seller->name ?? 'N/A' }}</p>
<p><strong>TO BUYER:</strong> {{ $deal->buyer->name ?? 'N/A' }}</p>
<p>All dues have been cleared and the property is in satisfactory condition.</p>

<div class="sec-title">Handover</div>
<table class="info">
    <tr><td>Keys Handed Over</td><td>________ sets</td></tr>
    <tr><td>Electric Meter</td><td>________</td></tr>
    <tr><td>Gas Meter</td><td>________</td></tr>
    <tr><td>Water Meter</td><td>________</td></tr>
</table>

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Seller</strong><br>_________________________</td>
            <td><strong>Buyer</strong><br>_________________________</td>
            <td><strong>Agent</strong><br>_________________________</td>
        </tr>
        <tr>
            <td><strong>Witness</strong><br>_________________________</td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
@endsection
