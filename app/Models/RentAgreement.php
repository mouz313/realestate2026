<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentAgreement extends Model
{
    use LogsActivity, SoftDeletes, BelongsToCompany;

    protected $table = 'rent_agreements';

    protected $fillable = [
        'company_id', 'deal_id', 'renewed_from_id', 'property_id', 'tenant_id', 'owner_id', 'start_date', 'end_date',
        'possession_returned_date',
        'rent_amount', 'security_deposit', 'deposit_received', 'deposit_returned',
        'deposit_received_amount', 'deposit_received_date',
        'deposit_deductions', 'deposit_deduction_notes', 'deposit_returned_date',
        'notice_period_days', 'late_fee_per_day', 'rent_increase_percent',
        'rent_increase_frequency', 'payment_frequency', 'agreement_doc', 'status', 'notes', 'terms',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'possession_returned_date' => 'date',
            'rent_amount' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'deposit_received' => 'boolean',
            'deposit_received_amount' => 'decimal:2',
            'deposit_received_date' => 'date',
            'deposit_returned' => 'boolean',
            'notice_period_days' => 'integer',
            'late_fee_per_day' => 'decimal:2',
            'rent_increase_percent' => 'decimal:2',
            'deposit_deductions' => 'decimal:2',
            'deposit_returned_date' => 'date',
        ];
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function renewedFrom(): BelongsTo
    {
        return $this->belongsTo(RentAgreement::class, 'renewed_from_id');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(RentAgreement::class, 'renewed_from_id');
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

    public function rentPayments(): HasMany
    {
        return $this->hasMany(RentPayment::class);
    }

    public function rentNotices(): HasMany
    {
        return $this->hasMany(RentNotice::class);
    }

    public function depositDeductions(): HasMany
    {
        return $this->hasMany(RentDepositDeduction::class);
    }

    public function generateSchedule(): void
    {
        if (! $this->start_date) {
            return;
        }

        $start = $this->start_date->copy()->startOfMonth();
        $end = $this->end_date ? $this->end_date->copy()->endOfMonth() : $start->copy()->addYear();

        $current = $start->copy();

        while ($current->lte($end)) {
            $exists = $this->rentPayments()
                ->where('month', $current->month)
                ->where('year', $current->year)
                ->exists();

            if (! $exists) {
                $rent = $this->calculateRentForMonth($current);
                $dueDay = min(10, $current->daysInMonth);

                $this->rentPayments()->create([
                    'month' => $current->month,
                    'year' => $current->year,
                    'due_date' => $current->copy()->day($dueDay),
                    'amount' => $rent,
                    'total_due' => $rent,
                    'status' => 'pending',
                ]);
            }

            $current->addMonth();
        }
    }

    public function generateNextMonth(): void
    {
        if (! $this->start_date || $this->status !== 'active') {
            return;
        }

        $lastPayment = $this->rentPayments()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        $nextDate = $lastPayment
            ? Carbon::create($lastPayment->year, $lastPayment->month, 1)->addMonth()
            : $this->start_date->copy()->startOfMonth();

        if ($this->end_date && $nextDate->gt($this->end_date->copy()->endOfMonth())) {
            return;
        }

        $exists = $this->rentPayments()
            ->where('month', $nextDate->month)
            ->where('year', $nextDate->year)
            ->exists();

        if ($exists) {
            return;
        }

        $rent = $this->calculateRentForMonth($nextDate);
        $dueDay = min(10, $nextDate->daysInMonth);

        $this->rentPayments()->create([
            'month' => $nextDate->month,
            'year' => $nextDate->year,
            'due_date' => $nextDate->copy()->day($dueDay),
            'amount' => $rent,
            'total_due' => $rent,
            'status' => 'pending',
        ]);
    }

    private function calculateRentForMonth(Carbon $date): float
    {
        $rent = (float) $this->rent_amount;

        if ($this->rent_increase_percent > 0
            && $this->rent_increase_frequency !== 'none'
            && $this->start_date
            && $date->diffInMonths($this->start_date) >= 12
        ) {
            $monthsSinceStart = $date->diffInMonths($this->start_date);
            $interval = match ($this->rent_increase_frequency) {
                'yearly' => 12,
                'quarterly' => 3,
                'monthly' => 1,
                default => 12,
            };

            $timesIncreased = intdiv($monthsSinceStart, $interval);
            for ($i = 0; $i < $timesIncreased; $i++) {
                $rent = round($rent + ($rent * $this->rent_increase_percent / 100), 2);
            }
        }

        return $rent;
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->rentPayments()->where('status', 'paid')->sum('amount');
    }

    public function getTotalPendingAttribute(): float
    {
        return (float) $this->rentPayments()->whereIn('status', ['pending', 'overdue'])->sum('total_due');
    }

    public function getNetDepositReturnAttribute(): float
    {
        return max(0, round((float) $this->deposit_received_amount - (float) $this->deposit_deductions, 2));
    }

    public function getDepositRemainingAttribute(): float
    {
        return max(0, round((float) $this->security_deposit - (float) $this->deposit_received_amount, 2));
    }

    public function syncDepositDeductions(): void
    {
        $total = (float) $this->depositDeductions()->sum('amount');

        if ($total !== (float) $this->deposit_deductions) {
            $this->update(['deposit_deductions' => round($total, 2)]);
        }
    }
}
