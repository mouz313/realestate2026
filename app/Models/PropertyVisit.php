<?php

namespace App\Models;

use App\Models\CallLog;
use App\Models\Deal;
use App\Scopes\AgentScope;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyVisit extends Model
{
    use BelongsToCompany;

    protected static function booted(): void
    {
        static::addGlobalScope(new AgentScope('agent_id'));
    }

    protected $fillable = [
        'company_id', 'property_id', 'client_id', 'call_log_id', 'agent_id', 'deal_id', 'scheduled_date',
        'status', 'feedback', 'rating', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'rating' => 'integer',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function callLog(): BelongsTo
    {
        return $this->belongsTo(CallLog::class, 'call_log_id');
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
