@extends('layouts.admin')

@section('title', 'Item Templates <span class="urdu">(آئٹم ٹیمپلیٹس)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-decoration-none">Settings <span class="urdu">(ترتیبات)</span></a></li>
        <li class="breadcrumb-item active">Item Templates <span class="urdu">(آئٹم ٹیمپلیٹس)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3><i class="ti ti-template me-1"></i> Item Templates <span class="urdu">(آئٹم ٹیمپلیٹس)</span></h3>
        <div class="page-header-sub">Pre-defined line items for quotations and invoices <span class="urdu">(کوٹیشنز اور انوائسز کے لیے پہلے سے طے شدہ آئٹمز)</span></div>
    </div>
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="ti ti-plus"></i> New Template <span class="urdu">(نیا ٹیمپلیٹ)</span>
    </button>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th class="d-none d-md-table-cell">Description <span class="urdu">(تفصیل)</span></th>
                    <th>Category <span class="urdu">(زمرہ)</span></th>
                    <th>Unit <span class="urdu">(یونٹ)</span></th>
                    <th>Default Price <span class="urdu">(طے شدہ قیمت)</span></th>
                    <th>Active <span class="urdu">(فعال)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $t)
                <tr>
                    <td class="fw-semibold">{{ $t->name }}</td>
                    <td class="d-none d-md-table-cell text-secondary">{{ Str::limit($t->description, 40) }}</td>
                    <td>{{ $t->category ?? '-' }}</td>
                    <td>{{ $t->unit ?? '-' }}</td>
                    <td>{{ number_format($t->default_price, 2) }}</td>
                    <td>
                        @if($t->is_active)
                            <span class="badge status-paid">Yes</span>
                        @else
                            <span class="badge status-draft">No</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"
                                data-bs-toggle="modal" data-bs-target="#editModal-{{ $t->id }}">
                                <i class="ti ti-edit"></i>
                            </button>
                            <form action="{{ route('item-templates.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete this template?')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ti ti-template"></i>
                            <p>No item templates yet. <span class="urdu">(کوئی آئٹم ٹیمپلیٹ نہیں)</span></p>
                            <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Create your first template <span class="urdu">(اپنا پہلا ٹیمپلیٹ بنائیں)</span></button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($templates->hasPages())
    <div class="p-3 border-top">
        {{ $templates->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('item-templates.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">New Item Template <span class="urdu">(نیا آئٹم ٹیمپلیٹ)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="urdu">(تفصیل)</span></label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="urdu">(زمرہ)</span></label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Legal, Registration">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit <span class="urdu">(یونٹ)</span></label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. lot, sqft">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Default Price <span class="urdu">(طے شدہ قیمت)</span> <span class="text-danger">*</span></label>
                    <input type="number" name="default_price" step="0.01" min="0" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel <span class="urdu">(منسوخ)</span></button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save <span class="urdu">(محفوظ)</span></button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($templates as $t)
<div class="modal fade" id="editModal-{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('item-templates.update', $t) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Template <span class="urdu">(ٹیمپلیٹ میں ترمیم)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $t->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="urdu">(تفصیل)</span></label>
                    <textarea name="description" class="form-control" rows="2">{{ $t->description }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="urdu">(زمرہ)</span></label>
                        <input type="text" name="category" class="form-control" value="{{ $t->category }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit <span class="urdu">(یونٹ)</span></label>
                        <input type="text" name="unit" class="form-control" value="{{ $t->unit }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Default Price <span class="urdu">(طے شدہ قیمت)</span> <span class="text-danger">*</span></label>
                    <input type="number" name="default_price" step="0.01" min="0" class="form-control" value="{{ $t->default_price }}" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" id="activeCheck-{{ $t->id }}" {{ $t->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="activeCheck-{{ $t->id }}">Active <span class="urdu">(فعال)</span></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel <span class="urdu">(منسوخ)</span></button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update <span class="urdu">(اپ ڈیٹ)</span></button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
