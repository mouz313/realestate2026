@extends('layouts.admin')

@section('title', 'Edit Enquiry <span class="urdu">(انکوائری میں ترمیم)</span>')

@section('content')
<div class="page-header">
    <h3>Edit Enquiry <span class="urdu">(انکوائری میں ترمیم)</span></h3>
    <div class="action-btns">
        <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> <span class="urdu">(واپس)</span>
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('contacts.update', $contact) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name <span class="urdu">(نام)</span> <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $contact->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="urdu">(فون)</span> <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $contact->phone) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $contact->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Source <span class="urdu">(ذریعہ)</span></label>
                    <select name="lead_source" class="form-select">
                        @foreach($leadSources as $key => $label)
                            <option value="{{ $key }}" {{ old('lead_source', $contact->lead_source ?? 'walk_in') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Property Type <span class="urdu">(قسم)</span> <span class="text-danger">*</span></label>
                    <select name="property_type" class="form-select" required>
                        <option value="">Select type</option>
                        @foreach($propertyTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('property_type', $contact->property_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Purpose <span class="urdu">(مقصد)</span> <span class="text-danger">*</span></label>
                    <select name="purpose" class="form-select" required>
                        <option value="">Select purpose</option>
                        @foreach($purposes as $key => $label)
                            <option value="{{ $key }}" {{ old('purpose', $contact->purpose) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">City <span class="urdu">(شہر)</span></label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $contact->city) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Area / Location <span class="urdu">(علاقہ)</span></label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $contact->location) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Budget Min (Rs) <span class="urdu">(کم از کم)</span></label>
                    <input type="number" name="budget_min" class="form-control" value="{{ old('budget_min', $contact->budget_min) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Budget Max (Rs) <span class="urdu">(زیادہ سے زیادہ)</span></label>
                    <input type="number" name="budget_max" class="form-control" value="{{ old('budget_max', $contact->budget_max) }}" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes / Message <span class="urdu">(نوٹس)</span></label>
                    <textarea name="message" class="form-control" rows="3">{{ old('message', $contact->message) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Status <span class="urdu">(حالت)</span></label>
                    <select name="status" class="form-select">
                        @foreach(\App\Models\Contact::statusOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $contact->status ?? 'open') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Enquiry</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
