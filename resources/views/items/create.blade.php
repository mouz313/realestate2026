@extends('layouts.admin')

@section('title', 'Add Item <span class="urdu">(نیا آئٹم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('items.index') }}" class="text-decoration-none">Items <span class="urdu">(آئٹمز)</span></a></li>
        <li class="breadcrumb-item active">Add Item <span class="urdu">(نیا آئٹم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-package-plus me-1"></i> Add New Item <span class="urdu">(نیا آئٹم)</span></h4>
    </div>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="urdu">(وضاحت)</span></label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Default Price <span class="urdu">(قیمت)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('default_price') is-invalid @enderror" name="default_price" value="{{ old('default_price', 0) }}" required>
                        @error('default_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit <span class="urdu">(یونٹ)</span> <span class="text-secondary small fw-normal">(e.g. pcs, kg, hours)</span></label>
                        <input type="text" class="form-control" name="unit" value="{{ old('unit') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Item <span class="urdu">(آئٹم محفوظ)</span></button>
            <a href="{{ route('items.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
