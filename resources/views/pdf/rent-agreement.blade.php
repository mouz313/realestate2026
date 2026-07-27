@extends('pdf.document-layout')

@section('title', 'Rent Agreement #'.$rentAgreement->id)

@section('doc-type', 'Rent Agreement')
@section('doc-number')
    #AG-{{ str_pad($rentAgreement->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ $rentAgreement->created_at->format('d M Y') }}
@endsection

@section('content')
<div class="sec-title">Parties</div>
<p>THIS AGREEMENT is made on {{ $rentAgreement->created_at->format('d M Y') }} between:</p>
<p><strong>OWNER:</strong> {{ $rentAgreement->owner->name ?? 'N/A' }} &mdash; CNIC: {{ $rentAgreement->owner->cnic ?? 'N/A' }} &mdash; {{ $rentAgreement->owner->phone ?? '' }}</p>
<p><strong>TENANT:</strong> {{ $rentAgreement->tenant->name ?? 'N/A' }} &mdash; CNIC: {{ $rentAgreement->tenant->cnic ?? 'N/A' }} &mdash; {{ $rentAgreement->tenant->phone ?? '' }}</p>

<div class="sec-title">Property</div>
<table class="info">
    <tr><td>Title</td><td>{{ $rentAgreement->property->title ?? 'N/A' }}</td></tr>
    <tr><td>Location</td><td>{{ $rentAgreement->property->location_address ?? 'N/A' }}, {{ $rentAgreement->property->city ?? '' }}</td></tr>
    <tr><td>Type</td><td>{{ ucfirst($rentAgreement->property->type ?? 'N/A') }}</td></tr>
</table>

<div class="sec-title">Rent Terms</div>
<table class="info">
    <tr><td>Monthly Rent</td><td>Rs. {{ number_format($rentAgreement->rent_amount, 2) }}</td></tr>
    <tr><td>Security Deposit</td><td>Rs. {{ number_format($rentAgreement->security_deposit ?? 0, 2) }}</td></tr>
    @if($rentAgreement->start_date && $rentAgreement->end_date)
    <tr><td>Period</td><td>{{ $rentAgreement->start_date->diffInMonths($rentAgreement->end_date) }} months</td></tr>
    @endif
    <tr><td>Start / End</td><td>{{ $rentAgreement->start_date->format('d M Y') }} @if($rentAgreement->end_date) &rarr; {{ $rentAgreement->end_date->format('d M Y') }} @endif</td></tr>
</table>

<div class="sec-title">Terms</div>
<ol>
    <li>Rent of Rs. {{ number_format($rentAgreement->rent_amount, 2) }} payable by 10th each month.</li>
    <li>Security deposit of Rs. {{ number_format($rentAgreement->security_deposit ?? 0, 2) }} refundable at end of tenancy (less damages).</li>
    <li>Tenant shall not sublet without written consent.</li>
    <li>Utility bills payable by Tenant.</li>
    <li>30 days notice required from either party.</li>
</ol>

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Owner</strong><br>_________________________</td>
            <td><strong>Tenant</strong><br>_________________________</td>
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
