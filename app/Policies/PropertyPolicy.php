<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->assigned_agent_id === $user->agent_id;
    }

    public function update(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->assigned_agent_id === $user->agent_id;
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->isAdmin() || $property->assigned_agent_id === $user->agent_id;
    }
}
