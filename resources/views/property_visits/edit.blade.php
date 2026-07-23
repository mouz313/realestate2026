@extends('layouts.admin')

@section('title', 'Edit Property Visit <span class="urdu">(جائیداد کے دورے میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('property-visits.index') }}" class="text-decoration-none">Property Visits <span class="urdu">(جائیداد کے دورے)</span></a></li>
        <li class="breadcrumb-item active">Edit Visit <span class="urdu">(دورے میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header flex-wrap gap-2">
        <h4><i class="ti ti-eye"></i> Edit Property Visit <span class="urdu">(جائیداد کے دورے میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('property-visits.update', $propertyVisit) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Property <span class="urdu">(جائیداد)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('property_id') is-invalid @enderror" name="property_id" required>
                            <option value="">Select Property <span class="urdu">(جائیداد منتخب)</span></option>
                            @foreach($properties ?? [] as $property)
                                <option value="{{ $property->id }}" {{ old('property_id', $propertyVisit->property_id) == $property->id ? 'selected' : '' }}>{{ $property->title }} ({{ $property->property_code ?? $property->id }})</option>
                            @endforeach
                        </select>
                        @error('property_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client <span class="urdu">(گاہک)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('client_id') is-invalid @enderror" name="client_id" required>
                            <option value="">Select Client <span class="urdu">(گاہک منتخب)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $propertyVisit->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agent <span class="urdu">(ایجنٹ)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('agent_id') is-invalid @enderror" name="agent_id" required>
                            <option value="">Select Agent <span class="urdu">(ایجنٹ منتخب)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $propertyVisit->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Scheduled Date & Time <span class="urdu">(طے شدہ تاریخ اور وقت)</span> <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('scheduled_date') is-invalid @enderror" name="scheduled_date" value="{{ old('scheduled_date', $propertyVisit->scheduled_date ? $propertyVisit->scheduled_date->format('Y-m-d\TH:i') : '') }}" required>
                        @error('scheduled_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="scheduled" {{ old('status', $propertyVisit->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ old('status', $propertyVisit->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $propertyVisit->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rescheduled" {{ old('status', $propertyVisit->status) == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            <option value="no_show" {{ old('status', $propertyVisit->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $propertyVisit->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Visit <span class="urdu">(دورہ اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('property-visits.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
