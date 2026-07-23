@extends('layouts.admin')

@section('title', 'Add City <span class="urdu">(نیا شہر)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('cities.index') }}" class="text-decoration-none">Cities <span class="urdu">(شہر)</span></a></li>
        <li class="breadcrumb-item active">Add City <span class="urdu">(نیا شہر)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-building"></i> Add New City <span class="urdu">(نیا شہر)</span></h4>
    </div>
    <form action="{{ route('cities.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">City Name <span class="urdu">(شہر کا نام)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Province <span class="urdu">(صوبہ)</span></label>
                        <select class="form-select @error('province') is-invalid @enderror" name="province">
                            <option value="">Select Province <span class="urdu">(صوبہ منتخب کریں)</span></option>
                            <option value="Islamabad Capital Territory" {{ old('province') == 'Islamabad Capital Territory' ? 'selected' : '' }}>Islamabad Capital Territory</option>
                            <option value="Punjab" {{ old('province') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Sindh" {{ old('province') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
                            <option value="Khyber Pakhtunkhwa" {{ old('province') == 'Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
                            <option value="Balochistan" {{ old('province') == 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
                            <option value="Gilgit-Baltistan" {{ old('province') == 'Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                            <option value="Azad Jammu & Kashmir" {{ old('province') == 'Azad Jammu & Kashmir' ? 'selected' : '' }}>Azad Jammu & Kashmir</option>
                        </select>
                        @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" name="is_active" value="1" id="is_active" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active <span class="urdu">(فعال)</span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save City <span class="urdu">(شہر محفوظ)</span></button>
            <a href="{{ route('cities.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection
