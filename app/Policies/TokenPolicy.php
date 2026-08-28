<?php

namespace App\Policies;

use App\Models\Token;
use App\Models\User;

class TokenPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Token $token): bool
    {
        return $user->isAdmin()
            || ($token->deal && $token->deal->agent_id === $user->agent_id);
    }

    public function update(User $user, Token $token): bool
    {
        return $user->isAdmin()
            || ($token->deal && $token->deal->agent_id === $user->agent_id);
    }

    public function delete(User $user, Token $token): bool
    {
        return $user->isAdmin()
            || ($token->deal && $token->deal->agent_id === $user->agent_id);
    }
}
