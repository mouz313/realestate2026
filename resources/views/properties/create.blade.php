@extends('layouts.admin')

@section('title', 'Add Property')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties.index') }}" class="text-decoration-none">Properties <span class="urdu">(جائیدادیں)</span></a></li>
        <li class="breadcrumb-item active">Add Property <span class="urdu">(جائیداد شامل کریں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-building-plus me-1"></i> <span class="urdu">(نئی جائیداد شامل کریں)</span></h4>
    </div>

    {{-- Step indicator --}}
    <div class="wizard-steps px-3 pt-3">
        <div class="ws-item active" data-ind="1"><span class="ws-num">1</span><span class="ws-label">Type <span class="urdu">(نوعیت)</span></span></div>
        <div class="ws-item" data-ind="2"><span class="ws-num">2</span><span class="ws-label">Location & Price <span class="urdu">(مقام و قیمت)</span></span></div>
        <div class="ws-item" data-ind="3"><span class="ws-num">3</span><span class="ws-label">Specs <span class="urdu">(تفصیلات)</span></span></div>
        <div class="ws-item" data-ind="4"><span class="ws-num">4</span><span class="ws-label">Features <span class="urdu">(خصوصیات)</span></span></div>
        <div class="ws-item" data-ind="5"><span class="ws-num">5</span><span class="ws-label">Review <span class="urdu">(جائزہ)</span></span></div>
    </div>

    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if(!empty($prefill['call_log_id']))
        <input type="hidden" name="call_log_id" value="{{ $prefill['call_log_id'] }}">
        @endif

        <div class="card-body">

            {{-- STEP 1: Type & Basics --}}
            <section class="wizard-step" data-step="1">
                <div class="form-section">Property Type <span class="urdu">(جائیداد کی نوعیت)</span></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="urdu">(قسم)</span> <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" id="categoryField" required>
                                <option value=""><span class="urdu">(قسم منتخب کریں)</span></option>
                                @foreach($types as $t)
                                <option value="{{ $t }}" {{ old('category') == $t ? 'selected' : '' }}>{{ \App\Helpers\Status::categoryLabel($t) }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Transaction Type <span class="urdu">(لین دین کی قسم)</span> <span class="text-danger">*</span></label>
                            <select class="form-select @error('transaction_type') is-invalid @enderror" name="transaction_type" required>
                                <option value=""><span class="urdu">(منتخب کریں)</span></option>
                                <option value="sale" {{ old('transaction_type') == 'sale' ? 'selected' : '' }}>Sale <span class="urdu">(فروخت)</span></option>
                                <option value="buy" {{ old('transaction_type') == 'buy' ? 'selected' : '' }}>Buy <span class="urdu">(خریداری)</span></option>
                                <option value="rent" {{ old('transaction_type') == 'rent' ? 'selected' : '' }}>Rent <span class="urdu">(کرایہ)</span></option>
                                <option value="installment" {{ old('transaction_type') == 'installment' ? 'selected' : '' }}>Installment <span class="urdu">(اقساط)</span></option>
                            </select>
                            @error('transaction_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="urdu">(عنوان)</span> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Property Code <span class="urdu">(جائیداد کا کوڈ)</span></label>
                            <input type="text" class="form-control @error('property_code') is-invalid @enderror" name="property_code" value="{{ old('property_code', $autoCode ?? '') }}" readonly>
                            <div class="form-text"><span class="urdu">(خودکار تخلیق شدہ)</span></div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="urdu">(کیفیت)</span> <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available <span class="urdu">(دستیاب)</span></option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented <span class="urdu">(کرایہ پر)</span></option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold <span class="urdu">(فروخت شدہ)</span></option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Commission Rate <span class="urdu">(کمیشن ریٹ)</span> (%)</label>
                            @if(auth()->user()->isAdmin())
                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('commission_rate') is-invalid @enderror" name="commission_rate" value="{{ old('commission_rate') }}" placeholder="e.g. 2">
                                <div class="form-text"><span class="urdu">(ڈیل مکمل ہونے پر ایجنٹ کا کمیشن)</span></div>
                            @else
                                <input type="number" step="0.01" min="0" max="100" class="form-control" name="commission_rate" value="{{ old('commission_rate') }}" disabled>
                                <div class="form-text">Only admins can set the commission rate. <span class="urdu">(صرف ایڈمن کمیشن ریٹ سیٹ کر سکتا ہے)</span></div>
                            @endif
                            @error('commission_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- STEP 2: Location & Pricing --}}
            <section class="wizard-step" data-step="2" style="display:none;">
                <div class="form-section">Location <span class="urdu">(مقام)</span></div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">City <span class="urdu">(شہر)</span></label>
                            <select class="form-select @error('city') is-invalid @enderror" name="city">
                                <option value=""><span class="urdu">(شہر منتخب کریں)</span></option>
                                @foreach($cities as $city)
                                <option value="{{ $city->name }}" {{ old('city') == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Sector / Town <span class="urdu">(سیکٹر / ٹاؤن)</span></label>
                            <input type="text" class="form-control @error('sector_town') is-invalid @enderror" name="sector_town" value="{{ old('sector_town') }}">
                            @error('sector_town') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Block <span class="urdu">(بلاک)</span></label>
                            <input type="text" class="form-control @error('block') is-invalid @enderror" name="block" value="{{ old('block') }}">
                            @error('block') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address <span class="urdu">(پتہ)</span></label>
                    <textarea class="form-control @error('location_address') is-invalid @enderror" name="location_address" rows="2">{{ old('location_address') }}</textarea>
                    @error('location_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Latitude <span class="urdu">(عرض البلد)</span></label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude') }}" placeholder="e.g. 33.6844">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Longitude <span class="urdu">(طول البلد)</span></label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude') }}" placeholder="e.g. 73.0479">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section mt-2">Pricing <span class="urdu">(قیمت)</span></div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Price <span class="urdu">(قیمت)</span> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                            </div>
                            <div class="form-text text-secondary" id="priceWords"></div>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Price Per Sqft <span class="urdu">(قیمت فی مربع فٹ)</span></label>
                            <input type="number" step="0.01" class="form-control @error('price_per_sqft') is-invalid @enderror" name="price_per_sqft" value="{{ old('price_per_sqft') }}">
                            @error('price_per_sqft') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Currency <span class="urdu">(کرنسی)</span></label>
                            <input type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" value="{{ old('currency', 'PKR') }}" maxlength="10">
                            @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Possession Status <span class="urdu">(قبضے کی کیفیت)</span></label>
                            <select class="form-select @error('possession_status') is-invalid @enderror" name="possession_status">
                                <option value="">Select</option>
                                <option value="ready" {{ old('possession_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="under_construction" {{ old('possession_status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                                <option value="off_plan" {{ old('possession_status') == 'off_plan' ? 'selected' : '' }}>Off-Plan</option>
                            </select>
                            @error('possession_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Possession Year <span class="urdu">(قبضے کا سال)</span></label>
                            <select class="form-select @error('possession_year') is-invalid @enderror" name="possession_year">
                                <option value="">Select Year</option>
                                @for($y = date('Y'); $y <= date('Y') + 5; $y++)
                                <option value="{{ $y }}" {{ old('possession_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            @error('possession_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- STEP 3: Specifications (conditional by type) --}}
            <section class="wizard-step" data-step="3" style="display:none;">
                @include('properties.partials.specs')
            </section>

            {{-- STEP 4: Features & Amenities --}}
            <section class="wizard-step" data-step="4" style="display:none;">
                <div class="form-section">Additional Info <span class="urdu">(اضافی معلومات)</span></div>
                <div class="mb-3">
                    <label class="form-label">Features <span class="urdu">(خصوصیات)</span> <span class="text-secondary fw-normal">(comma separated)</span></label>
                    <textarea class="form-control @error('features') is-invalid @enderror" name="features" rows="2">{{ old('features') }}</textarea>
                    @error('features') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Nearby Landmarks <span class="urdu">(قریبی نشانات)</span></label>
                    <textarea class="form-control @error('nearby_landmarks') is-invalid @enderror" name="nearby_landmarks" rows="2">{{ old('nearby_landmarks') }}</textarea>
                    @error('nearby_landmarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="urdu">(وضاحت)</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="form-section">Nearby Places <span class="urdu">(قریبی مقامات)</span></div>
                        <div class="row g-2">
                            @foreach(['Mosque', 'Park', 'Mall', 'School', 'Hospital', 'University', 'Restaurant', 'Market', 'Gym', 'Pharmacy'] as $i => $place)
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('nearby_places') is-invalid @enderror" type="checkbox" role="switch" name="nearby_places[]" value="{{ $place }}" id="np_{{ $i }}" {{ in_array($place, old('nearby_places', [])) ? 'checked' : '' }}>
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
                            @foreach(['Gas', 'Water', 'Electricity', 'Internet', 'Sewage', 'Telephone'] as $i => $utility)
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('utilities') is-invalid @enderror" type="checkbox" role="switch" name="utilities[]" value="{{ $utility }}" id="util_{{ $i }}" {{ in_array($utility, old('utilities', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="util_{{ $i }}">{{ $utility }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('utilities') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="form-section">Community Amenities <span class="urdu">(کمیونٹی سہولیات)</span></div>
                        <div class="row g-2">
                            @php $amenities = ['Swimming Pool', 'Community Gym', 'Lawn / Garden', 'Kids Play Area', 'Barbeque Area', 'Community Centre', 'Mosque', 'Security Staff', 'Maintenance Staff', 'Daycare Centre', 'First Aid Centre', 'Disabled Facilities']; @endphp
                            @foreach($amenities as $i => $amenity)
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="community_amenities[]" value="{{ $amenity }}" id="ca_{{ $i }}" {{ in_array($amenity, old('community_amenities', [])) ? 'checked' : '' }}>
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
                            @php $comms = ['Broadband Internet', 'Cable TV', 'Intercom', 'Business Center', 'Conference Room']; @endphp
                            @foreach($comms as $i => $comm)
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="communication_features[]" value="{{ $comm }}" id="cf_{{ $i }}" {{ in_array($comm, old('communication_features', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cf_{{ $i }}">{{ $comm }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('communication_features') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </section>

            {{-- STEP 5: Media, Assignment & Review --}}
            <section class="wizard-step" data-step="5" style="display:none;">
                <div class="form-section">Media <span class="urdu">(میڈیا)</span></div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Images <span class="urdu">(تصاویر)</span> <span class="text-secondary fw-normal">(up to 20)</span></label>
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
                        <label class="form-label">Video <span class="urdu">(ویڈیو)</span> <span class="text-secondary fw-normal">(optional)</span></label>
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

                <div class="form-section mt-3">Assignment <span class="urdu">(تفویض)</span></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Owner <span class="urdu">(مالک)</span></label>
                            <select class="form-select" name="owner_select" id="owner_select">
                                <option value="">— Select existing —</option>
                                <option value="__new__" @selected(old('owner_name', $prefill['owner_name'] ?? null))>➕ Add new owner <span class="urdu">(نیا مالک)</span></option>
                                @foreach($clients ?? [] as $client)
                                    <option value="{{ $client->id }}" @selected(old('owner_id', $prefill['owner_id'] ?? null) == $client->id)>{{ $client->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="owner_id" id="owner_id" value="{{ old('owner_id', $prefill['owner_id'] ?? '') }}">
                            @error('owner_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <div class="row g-2 mt-2" id="owner_new" style="display:@if(old('owner_name', $prefill['owner_name'] ?? null)) flex @else none @endif;">
                                <div class="col">
                                    <input type="text" class="form-control @error('owner_name') is-invalid @enderror" name="owner_name" placeholder="Owner name" value="{{ old('owner_name', $prefill['owner_name'] ?? '') }}">
                                    @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control @error('owner_phone') is-invalid @enderror" name="owner_phone" placeholder="Owner phone" value="{{ old('owner_phone', $prefill['owner_phone'] ?? '') }}">
                                    @error('owner_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-text">Client is created only when this property is saved.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Agent <span class="urdu">(ایجنٹ)</span></label>
                            <select class="form-select @error('assigned_agent_id') is-invalid @enderror" name="assigned_agent_id">
                                <option value=""><span class="urdu">(ایجنٹ منتخب کریں)</span></option>
                                @foreach($agents ?? [] as $agent)
                                    <option value="{{ $agent->id }}" {{ old('assigned_agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Listed Date <span class="urdu">(تاریخ اجراء)</span></label>
                            <input type="date" class="form-control @error('listed_date') is-invalid @enderror" name="listed_date" value="{{ old('listed_date') }}">
                            @error('listed_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Expiry Date <span class="urdu">(تاریخ میعاد)</span></label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" name="expiry_date" value="{{ old('expiry_date') }}">
                            @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section mt-3">Review <span class="urdu">(حتمی جائزہ)</span></div>
                <div class="review-box border rounded p-3 bg-light" id="reviewBox"></div>
                <div id="reviewErrors" class="text-danger small mt-2"></div>
            </section>

        </div>

        <div class="card-footer d-flex flex-wrap gap-2 justify-content-between">
            <button type="button" class="btn btn-outline-secondary" id="prevBtn" style="display:none;"><i class="ti ti-arrow-left"></i> <span class="urdu">(پچھلا)</span> Previous</button>
            <div class="d-flex gap-2 align-items-center">
                <span id="stepInfo" class="small text-secondary"></span>
                <a href="{{ route('properties.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
                <button type="button" class="btn btn-primary" id="nextBtn">Next <span class="urdu">(اگلا)</span> <i class="ti ti-arrow-right"></i></button>
                <button type="submit" class="btn btn-dark" id="submitBtn" style="display:none;"><i class="ti ti-device-floppy"></i> <span class="urdu">(جائیداد محفوظ کریں)</span> Save Property</button>
            </div>
            <div class="uploading-overlay" id="uploadingOverlay">
                <div class="uploading-spinner"></div>
                <span><span class="urdu">(میڈیا اپ لوڈ ہو رہا ہے...)</span></span>
            </div>
        </div>
    </form>
</div>

<style>
.wizard-steps { display:flex; gap:8px; flex-wrap:wrap; border-bottom:1px solid var(--bs-border-color,#dee2e6); padding-bottom:12px; margin-bottom:8px; }
.ws-item { display:flex; align-items:center; gap:8px; padding:6px 12px; border-radius:30px; background:var(--bs-tertiary-bg,#f1f3f5); color:#6c757d; font-size:.85rem; }
.ws-item .ws-num { width:22px; height:22px; border-radius:50%; background:#ced4da; color:#fff; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:600; }
.ws-item.active { background:rgba(var(--bs-primary-rgb),.12); color:var(--bs-primary); }
.ws-item.active .ws-num { background:var(--bs-primary); }
.ws-item.done .ws-num { background:#198754; }
.ws-item.done { color:#198754; }

.review-box .review-row { display:flex; justify-content:space-between; gap:1rem; padding:5px 0; border-bottom:1px dashed var(--bs-border-color,#dee2e6); font-size:.9rem; }
.review-box .review-label { color:#6c757d; font-weight:600; }
.review-box .review-value { text-align:right; word-break:break-word; }

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
    const form = document.getElementById('categoryField')?.closest('form');
    if (!form) return;

    const steps = Array.from(form.querySelectorAll('.wizard-step'));
    const indicators = Array.from(document.querySelectorAll('.ws-item'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const stepInfo = document.getElementById('stepInfo');
    const reviewBox = document.getElementById('reviewBox');
    const reviewErrors = document.getElementById('reviewErrors');
    let current = 1;
    const TOTAL = steps.length;

    /* ---------- Media drop-zone (unchanged behaviour) ---------- */
    const imageInput = document.getElementById('imageInput');
    const videoInput = document.getElementById('videoInput');
    const imageDropZone = document.getElementById('imageDropZone');
    const videoDropZone = document.getElementById('videoDropZone');
    const imagePreview = document.getElementById('imagePreview');
    const videoName = document.getElementById('videoName');
    const overlay = document.getElementById('uploadingOverlay');
    let selectedFiles = [];

    function setupDropZone(zone, input, isVideo) {
        if (!zone || !input) return;
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
    setupDropZone(imageDropZone, imageInput, false);
    setupDropZone(videoDropZone, videoInput, true);

    /* ---------- Owner toggle ---------- */
    (function bindOwner() {
        const select = document.getElementById('owner_select');
        const hidden = document.getElementById('owner_id');
        const box = document.getElementById('owner_new');
        if (!select) return;
        function apply() {
            if (select.value === '__new__') { hidden.value = ''; box.style.display = 'flex'; }
            else { hidden.value = select.value; box.style.display = 'none'; }
        }
        select.addEventListener('change', apply);
        apply();
    })();

    /* ---------- Type-conditional specs ---------- */
    const categoryField = document.getElementById('categoryField');
    function applyTypeConditional() {
        const cat = categoryField ? categoryField.value : '';
        document.querySelectorAll('[data-cat]').forEach(el => {
            const cats = (el.getAttribute('data-cat') || '').split(' ').filter(Boolean);
            el.style.display = cats.includes(cat) ? '' : 'none';
        });
    }
    if (categoryField) categoryField.addEventListener('change', applyTypeConditional);

    /* ---------- Validation ---------- */
    function markInvalid(el, msg) {
        el.classList.add('is-invalid');
        let wrap = el.closest('.mb-3') || el.parentElement;
        let fb = wrap ? wrap.querySelector('.invalid-feedback.d-block') : null;
        if (!fb && wrap) {
            fb = document.createElement('div');
            fb.className = 'invalid-feedback d-block';
            fb.dataset.client = '1';
            wrap.appendChild(fb);
        }
        if (fb) fb.textContent = msg;
    }
    function clearInvalid(el) {
        el.classList.remove('is-invalid');
        const wrap = el.closest('.mb-3') || el.parentElement;
        const fb = wrap ? wrap.querySelector('.invalid-feedback.d-block[data-client="1"]') : null;
        if (fb) fb.remove();
    }
    function validateStep(step) {
        const sec = steps[step - 1];
        let ok = true, first = null;
        sec.querySelectorAll('input,select,textarea').forEach(el => {
            if (el.disabled || el.type === 'hidden' || el.name === 'call_log_id') return;
            if (el.hasAttribute('required')) {
                const val = (el.type === 'checkbox') ? el.checked : (el.value || '').trim();
                if (!val) {
                    markInvalid(el, 'This field is required.');
                    if (!first) first = el;
                    ok = false;
                } else {
                    clearInvalid(el);
                }
            }
        });
        if (first) first.focus();
        return ok;
    }

    /* ---------- Review ---------- */
    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }
    function fieldVal(name) {
        const el = form.querySelector('[name="'+name+'"]');
        if (!el) return '';
        if (el.tagName === 'SELECT') return el.selectedIndex > -1 ? el.options[el.selectedIndex].text.trim() : '';
        if (el.type === 'checkbox') return el.checked ? 'Yes' : 'No';
        return (el.value || '').trim();
    }
    function checkedLabels(name) {
        const els = form.querySelectorAll('[name="'+name+'"]');
        const out = [];
        els.forEach(e => { if (e.checked) out.push(e.value); });
        return out.join(', ');
    }
    const reviewFields = [
        {n:'title', l:'Title'}, {n:'category', l:'Category'}, {n:'transaction_type', l:'Transaction Type'},
        {n:'status', l:'Status'}, {n:'price', l:'Price', p:'PKR '}, {n:'price_per_sqft', l:'Price/Sqft'},
        {n:'currency', l:'Currency'}, {n:'commission_rate', l:'Commission %'}, {n:'city', l:'City'},
        {n:'sector_town', l:'Sector/Town'}, {n:'block', l:'Block'}, {n:'location_address', l:'Address'},
        {n:'latitude', l:'Latitude'}, {n:'longitude', l:'Longitude'}, {n:'possession_status', l:'Possession'},
        {n:'possession_year', l:'Possession Year'}, {n:'plot_size', l:'Plot Size'}, {n:'plot_size_unit', l:'Unit'},
        {n:'land_area', l:'Land Area'}, {n:'covered_area', l:'Covered Area'}, {n:'covered_area_unit', l:'Covered Unit'},
        {n:'bedrooms', l:'Bedrooms'}, {n:'bathrooms', l:'Bathrooms'}, {n:'kitchens', l:'Kitchens'},
        {n:'floors', l:'Floors'}, {n:'floor_number', l:'Floor #'}, {n:'total_floors', l:'Total Floors'},
        {n:'property_condition', l:'Condition'}, {n:'year_built', l:'Year Built'},
        {n:'furnished_type', l:'Furnished'}, {n:'parking_spaces', l:'Parking'},
        {n:'road_width', l:'Road Width'}, {n:'facing', l:'Facing'}, {n:'owner_name', l:'Owner'},
        {n:'assigned_agent_id', l:'Agent'}, {n:'listed_date', l:'Listed Date'}, {n:'expiry_date', l:'Expiry Date'},
        {n:'description', l:'Description'}, {n:'features', l:'Features'}, {n:'nearby_landmarks', l:'Nearby Landmarks'},
        {n:'notes', l:'Notes'}
    ];
    const reviewChecks = [
        {n:'nearby_places[]', l:'Nearby Places'}, {n:'utilities[]', l:'Utilities'},
        {n:'community_amenities[]', l:'Community Amenities'}, {n:'communication_features[]', l:'Communication Features'},
        {n:'additional_rooms[]', l:'Additional Rooms'}, {n:'building_features[]', l:'Building Features'}
    ];
    function buildReview() {
        let html = '';
        reviewFields.forEach(f => {
            let v = f.c ? fieldVal(f.n) : fieldVal(f.n);
            if (f.p && v) v = f.p + v;
            if (!v || v === 'No' && f.c) return;
            if (v === '') return;
            html += '<div class="review-row"><span class="review-label">'+f.l+'</span><span class="review-value">'+escapeHtml(v)+'</span></div>';
        });
        reviewChecks.forEach(c => {
            const v = checkedLabels(c.n);
            if (v) html += '<div class="review-row"><span class="review-label">'+c.l+'</span><span class="review-value">'+escapeHtml(v)+'</span></div>';
        });
        const imgs = selectedFiles.length;
        const vid = (videoInput && videoInput.files.length) ? '1 video' : '—';
        html += '<div class="review-row"><span class="review-label">Media</span><span class="review-value">'+imgs+' images, '+vid+'</span></div>';
        reviewBox.innerHTML = html || '<p class="text-secondary mb-0">No details entered yet.</p>';
    }

    /* ---------- Step navigation ---------- */
    function showStep(n) {
        current = n;
        steps.forEach(s => s.style.display = (Number(s.dataset.step) === n) ? '' : 'none');
        indicators.forEach(ind => {
            const i = Number(ind.dataset.ind);
            ind.classList.toggle('active', i === n);
            ind.classList.toggle('done', i < n);
        });
        prevBtn.style.display = n === 1 ? 'none' : '';
        nextBtn.style.display = n === TOTAL ? 'none' : '';
        submitBtn.style.display = n === TOTAL ? '' : 'none';
        stepInfo.textContent = 'Step ' + n + ' of ' + TOTAL;
        if (n === TOTAL) { buildReview(); reviewErrors.textContent = ''; }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function validateAll() {
        for (let s = 1; s <= TOTAL; s++) {
            if (!validateStep(s)) return s;
        }
        return 0;
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(current)) showStep(current + 1);
    });
    prevBtn.addEventListener('click', () => showStep(current - 1));

    form.addEventListener('submit', function(e) {
        const bad = validateAll();
        if (bad) {
            e.preventDefault();
            showStep(bad);
            reviewErrors.textContent = 'Please complete the required fields in step ' + bad + '.';
            return;
        }
        if (selectedFiles.length || (videoInput && videoInput.files.length)) {
            overlay.classList.add('active');
            submitBtn.disabled = true;
        }
    });

    /* ---------- Live price words (e.g. "≈ 1 Million (1,000,000)") ---------- */
    const priceInput = form.querySelector('[name="price"]');
    const priceWords = document.getElementById('priceWords');
    function fmtPriceWords(n) {
        if (!n || isNaN(n)) return '';
        const abs = Math.abs(n);
        let w;
        if (abs >= 1e9) w = (n / 1e9).toFixed(2).replace(/\.?0+$/, '') + ' Billion';
        else if (abs >= 1e7) w = (n / 1e7).toFixed(2).replace(/\.?0+$/, '') + ' Crore';
        else if (abs >= 1e6) w = (n / 1e6).toFixed(2).replace(/\.?0+$/, '') + ' Million';
        else if (abs >= 1e5) w = (n / 1e5).toFixed(2).replace(/\.?0+$/, '') + ' Lac';
        else if (abs >= 1e3) w = (n / 1e3).toFixed(2).replace(/\.?0+$/, '') + ' Thousand';
        else w = n.toString();
        const grouped = Number(n).toLocaleString('en-US');
        return '≈ ' + w + '  (' + grouped + ')';
    }
    if (priceInput && priceWords) {
        priceInput.addEventListener('input', () => { priceWords.textContent = fmtPriceWords(priceInput.value); });
        priceWords.textContent = fmtPriceWords(priceInput.value);
    }

    /* ---------- Init ---------- */
    applyTypeConditional();
    // If server returned validation errors, jump to the first step containing an invalid field.
    const firstInvalid = form.querySelector('.is-invalid');
    if (firstInvalid) {
        const sec = firstInvalid.closest('.wizard-step');
        if (sec) showStep(Number(sec.dataset.step));
        else showStep(1);
    } else {
        showStep(1);
    }
})();
</script>
@endpush
@endsection
