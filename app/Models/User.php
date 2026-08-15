<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\HasRoles;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['company_id', 'name', 'email', 'password', 'avatar', 'role', 'is_active', 'agent_id', 'notification_prefs'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notification_prefs' => 'array',
        ];
    }

    public function allowsChannel(string $channel, bool $default = true): bool
    {
        $prefs = $this->notification_prefs ?? [];

        return $prefs[$channel] ?? $default;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function assignedProperties(): HasManyThrough
    {
        return $this->hasManyThrough(Property::class, Agent::class, 'user_id', 'assigned_agent_id');
    }
}
