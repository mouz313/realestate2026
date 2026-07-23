@extends('layouts.admin')

@section('title', 'Invoices <span class="urdu">(انوائسز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Invoices <span class="urdu">(انوائسز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Invoices <span class="urdu">(انوائسز)</span></h3>
        <div class="page-header-sub">{{ $invoices->total() }} <span class="urdu">کل انوائسز</span></div>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn btn-dark"><i class="ti ti-plus"></i> Add Invoice <span class="urdu">(انوائس شامل)</span></a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th># <span class="urdu">(نمبر)</span></th>
                    <th>Client <span class="urdu">(گاہک)</span></th>
                    <th class="d-none d-sm-table-cell">Date <span class="urdu">(تاریخ)</span></th>
                    <th class="d-none d-sm-table-cell">Due Date <span class="urdu">(آخری تاریخ)</span></th>
                    <th>Total <span class="urdu">(کل)</span></th>
                    <th class="d-none d-sm-table-cell">Paid <span class="urdu">(ادا شدہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->client->name }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $inv->created_at->format('d M Y') }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $inv->due_date ? $inv->due_date->format('d M Y') : '-' }}</td>
                    <td class="fw-medium">{{ number_format($inv->total, 0) }}</td>
                    <td class="d-none d-sm-table-cell">{{ number_format($inv->paid_amount, 0) }}</td>
                    <td>
                        @php $ps = ['pending' => 'status-pending', 'partial' => 'status-partial', 'paid' => 'status-paid']; @endphp
                        <span class="badge {{ $ps[$inv->payment_status] ?? 'status-pending' }}">{{ ucfirst($inv->payment_status) }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('invoices.pdf', $inv) }}" class="btn btn-sm btn-outline-secondary" title="PDF">
                                <i class="ti ti-file-download"></i>
                            </a>
                            <form action="{{ route('invoices.destroy', $inv) }}" method="POST" onsubmit="return confirm('Delete this invoice?')">
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
                            <i class="ti ti-file-invoice"></i>
                            <p>No invoices yet. <span class="urdu">(کوئی انوائس نہیں)</span></p>
                            <a href="{{ route('invoices.create') }}" class="text-decoration-none fw-medium">Create your first invoice <span class="urdu">(اپنی پہلی انوائس بنائیں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="p-3 border-top">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
