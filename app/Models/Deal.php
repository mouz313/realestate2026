<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'deal_number', 'type', 'status', 'property_id', 'buyer_id', 'seller_id',
        'agent_id', 'co_agent_id', 'sale_price', 'token_amount', 'token_date',
        'commission_percentage', 'commission_amount', 'agent_commission', 'agency_share',
        'agreement_date', 'possession_date', 'payment_plan', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_price' => 'decimal:2',
            'token_amount' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'agent_commission' => 'decimal:2',
            'agency_share' => 'decimal:2',
            'agreement_date' => 'date',
            'possession_date' => 'date',
            'token_date' => 'date',
            'payment_plan' => 'json',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'seller_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function coAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'co_agent_id');
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function installmentPlan(): HasOne
    {
        return $this->hasOne(InstallmentPlan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
}
