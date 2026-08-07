<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_TRIAL = 'trial';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'company_id', 'package_id', 'verified_by', 'previous_subscription_id',
        'status', 'amount_paid', 'currency', 'started_at', 'ends_at',
        'trial_ends_at', 'verified_at', 'proof_path', 'block_reason', 'note',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid' => 'decimal:2',
            'started_at' => 'datetime',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'verified_at' => 'datetime',
            'verified_by' => 'integer',
            'previous_subscription_id' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function previous(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'previous_subscription_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_TRIAL]);
    }

    public function isExpired(): bool
    {
        return in_array($this->status, [self::STATUS_EXPIRED, self::STATUS_SUSPENDED, self::STATUS_CANCELLED]);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_TRIAL => 'Trial',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_SUSPENDED => 'Suspended',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE, self::STATUS_TRIAL => 'status-active',
            self::STATUS_PENDING => 'status-warning',
            self::STATUS_EXPIRED, self::STATUS_SUSPENDED, self::STATUS_CANCELLED => 'status-draft',
            default => 'status-secondary',
        };
    }
}
