<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentPayment extends Model
{
    use LogsActivity, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'rent_agreement_id', 'month', 'year', 'due_date', 'amount',
        'late_fee', 'total_due', 'status', 'paid_date',
        'payment_method', 'reference_no', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'total_due' => 'decimal:2',
            'paid_date' => 'date',
        ];
    }

    public function rentAgreement(): BelongsTo
    {
        return $this->belongsTo(RentAgreement::class);
    }

    public function getMonthNameAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }

    public function calculateLateFee(): float
    {
        if ($this->status === 'paid' || $this->status === 'waived') {
            return 0;
        }

        $lateFeePerDay = $this->rentAgreement->late_fee_per_day ?? 0;
        if ($lateFeePerDay <= 0 || $this->due_date->isFuture()) {
            return 0;
        }

        $daysPastDue = (int) $this->due_date->diffInDays(now());
        $lateFee = round($daysPastDue * $lateFeePerDay, 2);

        return $lateFee;
    }

    public function syncLateFee(): void
    {
        $lateFee = $this->calculateLateFee();
        if ($lateFee !== $this->late_fee) {
            $this->update([
                'late_fee' => $lateFee,
                'total_due' => $this->amount + $lateFee,
            ]);
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')->where('due_date', '<', now());
    }
}
