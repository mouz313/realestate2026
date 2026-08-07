@csrf
<div class="row g-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Package Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $package->name ?? '') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $package->slug ?? '') }}" required>
            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Price <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">{{ $package->currency ?? 'PKR' }}</span>
                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $package->price ?? '') }}" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-text">Set price to 0 for a free/trial package.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $package->description ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Currency <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="currency" value="{{ old('currency', $package->currency ?? 'PKR') }}" maxlength="10">
        </div>
        <div class="mb-3">
            <label class="form-label">Billing Interval <span class="text-danger">*</span></label>
            <select class="form-select" name="interval">
                <option value="month" {{ (old('interval', $package->interval ?? '') === 'month') ? 'selected' : '' }}>Monthly</option>
                <option value="year" {{ (old('interval', $package->interval ?? '') === 'year') ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Trial Days</label>
            <input type="number" min="0" class="form-control" name="trial_days" value="{{ old('trial_days', $package->trial_days ?? 0) }}">
            <div class="form-text">Days of free trial before billing.</div>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ (old('is_active', $package->is_active ?? true)) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (available for purchase)</label>
            </div>
        </div>
    </div>
</div>

<h5 class="mt-2 mb-2">Limits <span class="text-secondary small">(0 = Unlimited)</span></h5>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Max Employees</label>
        <input type="number" min="0" class="form-control @error('max_employees') is-invalid @enderror" name="max_employees" value="{{ old('max_employees', $package->max_employees ?? 0) }}">
        @error('max_employees') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Max Clients</label>
        <input type="number" min="0" class="form-control @error('max_clients') is-invalid @enderror" name="max_clients" value="{{ old('max_clients', $package->max_clients ?? 0) }}">
        @error('max_clients') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Max Properties</label>
        <input type="number" min="0" class="form-control @error('max_properties') is-invalid @enderror" name="max_properties" value="{{ old('max_properties', $package->max_properties ?? 0) }}">
        @error('max_properties') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
