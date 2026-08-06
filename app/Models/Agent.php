<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'user_id', 'name', 'role', 'phone', 'whatsapp', 'email', 'cnic', 'cnic_front', 'cnic_back',
        'photo', 'address', 'license_number', 'commission_rate',
        'status', 'type', 'join_date', 'notes',
        'bio', 'experience_years', 'languages',
        'facebook', 'twitter', 'linkedin', 'instagram',
        'website', 'specializations',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'join_date' => 'date',
            'specializations' => 'json',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'agent_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(AgentPayout::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class, 'agent_id');
    }

    public function rentAgreements(): HasManyThrough
    {
        return $this->hasManyThrough(RentAgreement::class, Property::class, 'assigned_agent_id', 'property_id');
    }

    public function assignedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'assigned_agent_id');
    }
}
