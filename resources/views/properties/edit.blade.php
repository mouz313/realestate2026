@extends('layouts.admin')

@section('title', 'Edit Property <span class="urdu">(جائیداد میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties.index') }}" class="text-decoration-none">Properties <span class="urdu">(جائیدادیں)</span></a></li>
        <li class="breadcrumb-item active">Edit Property <span class="urdu">(جائیداد میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-building me-1"></i> <span class="urdu">(جائیداد میں ترمیم کریں)</span></h4>
    </div>
    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="urdu">(عنوان)</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $property->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property Code <span class="urdu">(جائیداد کا کوڈ)</span></label>
                        <input type="text" class="form-control @error('property_code') is-invalid @enderror" name="property_code" value="{{ old('property_code', $property->property_code) }}" {{ $property->property_code ? 'readonly' : '' }}>
                        @error('property_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="urdu">(قسم)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                            <option value=""><span class="urdu">(قسم منتخب کریں)</span></option>
                            <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>House</option>
                            <option value="flat" {{ old('type', $property->type) == 'flat' ? 'selected' : '' }}>Flat</option>
                            <option value="plot" {{ old('type', $property->type) == 'plot' ? 'selected' : '' }}>Plot</option>
                            <option value="commercial" {{ old('type', $property->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="farmhouse" {{ old('type', $property->type) == 'farmhouse' ? 'selected' : '' }}>Farmhouse</option>
                            <option value="penthouse" {{ old('type', $property->type) == 'penthouse' ? 'selected' : '' }}>Penthouse</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction Type <span class="urdu">(لین دین کی قسم)</span></label>
                        <select class="form-select @error('transaction_type') is-invalid @enderror" name="transaction_type">
                            <option value="">Select</option>
                            <option value="sale" {{ old('transaction_type', $property->transaction_type) == 'sale' ? 'selected' : '' }}>Sale</option>
                            <option value="rent" {{ old('transaction_type', $property->transaction_type) == 'rent' ? 'selected' : '' }}>Rent</option>
                            <option value="lease" {{ old('transaction_type', $property->transaction_type) == 'lease' ? 'selected' : '' }}>Lease</option>
                        </select>
                        @error('transaction_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="under_offer" {{ old('status', $property->status) == 'under_offer' ? 'selected' : '' }}>Under Offer</option>
                            <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="under_construction" {{ old('status', $property->status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                            <option value="off_market" {{ old('status', $property->status) == 'off_market' ? 'selected' : '' }}>Off Market</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price <span class="urdu">(قیمت)</span></label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $property->price) }}">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price Per Sqft <span class="urdu">(قیمت فی مربع فٹ)</span></label>
                        <input type="number" step="0.01" class="form-control @error('price_per_sqft') is-invalid @enderror" name="price_per_sqft" value="{{ old('price_per_sqft', $property->price_per_sqft) }}">
                        @error('price_per_sqft') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Possession Status <span class="urdu">(قبضے کی کیفیت)</span></label>
                        <select class="form-select @error('possession_status') is-invalid @enderror" name="possession_status">
                            <option value="">Select</option>
                            <option value="ready" {{ old('possession_status', $property->possession_status) == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="under_construction" {{ old('possession_status', $property->possession_status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                            <option value="off_plan" {{ old('possession_status', $property->possession_status) == 'off_plan' ? 'selected' : '' }}>Off-Plan</option>
                        </select>
                        @error('possession_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Possession Year <span class="urdu">(قبضے کا سال)</span></label>
                        <select class="form-select @error('possession_year') is-invalid @enderror" name="possession_year">
                            <option value="">Select Year</option>
                            @for($y = date('Y'); $y <= date('Y') + 5; $y++)
                            <option value="{{ $y }}" {{ old('possession_year', $property->possession_year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('possession_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Currency <span class="urdu">(کرنسی)</span></label>
                        <input type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" value="{{ old('currency', $property->currency ?? 'PKR') }}" maxlength="10">
                        @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City <span class="urdu">(شہر)</span></label>
                        <select class="form-select @error('city') is-invalid @enderror" name="city">
                            <option value=""><span class="urdu">(شہر منتخب کریں)</span></option>
                            @foreach($cities as $city)
                            <option value="{{ $city->name }}" {{ old('city', $property->city) == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sector / Town <span class="urdu">(سیکٹر / ٹاؤن)</span></label>
                        <input type="text" class="form-control @error('sector_town') is-invalid @enderror" name="sector_town" value="{{ old('sector_town', $property->sector_town) }}">
                        @error('sector_town') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Block <span class="urdu">(بلاک)</span></label>
                        <input type="text" class="form-control @error('block') is-invalid @enderror" name="block" value="{{ old('block', $property->block) }}">
                        @error('block') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location Address <span class="urdu">(مقام کا پتہ)</span></label>
                        <textarea class="form-control @error('location_address') is-invalid @enderror" name="location_address" rows="2">{{ old('location_address', $property->location_address) }}</textarea>
                        @error('location_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Plot Size <span class="urdu">(پلاٹ کا سائز)</span></label>
                        <input type="number" step="0.01" class="form-control @error('plot_size') is-invalid @enderror" name="plot_size" value="{{ old('plot_size', $property->plot_size) }}">
                        @error('plot_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plot Size Unit <span class="urdu">(پلاٹ سائز یونٹ)</span></label>
                        <select class="form-select @error('plot_size_unit') is-invalid @enderror" name="plot_size_unit">
                            <option value="">Select Unit</option>
                            <option value="marla" {{ old('plot_size_unit', $property->plot_size_unit) == 'marla' ? 'selected' : '' }}>Marla</option>
                            <option value="kanal" {{ old('plot_size_unit', $property->plot_size_unit) == 'kanal' ? 'selected' : '' }}>Kanal</option>
                            <option value="sqft" {{ old('plot_size_unit', $property->plot_size_unit) == 'sqft' ? 'selected' : '' }}>Sq. Ft.</option>
                            <option value="sqm" {{ old('plot_size_unit', $property->plot_size_unit) == 'sqm' ? 'selected' : '' }}>Sq. M.</option>
                            <option value="acre" {{ old('plot_size_unit', $property->plot_size_unit) == 'acre' ? 'selected' : '' }}>Acre</option>
                        </select>
                        @error('plot_size_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Land Area <span class="urdu">(رقبہ)</span></label>
                        <input type="text" class="form-control @error('land_area') is-invalid @enderror" name="land_area" value="{{ old('land_area', $property->land_area) }}">
                        @error('land_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Covered Area <span class="urdu">(تعمیر شدہ رقبہ)</span> <span class="text-secondary small">(built-up)</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" class="form-control @error('covered_area') is-invalid @enderror" name="covered_area" value="{{ old('covered_area', $property->covered_area) }}" placeholder="Size">
                            </div>
                            <div class="col-6">
                                <select class="form-select @error('covered_area_unit') is-invalid @enderror" name="covered_area_unit">
                                    <option value="">Unit</option>
                                    <option value="sqft" {{ old('covered_area_unit', $property->covered_area_unit) == 'sqft' ? 'selected' : '' }}>Sq. Ft.</option>
                                    <option value="sqm" {{ old('covered_area_unit', $property->covered_area_unit) == 'sqm' ? 'selected' : '' }}>Sq. M.</option>
                                    <option value="marla" {{ old('covered_area_unit', $property->covered_area_unit) == 'marla' ? 'selected' : '' }}>Marla</option>
                                </select>
                            </div>
                        </div>
                        @error('covered_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @error('covered_area_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Bedrooms <span class="urdu">(بیڈروم)</span></label>
                                <input type="number" min="0" class="form-control @error('bedrooms') is-invalid @enderror" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}">
                                @error('bedrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Bathrooms <span class="urdu">(باتھ روم)</span></label>
                                <input type="number" min="0" class="form-control @error('bathrooms') is-invalid @enderror" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}">
                                @error('bathrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Kitchens <span class="urdu">(کچن)</span></label>
                                <input type="number" min="0" class="form-control @error('kitchens') is-invalid @enderror" name="kitchens" value="{{ old('kitchens', $property->kitchens) }}">
                                @error('kitchens') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Floors <span class="urdu">(منزلیں)</span> <span class="text-secondary small">(in unit)</span></label>
                                <input type="number" min="0" class="form-control @error('floors') is-invalid @enderror" name="floors" value="{{ old('floors', $property->floors) }}">
                                @error('floors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Floor # <span class="urdu">(منزل نمبر)</span></label>
                                <input type="number" min="0" class="form-control @error('floor_number') is-invalid @enderror" name="floor_number" value="{{ old('floor_number', $property->floor_number) }}" placeholder="e.g. 3">
                                @error('floor_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Total Floors in Building <span class="urdu">(عمارت میں کل منزلیں)</span></label>
                                <input type="number" min="0" class="form-control @error('total_floors') is-invalid @enderror" name="total_floors" value="{{ old('total_floors', $property->total_floors) }}" placeholder="e.g. 15">
                                @error('total_floors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('furnished') is-invalid @enderror" name="furnished" value="1" id="furnished" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                            <label class="form-check-label" for="furnished">Furnished <span class="urdu">(فرنشڈ)</span></label>
                            @error('furnished') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parking Spaces <span class="urdu">(پارکنگ کی جگہیں)</span></label>
                        <input type="number" min="0" class="form-control @error('parking_spaces') is-invalid @enderror" name="parking_spaces" value="{{ old('parking_spaces', $property->parking_spaces) }}">
                        @error('parking_spaces') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Features <span class="urdu">(خصوصیات)</span> <span class="text-secondary small">(comma separated)</span></label>
                        <textarea class="form-control @error('features') is-invalid @enderror" name="features" rows="2">{{ old('features', is_array($property->features) ? implode(', ', $property->features) : $property->features) }}</textarea>
                        @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nearby Landmarks <span class="urdu">(قریبی نشانات)</span></label>
                        <textarea class="form-control @error('nearby_landmarks') is-invalid @enderror" name="nearby_landmarks" rows="2">{{ old('nearby_landmarks', $property->nearby_landmarks) }}</textarea>
                        @error('nearby_landmarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="form-section">Nearby Places <span class="urdu">(قریبی مقامات)</span></div>
                            <div class="row g-2">
                                @php $selectedPlaces = old('nearby_places', $property->nearby_places ?? []); @endphp
                                @foreach(['Mosque', 'Park', 'Mall', 'School', 'Hospital', 'University', 'Restaurant', 'Market', 'Gym', 'Pharmacy'] as $i => $place)
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('nearby_places') is-invalid @enderror" type="checkbox" role="switch" name="nearby_places[]" value="{{ $place }}" id="np_{{ $i }}" {{ in_array($place, $selectedPlaces) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="np_{{ $i }}">{{ $place }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('nearby_places') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-section">Utilities <span class="urdu">(یوٹیلیٹیز)</span></div>
                            <div class="row g-2">
                                @php $selectedUtils = old('utilities', $property->utilities ?? []); @endphp
                                @foreach(['Gas', 'Water', 'Electricity', 'Internet', 'Sewage', 'Telephone'] as $i => $utility)
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('utilities') is-invalid @enderror" type="checkbox" role="switch" name="utilities[]" value="{{ $utility }}" id="util_{{ $i }}" {{ in_array($utility, $selectedUtils) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="util_{{ $i }}">{{ $utility }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('utilities') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="urdu">(وضاحت)</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $property->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Owner <span class="urdu">(مالک)</span></label>
                        <select class="form-select @error('owner_id') is-invalid @enderror" name="owner_id">
                            <option value=""><span class="urdu">(مالک منتخب کریں)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('owner_id', $property->owner_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('owner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned Agent <span class="urdu">(مقرر کردہ ایجنٹ)</span></label>
                        <select class="form-select @error('assigned_agent_id') is-invalid @enderror" name="assigned_agent_id">
                            <option value=""><span class="urdu">(ایجنٹ منتخب کریں)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_agent_id', $property->assigned_agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Listed Date <span class="urdu">(تاریخ اجراء)</span></label>
                                <input type="date" class="form-control @error('listed_date') is-invalid @enderror" name="listed_date" value="{{ old('listed_date', $property->listed_date ? $property->listed_date->format('Y-m-d') : '') }}">
                                @error('listed_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date <span class="urdu">(تاریخ میعاد)</span></label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" name="expiry_date" value="{{ old('expiry_date', $property->expiry_date ? $property->expiry_date->format('Y-m-d') : '') }}">
                                @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $property->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-section">Additional Rooms <span class="urdu">(اضافی کمرے)</span></div>
                    <div class="row g-2">
                        @php $selectedRooms = old('additional_rooms', $property->additional_rooms ?? []); @endphp
                        @foreach(['Servant Quarters', 'Drawing Room', 'Dining Room', 'Study Room', 'Prayer Room', 'Powder Room', 'Lounge', 'Laundry Room', 'Store Rooms', 'Steam Room'] as $i => $room)
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="additional_rooms[]" value="{{ $room }}" id="ar_{{ $i }}" {{ in_array($room, $selectedRooms) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ar_{{ $i }}">{{ $room }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('additional_rooms') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-section">Building Features <span class="urdu">(عمارت کی خصوصیات)</span></div>
                    <div class="row g-2">
                        @php $selectedBf = old('building_features', $property->building_features ?? []); @endphp
                        @foreach(['Elevator', 'Lobby', 'Double Glazed Windows', 'Central AC', 'Central Heating', 'Flooring', 'Electricity Backup', 'Waste Disposal'] as $i => $feat)
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="building_features[]" value="{{ $feat }}" id="bf_{{ $i }}" {{ in_array($feat, $selectedBf) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bf_{{ $i }}">{{ $feat }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('building_features') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-section">Community Amenities <span class="urdu">(کمیونٹی سہولیات)</span></div>
                    <div class="row g-2">
                        @php $selectedCa = old('community_amenities', $property->community_amenities ?? []); @endphp
                        @foreach(['Swimming Pool', 'Community Gym', 'Lawn / Garden', 'Kids Play Area', 'Barbeque Area', 'Community Centre', 'Mosque', 'Security Staff', 'Maintenance Staff', 'Daycare Centre', 'First Aid Centre', 'Disabled Facilities'] as $i => $amenity)
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="community_amenities[]" value="{{ $amenity }}" id="ca_{{ $i }}" {{ in_array($amenity, $selectedCa) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ca_{{ $i }}">{{ $amenity }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('community_amenities') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-section">Communication Features <span class="urdu">(مواصلاتی خصوصیات)</span></div>
                    <div class="row g-2">
                        @php $selectedCf = old('communication_features', $property->communication_features ?? []); @endphp
                        @foreach(['Broadband Internet', 'Cable TV', 'Intercom', 'Business Center', 'Conference Room'] as $i => $comm)
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="communication_features[]" value="{{ $comm }}" id="cf_{{ $i }}" {{ in_array($comm, $selectedCf) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cf_{{ $i }}">{{ $comm }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('communication_features') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-12">
                    <div class="form-section">Media <span class="urdu">(میڈیا)</span></div>

                    @if($property->media->count())
                    <div class="row g-2 mb-3">
                        @foreach($property->media as $media)
                        <div class="col-md-2 col-4">
                            <div class="position-relative">
                                @if($media->type === 'video')
                                <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:120px;">
                                    <i class="ti ti-video fs-1 text-secondary"></i>
                                </div>
                                @else
                                <img src="{{ Storage::url($media->file_path) }}" class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;">
                                @endif
                                <div class="mt-1 d-flex gap-1">
                                    @if($media->type === 'image' && !$media->is_primary)
                                    <form action="{{ route('properties.media.primary', $media) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary" title="Set as primary">⭐</button>
                                    </form>
                                    @endif
                                    @if($media->is_primary)
                                    <span class="badge bg-warning text-dark" style="font-size:0.6rem;">Primary</span>
                                    @endif
                                    <form action="{{ route('properties.media.destroy', $media) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Delete this media?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Add More Images <span class="urdu">(مزید تصاویر شامل کریں)</span> <span class="text-secondary fw-normal">(up to 20 total)</span></label>
                            <div class="drop-zone" id="imageDropZone">
                                <div class="drop-zone-content">
                                    <i class="ti ti-cloud-upload fs-1 text-secondary"></i>
                                    <p class="mb-1 fw-medium"><span class="urdu">(تصاویر یہاں گھسیٹیں اور چھوڑیں)</span></p>
                                    <small class="text-secondary"><span class="urdu">(یا کلک کریں براؤز کرنے کے لیے)</span></small>
                                </div>
                                <input type="file" class="drop-zone-input @error('images.*') is-invalid @enderror @error('images') is-invalid @enderror" name="images[]" multiple accept="image/*" id="imageInput">
                            </div>
                            <div class="preview-grid" id="imagePreview"></div>
                            <div class="form-text"><span class="urdu">(قبول شدہ)</span>: JPG, PNG, WebP. <span class="urdu">(زیادہ سے زیادہ 5MB ہر ایک)</span></div>
                            @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Add Video <span class="urdu">(ویڈیو شامل کریں)</span> <span class="text-secondary fw-normal">(optional)</span></label>
                            <div class="drop-zone" id="videoDropZone">
                                <div class="drop-zone-content">
                                    <i class="ti ti-video fs-1 text-secondary"></i>
                                    <p class="mb-1 fw-medium"><span class="urdu">(ویڈیو یہاں کلک کریں یا گھسیٹیں)</span></p>
                                    <small class="text-secondary">MP4, WebM. <span class="urdu">(زیادہ سے زیادہ 50MB)</span></small>
                                </div>
                                <input type="file" class="drop-zone-input @error('video') is-invalid @enderror" name="video" accept="video/*" id="videoInput">
                            </div>
                            <div id="videoName" class="mt-2 small text-secondary"></div>
                            @error('video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="uploading-overlay" id="uploadingOverlay">
                <div class="uploading-spinner"></div>
                <span><span class="urdu">(میڈیا اپ لوڈ ہو رہا ہے...)</span></span>
            </div>
            <button type="submit" class="btn btn-dark" id="submitBtn"><i class="ti ti-device-floppy"></i> <span class="urdu">(جائیداد اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('properties.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>

<style>
.drop-zone {
    border: 2px dashed var(--bs-border-color, #dee2e6);
    border-radius: 8px;
    padding: 2rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all .25s;
    position: relative;
    background: var(--bs-body-bg, #fff);
}
.drop-zone:hover, .drop-zone.drag-over {
    border-color: var(--bs-primary);
    background: rgba(var(--bs-primary-rgb), .04);
}
.drop-zone-input {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.preview-grid {
    display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px;
}
.preview-item {
    width: calc(20% - 8px); aspect-ratio: 1; border-radius: 6px;
    overflow: hidden; position: relative;
    border: 1px solid var(--bs-border-color, #dee2e6);
    animation: fadeIn .3s ease;
}
.preview-item img {
    width: 100%; height: 100%; object-fit: cover;
}
.preview-item .remove-btn {
    position: absolute; top: 3px; right: 3px;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(0,0,0,.55); color: #fff;
    border: none; font-size: 14px; line-height: 22px;
    text-align: center; cursor: pointer; z-index: 2;
}
.uploading-overlay {
    display: none; align-items: center; gap: 10px;
    padding: 8px 16px; background: var(--bs-primary);
    color: #fff; border-radius: 6px; font-size: .85rem;
    margin-bottom: 10px;
}
.uploading-overlay.active { display: flex; }
.uploading-spinner {
    width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.3);
    border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
@keyframes fadeIn { from { opacity: 0; transform: scale(.9); } to { opacity: 1; transform: scale(1); } }
</style>

@push('scripts')
<script>
(function() {
    const imageInput = document.getElementById('imageInput');
    const videoInput = document.getElementById('videoInput');
    const imageDropZone = document.getElementById('imageDropZone');
    const videoDropZone = document.getElementById('videoDropZone');
    const imagePreview = document.getElementById('imagePreview');
    const videoName = document.getElementById('videoName');
    const form = imageInput?.closest('form');
    const overlay = document.getElementById('uploadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    if (!imageInput) return;

    let selectedFiles = [];

    function setupDropZone(zone, input, isVideo) {
        zone.addEventListener('click', () => input.click());
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            if (isVideo) {
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    const f = e.dataTransfer.files[0];
                    videoName.textContent = f.name + ' (' + (f.size / 1024 / 1024).toFixed(1) + ' MB)';
                }
            } else {
                handleFiles(e.dataTransfer.files);
            }
        });
        input.addEventListener('change', () => {
            if (isVideo) {
                const f = input.files[0];
                videoName.textContent = f ? f.name + ' (' + (f.size / 1024 / 1024).toFixed(1) + ' MB)' : '';
            } else {
                handleFiles(input.files);
            }
        });
    }

    setupDropZone(imageDropZone, imageInput, false);
    setupDropZone(videoDropZone, videoInput, true);

    function handleFiles(files) {
        const remaining = 20 - selectedFiles.length;
        let count = 0;
        for (const file of files) {
            if (!file.type.startsWith('image/')) continue;
            if (count >= remaining) break;
            selectedFiles.push(file);
            count++;
        }
        renderPreviews();
        syncInput();
    }

    function renderPreviews() {
        imagePreview.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = '<img src="'+e.target.result+'" alt="Preview"><button type="button" class="remove-btn" data-idx="'+i+'">&times;</button>';
                div.querySelector('.remove-btn').addEventListener('click', () => {
                    selectedFiles.splice(i, 1);
                    renderPreviews();
                    syncInput();
                });
                imagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        imageInput.files = dt.files;
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length || (videoInput && videoInput.files.length)) {
                overlay.classList.add('active');
                submitBtn.disabled = true;
            }
        });
    }
})();
</script>
@endpush
@endsection
