<?php

namespace App\Models;

use App\Models\Agent;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['company_id', 'name', 'email', 'password', 'avatar', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isOwner(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'agent' || $this->hasRole('staff');
    }

    public function isClient(): bool
    {
        return $this->role === 'client' || $this->hasRole('client');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot('granted');
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $direct = $this->permissions()
            ->where('slug', $slug)
            ->first();

        if ($direct) {
            return (bool) $direct->pivot->granted;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('slug', $slug))
            ->exists();
    }

    public function assignRole(string $slug): self
    {
        $role = Role::where('slug', $slug)
            ->where(function ($q) {
                $q->whereNull('company_id')
                    ->orWhere('company_id', current_company_id());
            })
            ->first();

        if ($role) {
            $this->roles()->syncWithoutDetaching($role->id);
        }

        return $this;
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
