<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:house,plot,farmhouse,agricultural_land,flat,studio_apartment,office,shop',
            'transaction_type' => 'required|string|in:sale,buy,rent,installment',
            'status' => 'required|string|in:available,rented,sold',
            'possession_status' => 'nullable|string|in:ready,under_construction,off_plan',
            'possession_year' => 'nullable|integer|min:1900|max:2100',
            'price' => 'required|numeric|min:0',
            'price_per_sqft' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:10',
            'location_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'sector_town' => 'nullable|string|max:100',
            'block' => 'nullable|string|max:50',
            'plot_size' => 'nullable|numeric|min:0',
            'plot_size_unit' => 'nullable|string|max:20',
            'land_area' => 'nullable|numeric|min:0',
            'covered_area' => 'nullable|numeric|min:0',
            'covered_area_unit' => 'nullable|string|max:20',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'kitchens' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',
            'floor_number' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'furnished' => 'nullable|boolean',
            'furnished_type' => 'nullable|in:furnished,semi_furnished,unfurnished',
            'property_condition' => 'nullable|in:new,resale',
            'year_built' => 'nullable|integer|min:1900|max:2100',
            'road_width' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string|max:50',
            'parking_spaces' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*' => 'string',
            'building_features' => 'nullable|array',
            'building_features.*' => 'string',
            'community_amenities' => 'nullable|array',
            'community_amenities.*' => 'string',
            'communication_features' => 'nullable|array',
            'communication_features.*' => 'string',
            'nearby_landmarks' => 'nullable|string|max:1000',
            'nearby_places' => 'nullable|array',
            'nearby_places.*' => 'string|max:50',
            'utilities' => 'nullable|array',
            'utilities.*' => 'string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string|max:5000',
            'owner_id' => 'nullable|exists:clients,id',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'call_log_id' => 'nullable|exists:call_logs,id',
            'assigned_agent_id' => 'nullable|exists:agents,id',
            'listed_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'video' => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
        ];
    }
}
