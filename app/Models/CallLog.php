<?php

namespace App\Models;

use App\Scopes\AgentScope;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallLog extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope(new AgentScope('assigned_agent_id'));
    }

    protected $fillable = [
        'company_id', 'client_id', 'created_by', 'assigned_agent_id', 'property_id', 'deal_id',
        'name', 'phone', 'alternate_phone', 'lead_source', 'caller_role', 'category', 'transaction_type',
        'city', 'city_id', 'location', 'bedrooms', 'budget_min', 'budget_max',
        'notes', 'call_datetime', 'follow_up_date', 'status', 'matched_at',
    ];

    protected function casts(): array
    {
        return [
            'call_datetime' => 'datetime',
            'follow_up_date' => 'date',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'matched_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id');
    }
}
