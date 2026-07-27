@php
    $actionWord = $action->action === 'approved' ? 'approved' : 'rejected';
    $color = $action->action === 'approved' ? '#166534' : '#991b1b';
@endphp
<x-mail::message>
# Client Action Notification

**{{ $client->name }}** has **{{ $actionWord }}** the quotation **{{ $quotation->quote_number }}**.

@if($action->notes)
**Notes from client:**<br>
{{ $action->notes }}
@endif

@if($action->signed_name)
**Signed by:** {{ $action->signed_name }}<br>
**IP:** {{ $action->ip_address }}<br>
**Date:** {{ $action->created_at->format('d M Y H:i') }}
@endif

<x-mail::button :url="route('quotations.show', $quotation)" :color="$action->action === 'approved' ? 'success' : 'error'">
View Quotation
</x-mail::button>

<x-mail::table>
| Detail | Value |
|--------|-------|
| Quotation | {{ $quotation->quote_number }} |
| Client | {{ $client->name }} |
| Status | {{ ucfirst($action->action) }} |
| Total | {{ number_format($quotation->total, 2) }} |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
