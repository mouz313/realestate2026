@extends('layouts.admin')

@section('title', 'Payments')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Payments <span class="urdu">(ادائیگیاں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Payments <span class="urdu">(ادائیگیاں)</span></h3>
        <div class="page-header-sub">{{ $payments->total() }} <span class="urdu">کل ادائیگیاں</span></div>
    </div>
    <a href="{{ route('payments.export-excel') }}" class="btn btn-outline-success"><i class="ti ti-file-spreadsheet"></i> Export Excel <span class="urdu">(اکسل برآمد)</span></a>
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
                    @if($payment->invoice_id)
                    <td><a href="{{ route('invoices.show', $payment->invoice) }}" class="text-decoration-none fw-medium">{{ $payment->invoice->invoice_number }}</a></td>
                    <td>{{ $payment->invoice->client->name }}</td>
                    @else
                    <td>
                        @if($payment->payment_type === 'security_deposit_return')
                        <span class="badge bg-danger">Deposit Return <span class="urdu">(واپسی)</span></span>
                        @else
                        <span class="badge bg-info text-dark">Security Deposit</span>
                        @endif
                    </td>
                    <td>-</td>
                    @endif
                    <td class="fw-medium {{ $payment->payment_type === 'security_deposit_return' ? 'text-danger' : 'text-success' }}">{{ $payment->payment_type === 'security_deposit_return' ? '- ' : '' }}{{ number_format($payment->amount, 0) }}</td>
                    <td class="d-none d-sm-table-cell">{{ $payment->method ?: '-' }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $payment->reference ?: '-' }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $payment->paid_date->format('d M Y') }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            @if($payment->invoice_id)
                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-sm btn-outline-secondary" title="View Invoice">
                                <i class="ti ti-eye"></i>
                            </a>
                            @endif
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
