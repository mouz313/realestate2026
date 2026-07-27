@extends('pdf.document-layout')

@section('title', 'Commission #'.$commission->id)

@section('doc-type', 'Commission Invoice')
@section('doc-number')
    #COM-{{ str_pad($commission->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ now()->format('d M Y') }}
@endsection

@section('content')
<div class="sec-title">Agent Details</div>
<table class="info">
    <tr><td>Name</td><td>{{ $commission->agent->name ?? 'N/A' }}</td></tr>
    <tr><td>CNIC</td><td>{{ $commission->agent->cnic ?? 'N/A' }}</td></tr>
    @if($commission->agent->phone)<tr><td>Phone</td><td>{{ $commission->agent->phone }}</td></tr>@endif
    @if($commission->agent->email)<tr><td>Email</td><td>{{ $commission->agent->email }}</td></tr>@endif
</table>

<div class="sec-title">Deal Details</div>
<table class="info">
    <tr><td>Deal No</td><td>{{ $commission->deal->deal_number ?? 'N/A' }}</td></tr>
    <tr><td>Property</td><td>{{ $commission->deal->property->title ?? 'N/A' }}</td></tr>
    <tr><td>Sale Price</td><td>Rs. {{ number_format($commission->deal->sale_price ?? 0, 2) }}</td></tr>
</table>

<div class="amount-box">
    <div class="lbl">Commission Amount</div>
    <div class="val">Rs. {{ number_format($commission->amount, 2) }}</div>
    <div class="sub">Rate: {{ $commission->percentage ?? 0 }}% &bull; Status: {{ ucfirst($commission->status ?? 'pending') }}</div>
</div>

<div class="sec-title">Signatures</div>
<div class="signatures">
    <table>
        <tr>
            <td><strong>Agent</strong><br>_________________________</td>
            <td><strong>Agency Rep</strong><br>_________________________</td>
            <td></td>
        </tr>
    </table>
</div>
@endsection
