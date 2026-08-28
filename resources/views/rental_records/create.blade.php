@extends('layouts.admin')

@section('title', 'Add Rental Record')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('rental-records.index') }}" class="text-decoration-none">Rental Records <span class="urdu">(کرایہ ریکارڈ)</span></a></li>
        <li class="breadcrumb-item active">Add Rental Record <span class="urdu">(نیا ریکارڈ)</span></li>
        </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-home-plus me-1"></i> Add Rental Record <span class="urdu">(نیا کرایہ ریکارڈ)</span></h4>
    </div>
    <form action="{{ route('rental-records.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Property <span class="urdu">(جائیداد)</span> <span class="text-danger">*</span></label>
                        <select name="property_id" class="form-select @error('property_id') is-invalid @enderror" required>
                            <option value="">Select property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>{{ $property->title }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenant <span class="urdu">(کرایہ دار)</span></label>
                        <select name="tenant_select" id="tenant_select" class="form-select">
                            <option value="">Select tenant</option>
                            <option value="__new__" @selected(old('tenant_name'))>➕ Add new tenant</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('tenant_id') == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="tenant_id" id="tenant_id" value="{{ old('tenant_id') }}">
                        @error('tenant_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="row g-2 mt-2" id="tenant_new" style="display:@if(old('tenant_name')) flex @else none @endif;">
                            <div class="col"><input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" placeholder="Tenant name" value="{{ old('tenant_name') }}"></div>
                            <div class="col"><input type="text" name="tenant_phone" class="form-control @error('tenant_phone') is-invalid @enderror" placeholder="Tenant phone" value="{{ old('tenant_phone') }}"></div>
                        </div>
                        <div class="form-text">Client is created only when this record is saved.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Landlord <span class="urdu">(مالک)</span></label>
                        <select name="landlord_select" id="landlord_select" class="form-select">
                            <option value="">Select landlord</option>
                            <option value="__new__" @selected(old('landlord_name'))>➕ Add new landlord</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('landlord_id') == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="landlord_id" id="landlord_id" value="{{ old('landlord_id') }}">
                        @error('landlord_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="row g-2 mt-2" id="landlord_new" style="display:@if(old('landlord_name')) flex @else none @endif;">
                            <div class="col"><input type="text" name="landlord_name" class="form-control @error('landlord_name') is-invalid @enderror" placeholder="Landlord name" value="{{ old('landlord_name') }}"></div>
                            <div class="col"><input type="text" name="landlord_phone" class="form-control @error('landlord_phone') is-invalid @enderror" placeholder="Landlord phone" value="{{ old('landlord_phone') }}"></div>
                        </div>
                        <div class="form-text">Client is created only when this record is saved.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Created By <span class="urdu">(تخلیق کنندہ)</span></label>
                        <select name="created_by" class="form-select @error('created_by') is-invalid @enderror">
                            <option value="">Select agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" @selected(old('created_by') == $agent->id)>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('created_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Start Date <span class="urdu">(شروع کی تاریخ)</span></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date <span class="urdu">(اختتام کی تاریخ)</span></label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (months) <span class="urdu">(دورانیہ)</span></label>
                        <input type="number" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror" value="{{ old('duration_months') }}" min="0">
                        @error('duration_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span> <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" @selected(old('status', 'active') == 'active')>Active</option>
                            <option value="ended" @selected(old('status') == 'ended')>Ended</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save Record <span class="urdu">(محفوظ)</span></button>
            <a href="{{ route('rental-records.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function bindClientToggle(selectId, idField, newBox) {
        const select = document.getElementById(selectId);
        const hidden = document.getElementById(idField);
        const box = document.getElementById(newBox);
        function apply() {
            if (select.value === '__new__') { hidden.value = ''; box.style.display = 'flex'; }
            else { hidden.value = select.value; box.style.display = 'none'; }
        }
        select.addEventListener('change', apply);
        apply();
    }
    bindClientToggle('tenant_select', 'tenant_id', 'tenant_new');
    bindClientToggle('landlord_select', 'landlord_id', 'landlord_new');
</script>
@endpush
@endsection
