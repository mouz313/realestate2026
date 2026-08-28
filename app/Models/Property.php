<?php

namespace App\Models;

use App\Scopes\AgentScope;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope(new AgentScope('assigned_agent_id'));
    }

    protected $fillable = [
        'company_id', 'property_code', 'title', 'category', 'transaction_type', 'status', 'commission_rate',
        'sourced_by_agent_id',
        'possession_status', 'possession_year',
        'price', 'price_per_sqft', 'currency', 'location_address', 'city', 'city_id',
        'sector_town', 'block', 'plot_size', 'plot_size_unit', 'land_area',
        'covered_area', 'covered_area_unit',
        'bedrooms', 'bathrooms', 'kitchens', 'floors', 'floor_number', 'total_floors', 'furnished',
        'furnished_type', 'property_condition', 'year_built', 'road_width', 'facing',
        'parking_spaces', 'features', 'additional_rooms', 'building_features',
        'community_amenities', 'communication_features',
        'nearby_landmarks', 'nearby_places', 'utilities', 'latitude', 'longitude',
        'description', 'owner_id', 'assigned_agent_id', 'listed_date',
        'expiry_date', 'views_count', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'price_per_sqft' => 'decimal:2',
            'plot_size' => 'decimal:2',
            'land_area' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'furnished' => 'boolean',
            'furnished_type' => 'string',
            'property_condition' => 'string',
            'year_built' => 'integer',
            'road_width' => 'decimal:2',
            'facing' => 'string',
            'features' => 'json',
            'additional_rooms' => 'json',
            'building_features' => 'json',
            'community_amenities' => 'json',
            'communication_features' => 'json',
            'nearby_places' => 'json',
            'utilities' => 'json',
            'listed_date' => 'date',
            'expiry_date' => 'date',
            'views_count' => 'integer',
        ];
    }

    public function sourcedByAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'sourced_by_agent_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'owner_id');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PropertyMedia::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function comparisonSpecs(): array
    {
        $specs = [
            ['icon' => 'ti-tag', 'label' => 'Price', 'value' => $this->price ? 'Rs. '.number_format($this->price, 0) : null],
            ['icon' => 'ti-exchange', 'label' => 'Transaction', 'value' => $this->transaction_type ? ucfirst($this->transaction_type) : null],
            ['icon' => 'ti-home', 'label' => 'Type', 'value' => $this->category ? ucfirst(str_replace('_', ' ', $this->category)) : null],
            ['icon' => 'ti-check', 'label' => 'Status', 'value' => $this->status ? ucfirst($this->status) : null],
            ['icon' => 'ti-map-pin', 'label' => 'City', 'value' => $this->city],
            ['icon' => 'ti-map-pin', 'label' => 'Location', 'value' => $this->location_address],
            ['icon' => 'ti-bed', 'label' => 'Bedrooms', 'value' => $this->bedrooms],
            ['icon' => 'ti-bath', 'label' => 'Bathrooms', 'value' => $this->bathrooms],
            ['icon' => 'ti-tools-kitchen', 'label' => 'Kitchens', 'value' => $this->kitchens],
            ['icon' => 'ti-ruler-2', 'label' => 'Plot Size', 'value' => $this->plot_size ? number_format($this->plot_size, 0).' '.($this->plot_size_unit ?? 'sqft') : null],
            ['icon' => 'ti-building', 'label' => 'Covered Area', 'value' => $this->covered_area ? number_format($this->covered_area, 0).' sqft' : null],
            ['icon' => 'ti-car', 'label' => 'Parking', 'value' => $this->parking_spaces],
            ['icon' => 'ti-armchair', 'label' => 'Furnished', 'value' => $this->furnished_type ? ucwords(str_replace('_', ' ', $this->furnished_type)) : ($this->furnished ? 'Yes' : null)],
            ['icon' => 'ti-calendar', 'label' => 'Possession', 'value' => $this->possession_status],
            ['icon' => 'ti-stack', 'label' => 'Floors', 'value' => $this->floors],
            ['icon' => 'ti-clock', 'label' => 'Listed', 'value' => $this->listed_date ? $this->listed_date->format('d M Y') : null],
            ['icon' => 'ti-hash', 'label' => 'Code', 'value' => $this->property_code],
            ['icon' => 'ti-user', 'label' => 'Owner', 'value' => $this->owner?->name],
            ['icon' => 'ti-user', 'label' => 'Agent', 'value' => $this->assignedAgent?->name],
        ];

        return array_values(array_filter($specs, fn ($s) => $s['value'] !== null && $s['value'] !== ''));
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function primaryMedia()
    {
        return $this->hasOne(PropertyMedia::class)->where('is_primary', true);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeCity($query, $city)
    {
        if (empty($city)) {
            return $query;
        }

        return is_numeric($city)
            ? $query->where('city_id', $city)
            : $query->where('city', $city);
    }

    public function scopeCategory($query, ?string $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        return $term ? $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('property_code', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%");
        }) : $query;
    }
}
