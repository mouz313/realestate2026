<?php

namespace App\Policies;

use App\Models\PropertyVisit;
use App\Models\User;

class PropertyVisitPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PropertyVisit $propertyVisit): bool
    {
        return $user->isAdmin() || $propertyVisit->agent_id === $user->agent_id;
    }

    public function update(User $user, PropertyVisit $propertyVisit): bool
    {
        return $user->isAdmin() || $propertyVisit->agent_id === $user->agent_id;
    }

    public function delete(User $user, PropertyVisit $propertyVisit): bool
    {
        return $user->isAdmin() || $propertyVisit->agent_id === $user->agent_id;
    }
}
