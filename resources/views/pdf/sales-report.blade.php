@extends('pdf.document-layout')

@section('title', 'Sales Report')

@section('doc-type', 'Sales Report')
@section('doc-number', $start.' to '.$end)

@section('content')
<div class="sec-title alt">Summary</div>
<table class="info" style="margin-bottom:18px;">
    <tr>
        <td style="width:33%;text-align:center;border:1px solid #e5e5e5;padding:10px;">
            <div style="font-size:8px;color:#8A8A8A;text-transform:uppercase;letter-spacing:.8px;">Deals Closed</div>
            <div style="font-size:18px;font-weight:900;color:#2B2B2B;margin-top:3px;">{{ $deals->count() }}</div>
        </td>
        <td style="width:33%;text-align:center;border:1px solid #e5e5e5;padding:10px;">
            <div style="font-size:8px;color:#8A8A8A;text-transform:uppercase;letter-spacing:.8px;">Total Volume</div>
            <div style="font-size:18px;font-weight:900;color:#2B2B2B;margin-top:3px;">{{ number_format($totalVolume, 0) }}</div>
        </td>
        <td style="width:33%;text-align:center;border:1px solid #e5e5e5;padding:10px;">
            <div style="font-size:8px;color:#8A8A8A;text-transform:uppercase;letter-spacing:.8px;">Total Commission</div>
            <div style="font-size:18px;font-weight:900;color:#2B2B2B;margin-top:3px;">{{ number_format($totalCommission, 0) }}</div>
        </td>
    </tr>
</table>

<div class="sec-title alt">Deal Details</div>
<table class="items">
    <thead>
        <tr>
            <th>Deal #</th>
            <th>Property</th>
            <th>Buyer</th>
            <th>Agent</th>
            <th class="ta-r">Price</th>
            <th class="ta-r">Commission</th>
        </tr>
    </thead>
    <tbody>
        @forelse($deals as $d)
        <tr>
            <td>{{ $d->deal_number }}</td>
            <td>{{ $d->property?->title ?? '-' }}</td>
            <td>{{ $d->buyer?->name ?? '-' }}</td>
            <td>{{ $d->agent?->name ?? '-' }}</td>
            <td class="ta-r">{{ number_format($d->sale_price ?? 0, 0) }}</td>
            <td class="ta-r">{{ number_format($d->commission_amount ?? 0, 0) }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="ta-c" style="padding:20px;color:#8A8A8A;">No completed deals in this period.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="text-align:center;margin-top:14px;font-size:8px;color:#8A8A8A;">
    Generated on {{ now()->format('d M Y H:i') }} &middot; {{ config('app.name') }}
</div>
@endsection
