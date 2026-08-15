<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AgentScope implements Scope
{
    public function __construct(protected string $foreignKey = 'assigned_agent_id') {}

    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        if (! $user) {
            return;
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return;
        }

        if (method_exists($user, 'isStaff') && $user->isStaff()) {
            return;
        }

        if (! (method_exists($user, 'isAgent') && $user->isAgent())) {
            return;
        }

        $agentId = $user->agent_id ?? null;

        if (! $agentId) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->where($model->getTable().'.'.$this->foreignKey, $agentId);
    }
}
