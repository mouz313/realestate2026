<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'plan_id', 'installment_no', 'due_date', 'amount', 'paid_amount',
        'status', 'paid_date', 'payment_method', 'reference_no', 'late_fee', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
            'installment_no' => 'integer',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InstallmentPlan::class, 'plan_id');
    }
}
