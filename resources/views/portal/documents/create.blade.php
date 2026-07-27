@extends('portal.layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="ti ti-upload me-1"></i> Upload Document <span class="urdu">(دستاویز اپ لوڈ)</span></h4>
    <a href="{{ route('portal.documents.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back <span class="urdu">(واپس)</span></a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('portal.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Document Type <span class="urdu">(دستاویز کی قسم)</span> <span class="text-danger">*</span></label>
                <select name="document_type" class="form-select" required>
                    <option value="">Select type <span class="urdu">(قسم منتخب کریں)</span></option>
                    <option value="cnic">CNIC <span class="urdu">(شناختی کارڈ)</span></option>
                    <option value="passport">Passport <span class="urdu">(پاسپورٹ)</span></option>
                    <option value="property_deed">Property Deed <span class="urdu">(جائیداد دستاویز)</span></option>
                    <option value="bank_statement">Bank Statement <span class="urdu">(بینک اسٹیٹمنٹ)</span></option>
                    <option value="tax_document">Tax Document <span class="urdu">(ٹیکس دستاویز)</span></option>
                    <option value="other">Other <span class="urdu">(دیگر)</span></option>
                </select>
                @error('document_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">File <span class="urdu">(فائل)</span> <span class="text-danger">*</span></label>
                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                <div class="form-text">Accepted: PDF, JPG, PNG. Max 10MB. <span class="urdu">(قبول شدہ: PDF, JPG, PNG. زیادہ سے زیادہ 10MB)</span></div>
                @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
                @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-dark">
                <i class="ti ti-upload"></i> Upload <span class="urdu">(اپ لوڈ)</span>
            </button>
            <a href="{{ route('portal.documents.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </form>
    </div>
</div>
@endsection
