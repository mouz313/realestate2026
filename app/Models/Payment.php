<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use LogsActivity, BelongsToCompany;

    protected static function booted(): void
    {
        static::addGlobalScope('agent', function (Builder $builder) {
            $user = Auth::user();

            if (! $user || $user->isAdmin() || $user->isStaff()) {
                return;
            }

            if (! $user->isAgent() || ! $user->agent_id) {
                $builder->whereRaw('1 = 0');

                return;
            }

            $agentId = $user->agent_id;
            $builder->whereHas('invoice', fn ($q) => $q->where('agent_id', $agentId));
        });
    }

    protected $fillable = [
        'company_id', 'invoice_id', 'rent_agreement_id', 'amount', 'method', 'reference', 'paid_date', 'notes', 'payment_type',
    ];

    protected function casts(): array
    {
        return [
            'paid_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
