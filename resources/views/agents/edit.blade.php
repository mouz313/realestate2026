@extends('layouts.admin')

@section('title', 'Edit Agent')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('agents.index') }}" class="text-decoration-none">Agents</a></li>
        <li class="breadcrumb-item active">Edit Agent</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="ti ti-user"></i> Edit Agent</h4>
    </div>
    <form action="{{ route('agents.update', $agent) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $agent->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role / Designation</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role', $agent->role) }}" placeholder="e.g. CEO &amp; Founder, Senior Agent">
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $agent->phone) }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ old('whatsapp', $agent->whatsapp) }}" placeholder="e.g. 923001234567">
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $agent->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CNIC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cnic') is-invalid @enderror" name="cnic" value="{{ old('cnic', $agent->cnic) }}" required>
                        @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commission Rate (%)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('commission_rate') is-invalid @enderror" name="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}">
                        @error('commission_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type">
                            <option value="">Select Type</option>
                            <option value="in_house" {{ old('type', $agent->type) == 'in_house' ? 'selected' : '' }}>In House</option>
                            <option value="freelance" {{ old('type', $agent->type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="partner" {{ old('type', $agent->type) == 'partner' ? 'selected' : '' }}>Partner</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" min="0" max="100" class="form-control @error('experience_years') is-invalid @enderror" name="experience_years" value="{{ old('experience_years', $agent->experience_years) }}" placeholder="e.g. 5">
                        @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Languages</label>
                        <input type="text" class="form-control @error('languages') is-invalid @enderror" name="languages" value="{{ old('languages', $agent->languages) }}" placeholder="e.g. Urdu, English, Punjabi">
                        @error('languages') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
                        <small class="text-secondary">Optional. Leave empty to keep current photo.</small>
                        @if($agent->photo)
                        <div class="mt-2"><img src="{{ Storage::url($agent->photo) }}" alt="" style="height:60px;width:60px;object-fit:cover;border-radius:8px;"></div>
                        @endif
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Join Date</label>
                        <input type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" value="{{ old('join_date', $agent->join_date ? $agent->join_date->format('Y-m-d') : '') }}">
                        @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $agent->address) }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">License Number</label>
                        <input type="text" class="form-control @error('license_number') is-invalid @enderror" name="license_number" value="{{ old('license_number', $agent->license_number) }}">
                        @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio / About</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3" placeholder="Short biography about the agent">{{ old('bio', $agent->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specializations</label>
                        @php $specs = ['Residential', 'Commercial', 'Rental', 'Luxury', 'Agricultural', 'Industrial', 'Land/Plots']; @endphp
                        @php $selectedSpecs = old('specializations', is_array($agent->specializations) ? $agent->specializations : (json_decode($agent->specializations, true) ?? [])); @endphp
                        <div class="d-flex flex-wrap gap-3 mt-1">
                            @foreach($specs as $s)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="specializations[]" value="{{ $s }}" id="spec-{{ $loop->index }}" {{ in_array($s, $selectedSpecs) ? 'checked' : '' }}>
                                <label class="form-check-label" for="spec-{{ $loop->index }}">{{ $s }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('specializations') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website', $agent->website) }}" placeholder="https://">
                        @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $agent->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <hr>
            <h5 class="mb-3"><i class="ti ti-brand-social"></i> Social Media</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-brand-facebook text-primary"></i> Facebook</label>
                        <input type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook', $agent->facebook) }}" placeholder="https://facebook.com/...">
                        @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-brand-twitter"></i> Twitter / X</label>
                        <input type="url" class="form-control @error('twitter') is-invalid @enderror" name="twitter" value="{{ old('twitter', $agent->twitter) }}" placeholder="https://twitter.com/...">
                        @error('twitter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-brand-linkedin text-primary"></i> LinkedIn</label>
                        <input type="url" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin', $agent->linkedin) }}" placeholder="https://linkedin.com/in/...">
                        @error('linkedin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-brand-instagram text-danger"></i> Instagram</label>
                        <input type="url" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram', $agent->instagram) }}" placeholder="https://instagram.com/...">
                        @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Agent</button>
            <a href="{{ route('agents.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </div>
    </form>
</div>
@endsection
