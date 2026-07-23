@extends('layouts.admin')

@section('title', 'Quotations <span class="urdu">(کوٹیشنز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Quotations <span class="urdu">(کوٹیشنز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Quotations <span class="urdu">(کوٹیشنز)</span></h3>
        <div class="page-header-sub">{{ $quotations->total() }} <span class="urdu">کل کوٹیشنز</span></div>
    </div>
    <a href="{{ route('quotations.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> New Quotation <span class="urdu">(نیا کوٹیشن)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th># <span class="urdu">(نمبر)</span></th>
                    <th>Client <span class="urdu">(گاہک)</span></th>
                    <th class="d-none d-sm-table-cell">Date <span class="urdu">(تاریخ)</span></th>
                    <th>Total <span class="urdu">(کل)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                <tr>
                    <td class="fw-semibold">{{ $q->quote_number }}</td>
                    <td>{{ $q->client->name }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $q->created_at->format('d M Y') }}</td>
                    <td class="fw-medium">{{ number_format($q->total, 0) }}</td>
                    <td>
                        @php $sc = ['draft' => 'status-draft', 'sent' => 'status-sent', 'approved' => 'status-approved', 'rejected' => 'status-rejected', 'invoiced' => 'status-invoiced']; @endphp
                        <span class="badge {{ $sc[$q->status] ?? 'status-draft' }}">{{ ucfirst($q->status) }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('quotations.show', $q) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            @if($q->status === 'draft')
                                <a href="{{ route('quotations.edit', $q) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('quotations.destroy', $q) }}" method="POST" onsubmit="return confirm('Delete this quotation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ti ti-file-description"></i>
                            <p>No quotations yet. <span class="urdu">(کوئی کوٹیشن نہیں)</span></p>
                            <a href="{{ route('quotations.create') }}" class="text-decoration-none fw-medium">Create your first quotation <span class="urdu">(اپنا پہلا کوٹیشن بنائیں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotations->hasPages())
    <div class="p-3 border-top">
        {{ $quotations->links() }}
    </div>
    @endif
</div>
@endsection
