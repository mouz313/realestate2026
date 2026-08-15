<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentDepositDeduction extends Model
{
    use LogsActivity, BelongsToCompany;

    protected $fillable = [
        'company_id', 'rent_agreement_id', 'category', 'title', 'amount', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function rentAgreement(): BelongsTo
    {
        return $this->belongsTo(RentAgreement::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}