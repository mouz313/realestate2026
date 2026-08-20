<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalRecord extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id', 'property_id', 'tenant_id', 'landlord_id', 'created_by',
        'start_date', 'end_date', 'duration_months', 'status', 'notes',
        'reminders_sent', 'last_verification_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'reminders_sent' => 'json',
            'last_verification_sent_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'tenant_id');
    }

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'landlord_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by');
    }
}
