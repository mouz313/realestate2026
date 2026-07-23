<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'property_code', 'title', 'type', 'transaction_type', 'status',
        'possession_status', 'possession_year',
        'price', 'price_per_sqft', 'currency', 'location_address', 'city',
        'sector_town', 'block', 'plot_size', 'plot_size_unit', 'land_area',
        'covered_area', 'covered_area_unit',
        'bedrooms', 'bathrooms', 'kitchens', 'floors', 'floor_number', 'total_floors', 'furnished',
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
            'price_per_sqft' => 'decimal:2',
            'plot_size' => 'decimal:2',
            'land_area' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'furnished' => 'boolean',
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
}
