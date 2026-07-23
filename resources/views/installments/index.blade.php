@extends('layouts.admin')

@section('title', 'Installments <span class="urdu">(اقساط)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Installments <span class="urdu">(اقساط)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Installments <span class="urdu">(اقساط)</span></h3>
        <div class="page-header-sub">{{ $installments->total() }} <span class="urdu">(کل اقساط)</span></div>
    </div>
    <a href="{{ route('installments.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> <span class="urdu">(قسط کا منصوبہ شامل کریں)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plan / Deal <span class="urdu">(منصوبہ / ڈیل)</span></th>
                    <th>Amount <span class="urdu">(رقم)</span></th>
                    <th>Paid <span class="urdu">(ادا شدہ)</span></th>
                    <th>Due Date <span class="urdu">(واجب الادا تاریخ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Late Fee <span class="urdu">(تاخیری فیس)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($installments as $installment)
                <tr>
                    <td class="fw-semibold">{{ $installment->installment_no ?? $installment->id }}</td>
                    <td>
                        @if($installment->plan && $installment->plan->deal)
                            <a href="{{ route('deals.show', $installment->plan->deal) }}" class="text-decoration-none fw-medium">{{ $installment->plan->deal->deal_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="fw-medium">{{ number_format($installment->amount, 0) }}</td>
                    <td>{{ number_format($installment->paid_amount ?? 0, 0) }}</td>
                    <td class="text-secondary">{{ $installment->due_date ? $installment->due_date->format('d M Y') : '-' }}</td>
                    <td>
                        @php $sc = ['pending' => 'status-pending', 'paid' => 'status-paid', 'overdue' => 'status-cancelled', 'partial' => 'status-partial']; @endphp
                        <span class="badge {{ $sc[$installment->status] ?? 'status-pending' }}">{{ ucfirst($installment->status ?? 'pending') }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $installment->late_fee ? number_format($installment->late_fee, 0) : '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('installments.edit', $installment) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('installments.destroy', $installment) }}" method="POST" onsubmit="return confirm('Delete this installment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="ti ti-calendar-stats"></i>
                            <p>No installments yet. <span class="urdu">(ابھی تک کوئی قسط نہیں)</span></p>
                            <a href="{{ route('installments.create') }}" class="text-decoration-none fw-medium"><span class="urdu">(اپنا پہلا قسط کا منصوبہ شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($installments->hasPages())
    <div class="p-3 border-top">
        {{ $installments->links() }}
    </div>
    @endif
</div>
@endsection