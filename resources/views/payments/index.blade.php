@extends('layouts.admin')

@section('title', 'Payments <span class="urdu">(ادائیگیاں)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Payments <span class="urdu">(ادائیگیاں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Payments <span class="urdu">(ادائیگیاں)</span></h3>
        <div class="page-header-sub">{{ $payments->total() }} <span class="urdu">کل ادائیگیاں</span></div>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th># <span class="urdu">(نمبر)</span></th>
                    <th>Invoice <span class="urdu">(انوائس)</span></th>
                    <th>Client <span class="urdu">(گاہک)</span></th>
                    <th>Amount <span class="urdu">(رقم)</span></th>
                    <th class="d-none d-sm-table-cell">Method <span class="urdu">(ذریعہ)</span></th>
                    <th class="d-none d-sm-table-cell">Reference <span class="urdu">(حوالہ)</span></th>
                    <th class="d-none d-sm-table-cell">Paid Date <span class="urdu">(ادائیگی کی تاریخ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="fw-semibold">{{ $payment->id }}</td>
                    <td><a href="{{ route('invoices.show', $payment->invoice) }}" class="text-decoration-none fw-medium">{{ $payment->invoice->invoice_number }}</a></td>
                    <td>{{ $payment->invoice->client->name }}</td>
                    <td class="fw-medium text-success">{{ number_format($payment->amount, 0) }}</td>
                    <td class="d-none d-sm-table-cell">{{ $payment->method ?: '-' }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $payment->reference ?: '-' }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $payment->paid_date->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-sm btn-outline-secondary" title="View Invoice">
                                <i class="ti ti-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="ti ti-currency-dollar"></i>
                            <p>No payments recorded yet. <span class="urdu">(کوئی ادائیگی ریکارڈ نہیں)</span></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="p-3 border-top">{{ $payments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
