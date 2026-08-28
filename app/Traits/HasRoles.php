<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot('granted');
    }

    public function hasRole(string|array $slug): bool
    {
        $slugs = is_array($slug) ? $slug : [$slug];

        return $this->roles()->whereIn('slug', $slugs)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        $direct = $this->permissions()->where('slug', $slug)->first();

        if ($direct) {
            return (bool) $direct->pivot->granted;
        }

        return $this->roles()->whereHas('permissions', fn ($q) => $q->where('slug', $slug))->exists();
    }

    public function assignRole(string $slug): self
    {
        $role = $this->findRoleBySlug($slug);

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
            $this->syncPrimaryRoleCache();
        }

        return $this;
    }

    public function removeRole(string $slug): self
    {
        $role = $this->findRoleBySlug($slug);

        if ($role) {
            $this->roles()->detach($role->id);
            $this->syncPrimaryRoleCache();
        }

        return $this;
    }

    public function syncRoles(array $slugs): self
    {
        $roles = collect($slugs)
            ->map(fn ($slug) => $this->findRoleBySlug($slug))
            ->filter()
            ->pluck('id')
            ->all();

        $this->roles()->sync($roles);
        $this->syncPrimaryRoleCache();

        return $this;
    }

    public function grantPermission(string $slug): self
    {
        $permission = Permission::where('slug', $slug)
            ->where(function ($q) {
                $q->whereNull('company_id')->orWhere('company_id', $this->company_id ?? null);
            })
            ->first();

        if ($permission) {
            $this->permissions()->syncWithoutDetaching([
                $permission->id => ['granted' => true],
            ]);
        }

        return $this;
    }

    public function revokePermission(string $slug): self
    {
        $permission = Permission::where('slug', $slug)
            ->where(function ($q) {
                $q->whereNull('company_id')->orWhere('company_id', $this->company_id ?? null);
            })
            ->first();

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }

        return $this;
    }

    public function denyPermission(string $slug): self
    {
        $permission = Permission::where('slug', $slug)
            ->where(function ($q) {
                $q->whereNull('company_id')->orWhere('company_id', $this->company_id ?? null);
            })
            ->first();

        if ($permission) {
            $this->permissions()->syncWithoutDetaching([
                $permission->id => ['granted' => false],
            ]);
        }

        return $this;
    }

    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->isOwner();
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    public function isAgent(): bool
    {
        return $this->hasRole('agent') && isset($this->agent_id) && $this->agent_id;
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    public function hasRoleAccess(string $role): bool
    {
        $hierarchy = ['agent' => 1, 'staff' => 2, 'admin' => 3, 'owner' => 4];

        // Unknown roles (e.g. 'client') are not part of the hierarchy, so deny.
        if (! isset($hierarchy[$role])) {
            return false;
        }

        $userLevel = 0;

        foreach ($this->roles()->pluck('slug') as $slug) {
            $userLevel = max($userLevel, $hierarchy[$slug] ?? 0);
        }

        $required = $hierarchy[$role];

        return $userLevel >= $required;
    }

    protected function findRoleBySlug(string $slug): ?Role
    {
        $companyId = $this->company_id ?? current_company_id();

        return Role::where('slug', $slug)
            ->where(function ($q) use ($companyId) {
                $q->whereNull('company_id');
                if ($companyId) {
                    $q->orWhere('company_id', $companyId);
                }
            })
            ->orderByRaw('company_id IS NULL')
            ->first();
    }

    public function syncPrimaryRoleCache(): void
    {
        if (! array_key_exists('role', $this->getAttributes())) {
            return;
        }

        $hierarchy = ['agent' => 1, 'staff' => 2, 'admin' => 3, 'owner' => 4];

        $slugs = $this->roles()->pluck('slug')->all();

        $top = null;
        $topLevel = 0;

        foreach ($slugs as $slug) {
            $level = $hierarchy[$slug] ?? 0;
            if ($level > $topLevel) {
                $topLevel = $level;
                $top = $slug;
            }
        }

        $this->role = $top ?? 'agent';
        $this->saveQuietly();
    }
}
