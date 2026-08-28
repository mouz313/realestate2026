<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;

class CommissionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Commission $commission): bool
    {
        return $user->isAdmin() || $commission->agent_id === $user->agent_id;
    }

    public function update(User $user, Commission $commission): bool
    {
        return $user->isAdmin() || $commission->agent_id === $user->agent_id;
    }

    public function delete(User $user, Commission $commission): bool
    {
        return $user->isAdmin() || $commission->agent_id === $user->agent_id;
    }
}
