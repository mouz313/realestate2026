@extends('pdf.document-layout')

@section('title', 'Rent Receipt #'.$rentPayment->id)

@section('doc-type', 'Rent Receipt')
@section('doc-number')
    #RR-{{ str_pad($rentPayment->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ $rentPayment->paid_date ? $rentPayment->paid_date->format('d M Y') : now()->format('d M Y') }}
@endsection

@section('content')
<div class="row">
    <div class="col-half">
        <div class="sec-title">Tenant Details</div>
        <table class="info">
            <tr><td>Tenant</td><td>{{ $rentPayment->rentAgreement->tenant?->name ?? 'N/A' }}</td></tr>
            <tr><td>Phone</td><td>{{ $rentPayment->rentAgreement->tenant?->phone ?? 'N/A' }}</td></tr>
            <tr><td>CNIC</td><td>{{ $rentPayment->rentAgreement->tenant?->cnic ?? 'N/A' }}</td></tr>
        </table>
    </div>
    <div class="col-half">
        <div class="sec-title">Owner Details</div>
        <table class="info">
            <tr><td>Owner</td><td>{{ $rentPayment->rentAgreement->owner?->name ?? 'N/A' }}</td></tr>
            <tr><td>Phone</td><td>{{ $rentPayment->rentAgreement->owner?->phone ?? 'N/A' }}</td></tr>
        </table>
    </div>
</div>

<div class="sec-title">Property</div>
<table class="info">
    <tr><td>Property</td><td>{{ $rentPayment->rentAgreement->property?->title ?? 'N/A' }}</td></tr>
    <tr><td>Location</td><td>{{ $rentPayment->rentAgreement->property?->location_address ?? 'N/A' }}, {{ $rentPayment->rentAgreement->property?->city ?? '' }}</td></tr>
</table>

<div class="sec-title">Payment Details</div>
<table class="info">
    <tr><td>For Month</td><td>{{ date('F Y', mktime(0, 0, 0, $rentPayment->month, 1, $rentPayment->year)) }}</td></tr>
    <tr><td>Due Date</td><td>{{ $rentPayment->due_date->format('d M Y') }}</td></tr>
    <tr><td>Monthly Rent</td><td>Rs. {{ number_format($rentPayment->amount, 2) }}</td></tr>
    <tr><td>Late Fee</td><td>Rs. {{ number_format($rentPayment->late_fee, 2) }}</td></tr>
    <tr><td style="font-weight:bold;background:#f2f2f2;">Total Paid</td><td style="font-weight:bold;background:#f2f2f2;color:#10b981;">Rs. {{ number_format($rentPayment->total_due, 2) }}</td></tr>
    <tr><td>Payment Method</td><td>{{ ucfirst(str_replace('_', ' ', $rentPayment->payment_method ?? 'N/A')) }}</td></tr>
    @if($rentPayment->reference_no)
    <tr><td>Reference</td><td>{{ $rentPayment->reference_no }}</td></tr>
    @endif
    <tr><td>Paid On</td><td>{{ $rentPayment->paid_date ? $rentPayment->paid_date->format('d M Y') : 'N/A' }}</td></tr>
</table>

@if($rentPayment->notes)
<div class="sec-title">Notes</div>
<p>{{ $rentPayment->notes }}</p>
@endif

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Tenant</strong><br>_________________________</td>
            <td><strong>Owner</strong><br>_________________________</td>
            <td><strong>Agent</strong><br>_________________________</td>
        </tr>
    </table>
</div>
@endsection
