@extends('portal.layouts.app')

@section('title', 'Request Visit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Request a Visit</h4>
    <a href="{{ route('portal.visits') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('portal.visits.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Property</label>
                <select name="property_id" class="form-select @error('property_id') is-invalid @enderror">
                    <option value="">-- Select Property --</option>
                    @foreach($properties as $p)
                    <option value="{{ $p->id }}" {{ ($selectedProperty && $selectedProperty->id == $p->id) || old('property_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->title }} ({{ $p->property_code }})
                    </option>
                    @endforeach
                </select>
                @error('property_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Scheduled Date &amp; Time</label>
                <input type="datetime-local" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" value="{{ old('scheduled_date') }}">
                @error('scheduled_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" maxlength="500">{{ old('notes') }}</textarea>
                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-dark"><i class="ti ti-send me-1"></i> Submit Request</button>
        </form>
    </div>
</div>
@endsection
