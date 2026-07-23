<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentAgreement extends Model
{
    protected $table = 'rent_agreements';

    protected $fillable = [
        'deal_id', 'property_id', 'tenant_id', 'owner_id', 'start_date', 'end_date',
        'rent_amount', 'security_deposit', 'deposit_received', 'deposit_returned',
        'notice_period_days', 'late_fee_per_day', 'rent_increase_percent',
        'rent_increase_frequency', 'payment_frequency', 'agreement_doc', 'status', 'notes', 'terms',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'rent_amount' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'deposit_received' => 'boolean',
            'deposit_returned' => 'boolean',
            'notice_period_days' => 'integer',
            'late_fee_per_day' => 'decimal:2',
            'rent_increase_percent' => 'decimal:2',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'tenant_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'owner_id');
    }
}
