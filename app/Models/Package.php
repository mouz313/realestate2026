<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'currency', 'interval',
        'max_employees', 'max_clients', 'max_properties', 'trial_days',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'max_employees' => 'integer',
            'max_clients' => 'integer',
            'max_properties' => 'integer',
            'trial_days' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function intervalLabel(): string
    {
        return $this->interval === 'year' ? 'Yearly' : 'Monthly';
    }

    public function limit(int $key): int
    {
        return $this->{$key};
    }

    public function limitLabel(string $key): string
    {
        $max = $this->{$key};

        return $max > 0 ? $max : 'Unlimited';
    }

    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
