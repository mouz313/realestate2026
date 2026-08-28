<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Deal;
use App\Models\PropertyVisit;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Contact extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'name', 'email', 'phone', 'subject', 'message', 'read_at', 'status',
        'property_id', 'property_title', 'lead_source',
        'property_type', 'purpose', 'city', 'location', 'budget_min', 'budget_max',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CLOSED = 'closed';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'contact_id');
    }

    public function client()
    {
        return Client::where('phone', $this->phone)
            ->where('company_id', $this->company_id)
            ->first();
    }

    public function visits()
    {
        return PropertyVisit::query()
            ->where('company_id', $this->company_id)
            ->where(function ($q) {
                $q->where('contact_id', $this->id)
                    ->orWhereHas('client', fn ($c) => $c
                        ->where('phone', $this->phone)
                        ->where('company_id', $this->company_id));
            })
            ->with(['property', 'agent'])
            ->orderBy('scheduled_date')
            ->get();
    }
}
