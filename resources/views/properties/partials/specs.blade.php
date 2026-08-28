<?php
/* Shared type-aware property specification groups.
   Works in both create (no $property) and edit (has $property). */
$v = function ($field, $default = null) {
    $o = old($field);
    if ($o !== null && $o !== '') {
        return $o;
    }
    if (isset($property)) {
        $val = $property->{$field} ?? null;
        return $val !== null ? $val : $default;
    }
    return $default;
};
$isSel = function ($field, $val) use ($v) {
    return $v($field) == $val ? 'selected' : '';
};
$isChk = function ($field, $val) use ($v) {
    $cur = $v($field);
    if (is_array($cur)) {
        return in_array($val, $cur, true) ? 'checked' : '';
    }
    return (string) $cur === (string) $val ? 'checked' : '';
};
?>

{{-- ============ BUILT TYPES (house, flat, studio_apartment, farmhouse, office, shop) ============ --}}
<div data-cat="built">
    <div class="form-section">Condition & Age <span class="urdu">(حالت اور عمر)</span></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">New or Resale <span class="urdu">(نیا یا پرانا)</span></label>
                <select class="form-select @error('property_condition') is-invalid @enderror" name="property_condition">
                    <option value=""><span class="urdu">(منتخب کریں)</span></option>
                    <option value="new" {{ $isSel('property_condition', 'new') }}>New <span class="urdu">(نیا)</span></option>
                    <option value="resale" {{ $isSel('property_condition', 'resale') }}>Resale / Old <span class="urdu">(پرانا)</span></option>
                </select>
                @error('property_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Year Built <span class="urdu">(تعمیر کا سال)</span></label>
                <input type="number" min="1900" max="2100" class="form-control @error('year_built') is-invalid @enderror" name="year_built" value="{{ $v('year_built') }}">
                @error('year_built') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="form-section mt-2">Furnishing <span class="urdu">(فرنشنگ)</span></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Furnished <span class="urdu">(فرنشڈ)</span></label>
                <select class="form-select @error('furnished_type') is-invalid @enderror" name="furnished_type">
                    <option value=""><span class="urdu">(منتخب کریں)</span></option>
                    <option value="furnished" {{ $isSel('furnished_type', 'furnished') }}>Furnished <span class="urdu">(فرنشڈ)</span></option>
                    <option value="semi_furnished" {{ $isSel('furnished_type', 'semi_furnished') }}>Semi-Furnished <span class="urdu">(نیم فرنشڈ)</span></option>
                    <option value="unfurnished" {{ $isSel('furnished_type', 'unfurnished') }}>Unfurnished <span class="urdu">(غیر فرنشڈ)</span></option>
                </select>
                @error('furnished_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Covered Area <span class="urdu">(تعمیر شدہ رقبہ)</span> <span class="text-secondary fw-normal">(built-up)</span></label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" step="0.01" class="form-control @error('covered_area') is-invalid @enderror" name="covered_area" value="{{ $v('covered_area') }}" placeholder="Size">
                        @error('covered_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <select class="form-select @error('covered_area_unit') is-invalid @enderror" name="covered_area_unit">
                            <option value="">Unit</option>
                            <option value="sqft" {{ $isSel('covered_area_unit', 'sqft') }}>Sq. Ft.</option>
                            <option value="sqm" {{ $isSel('covered_area_unit', 'sqm') }}>Sq. M.</option>
                            <option value="marla" {{ $isSel('covered_area_unit', 'marla') }}>Marla</option>
                        </select>
                        @error('covered_area_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Floors <span class="urdu">(منزلیں)</span> <span class="text-secondary fw-normal">(in unit)</span></label>
                <input type="number" min="0" class="form-control @error('floors') is-invalid @enderror" name="floors" value="{{ $v('floors') }}">
                @error('floors') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Floor # <span class="urdu">(منزل نمبر)</span></label>
                <input type="number" min="0" class="form-control @error('floor_number') is-invalid @enderror" name="floor_number" value="{{ $v('floor_number') }}" placeholder="e.g. 3">
                @error('floor_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Total Floors <span class="urdu">(عمارت میں کل منزلیں)</span></label>
                <input type="number" min="0" class="form-control @error('total_floors') is-invalid @enderror" name="total_floors" value="{{ $v('total_floors') }}" placeholder="e.g. 15">
                @error('total_floors') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Parking Spaces <span class="urdu">(پارکنگ کی جگہیں)</span></label>
                <input type="number" min="0" class="form-control @error('parking_spaces') is-invalid @enderror" name="parking_spaces" value="{{ $v('parking_spaces') }}">
                @error('parking_spaces') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- Residential only: house, flat, studio_apartment, farmhouse --}}
    <div data-cat="residential">
        <div class="form-section mt-2">Rooms <span class="urdu">(کمرے)</span></div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Beds <span class="urdu">(بیڈروم)</span></label>
                    <input type="number" min="0" class="form-control @error('bedrooms') is-invalid @enderror" name="bedrooms" value="{{ $v('bedrooms') }}">
                    @error('bedrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Baths <span class="urdu">(باتھ روم)</span></label>
                    <input type="number" min="0" class="form-control @error('bathrooms') is-invalid @enderror" name="bathrooms" value="{{ $v('bathrooms') }}">
                    @error('bathrooms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label">Kitchens <span class="urdu">(کچن)</span></label>
                    <input type="number" min="0" class="form-control @error('kitchens') is-invalid @enderror" name="kitchens" value="{{ $v('kitchens') }}">
                    @error('kitchens') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-section mt-2">Additional Rooms <span class="urdu">(اضافی کمرے)</span></div>
        <div class="row g-2">
            @php $rooms = ['Servant Quarters', 'Drawing Room', 'Dining Room', 'Study Room', 'Prayer Room', 'Powder Room', 'Lounge', 'Laundry Room', 'Store Rooms', 'Steam Room']; @endphp
            @foreach($rooms as $i => $room)
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="additional_rooms[]" value="{{ $room }}" id="ar_{{ $i }}" {{ $isChk('additional_rooms', $room) }}>
                    <label class="form-check-label" for="ar_{{ $i }}">{{ $room }}</label>
                </div>
            </div>
            @endforeach
        </div>
        @error('additional_rooms') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

        <div class="form-section mt-2">Building Features <span class="urdu">(عمارت کی خصوصیات)</span></div>
        <div class="row g-2">
            @php $bfeatures = ['Elevator', 'Lobby', 'Double Glazed Windows', 'Central AC', 'Central Heating', 'Flooring', 'Electricity Backup', 'Waste Disposal']; @endphp
            @foreach($bfeatures as $i => $feat)
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="building_features[]" value="{{ $feat }}" id="bf_{{ $i }}" {{ $isChk('building_features', $feat) }}>
                    <label class="form-check-label" for="bf_{{ $i }}">{{ $feat }}</label>
                </div>
            </div>
            @endforeach
        </div>
        @error('building_features') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

{{-- ============ PLOT ============ --}}
<div data-cat="plot">
    <div class="form-section">Plot Dimensions <span class="urdu">(پلاٹ کی ناپ)</span></div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Plot Size <span class="urdu">(پلاٹ کا سائز)</span></label>
                <input type="number" step="0.01" class="form-control @error('plot_size') is-invalid @enderror" name="plot_size" value="{{ $v('plot_size') }}">
                @error('plot_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Unit <span class="urdu">(یونٹ)</span></label>
                <select class="form-select @error('plot_size_unit') is-invalid @enderror" name="plot_size_unit">
                    <option value=""><span class="urdu">(منتخب کریں)</span></option>
                    <option value="marla" {{ $isSel('plot_size_unit', 'marla') }}>Marla</option>
                    <option value="kanal" {{ $isSel('plot_size_unit', 'kanal') }}>Kanal</option>
                    <option value="sqft" {{ $isSel('plot_size_unit', 'sqft') }}>Sq. Ft.</option>
                    <option value="sqm" {{ $isSel('plot_size_unit', 'sqm') }}>Sq. M.</option>
                    <option value="acre" {{ $isSel('plot_size_unit', 'acre') }}>Acre</option>
                </select>
                @error('plot_size_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

{{-- ============ PLOT & AGRICULTURAL LAND (shared) ============ --}}
<div data-cat="plot land">
    <div class="form-section">Land Details <span class="urdu">(زمین کی تفصیلات)</span></div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Land Area <span class="urdu">(رقبہ)</span></label>
                <input type="text" class="form-control @error('land_area') is-invalid @enderror" name="land_area" value="{{ $v('land_area') }}">
                @error('land_area') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Road Width <span class="urdu">(سڑک کی چوڑائی)</span> <span class="text-secondary fw-normal">(ft)</span></label>
                <input type="number" step="0.01" min="0" class="form-control @error('road_width') is-invalid @enderror" name="road_width" value="{{ $v('road_width') }}">
                @error('road_width') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Facing <span class="urdu">(رخ)</span></label>
                <select class="form-select @error('facing') is-invalid @enderror" name="facing">
                    <option value=""><span class="urdu">(منتخب کریں)</span></option>
                    <option value="North" {{ $isSel('facing', 'North') }}>North <span class="urdu">(شمال)</span></option>
                    <option value="South" {{ $isSel('facing', 'South') }}>South <span class="urdu">(جنوب)</span></option>
                    <option value="East" {{ $isSel('facing', 'East') }}>East <span class="urdu">(مشرق)</span></option>
                    <option value="West" {{ $isSel('facing', 'West') }}>West <span class="urdu">(مغرب)</span></option>
                    <option value="Main Road" {{ $isSel('facing', 'Main Road') }}>Main Road <span class="urdu">(مرکزی سڑک)</span></option>
                </select>
                @error('facing') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>
