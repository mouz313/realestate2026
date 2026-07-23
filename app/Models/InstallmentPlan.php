<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPlan extends Model
{
    protected $fillable = ['deal_id', 'total_installments', 'installment_amount', 'frequency', 'start_date', 'notes'];

    protected function casts(): array
    {
        return [
            'installment_amount' => 'decimal:2',
            'start_date' => 'date',
            'total_installments' => 'integer',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class, 'plan_id');
    }
}
