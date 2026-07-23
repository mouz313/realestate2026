@extends('layouts.admin')

@section('title', 'Edit Item <span class="urdu">(آئٹم کی ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('items.index') }}" class="text-decoration-none">Items <span class="urdu">(آئٹمز)</span></a></li>
        <li class="breadcrumb-item active">Edit: {{ $item->name }} <span class="urdu">(ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-package me-1"></i> Edit Item <span class="urdu">(آئٹم کی ترمیم)</span></h4>
    </div>
    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $item->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="urdu">(وضاحت)</span></label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Default Price <span class="urdu">(قیمت)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('default_price') is-invalid @enderror" name="default_price" value="{{ old('default_price', $item->default_price) }}" required>
                        @error('default_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit <span class="urdu">(یونٹ)</span></label>
                        <input type="text" class="form-control" name="unit" value="{{ old('unit', $item->unit) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes', $item->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Item <span class="urdu">(آئٹم اپ ڈیٹ)</span></button>
            <a href="{{ route('items.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
