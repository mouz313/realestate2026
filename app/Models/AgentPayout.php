<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentPayout extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'agent_id', 'amount', 'commission_ids', 'payout_date',
        'method', 'reference', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'commission_ids' => 'json',
            'payout_date' => 'date',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
