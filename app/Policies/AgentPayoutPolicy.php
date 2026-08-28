<?php

namespace App\Policies;

use App\Models\AgentPayout;
use App\Models\User;

class AgentPayoutPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AgentPayout $agentPayout): bool
    {
        return $user->isAdmin() || $agentPayout->agent_id === $user->agent_id;
    }

    public function update(User $user, AgentPayout $agentPayout): bool
    {
        return $user->isAdmin() || $agentPayout->agent_id === $user->agent_id;
    }

    public function delete(User $user, AgentPayout $agentPayout): bool
    {
        return $user->isAdmin() || $agentPayout->agent_id === $user->agent_id;
    }
}
