<?php

namespace App\Models;

use App\Scopes\AgentScope;
use App\Services\CommissionCalculator;
use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use LogsActivity, BelongsToCompany;

    protected static function booted(): void
    {
        static::addGlobalScope(new AgentScope('agent_id'));

        // Enforce the 90/10 split (agency keeps 90%, agent gets 10%) whenever a
        // commission is created without an explicit split. Values already set by
        // DealController::syncDealExtras are left untouched.
        static::creating(function (self $commission) {
            if (! is_null($commission->amount)
                && (is_null($commission->agent_amount) || is_null($commission->agency_amount))) {
                $split = CommissionCalculator::split($commission->amount);
                $commission->agent_amount = $split['agent_amount'];
                $commission->agency_amount = $split['agency_amount'];
            }
        });
    }

    protected $fillable = [
        'company_id', 'deal_id', 'agent_id', 'type', 'percentage', 'amount',
        'agency_amount', 'agent_amount', 'source',
        'status', 'paid_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'amount' => 'decimal:2',
            'agency_amount' => 'decimal:2',
            'agent_amount' => 'decimal:2',
            'paid_date' => 'date',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function markPaid(): static
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);

        return $this;
    }
}
