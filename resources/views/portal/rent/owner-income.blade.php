@extends('portal.layouts.app')

@section('title', 'Income Overview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-chart-bar me-1"></i> Income Overview</h4>
</div>

@if(count($monthlyIncome))
<div class="card portal-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Month</th><th class="text-end">Total Income</th><th>Properties</th></tr></thead>
                <tbody>
                    @foreach($monthlyIncome as $mi)
                    <tr>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($mi['month'] . '-01')->format('F Y') }}</td>
                        <td class="text-end fw-bold text-success">Rs. {{ number_format($mi['total'], 0) }}</td>
                        <td>
                            @foreach($mi['breakdown'] as $b)
                                <span class="badge bg-light text-dark me-1">{{ $b['property'] }}: Rs. {{ number_format($b['amount'], 0) }}</span>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card portal-card">
    <div class="card-body text-center py-5 text-secondary">
        <i class="ti ti-chart-bar" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
        No income data available yet.
    </div>
</div>
@endif
@endsection
