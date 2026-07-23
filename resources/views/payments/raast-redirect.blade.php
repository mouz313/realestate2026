@extends('layouts.admin')
@section('title', 'Raast Payment')
@section('content')
<div class="card">
    <div class="card-body text-center py-5">
        <i class="ti ti-building-bank" style="font-size: 3rem;"></i>
        <h4 class="mt-3">Raast / IBAN Transfer</h4>
        <p class="text-secondary">Please transfer the amount to the following IBAN:</p>
        <div class="border rounded p-3 d-inline-block bg-light">
            <strong>IBAN:</strong> {{ $iban ?? 'N/A' }}<br>
            <strong>Amount:</strong> PKR {{ number_format($amount ?? 0, 2) }}<br>
            <strong>Reference:</strong> {{ $reference ?? 'N/A' }}
        </div>
        <p class="mt-3 small text-secondary">After payment, please send the confirmation to the agency.</p>
        <a href="{{ route('invoices.index') }}" class="btn btn-dark mt-2">Back to Invoices</a>
    </div>
</div>
@endsection
