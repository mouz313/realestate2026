<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function authorizeAgentAccess($record, ?string $relation = null): void
    {
        $user = auth()->user();
        if (! $user || $user->isAdmin()) {
            return;
        }
        $agentId = $user->agent_id;
        if ($relation) {
            $related = $record->{$relation};
            if (! $related || $related->assigned_agent_id !== $agentId) {
                abort(403, 'Unauthorized access to this record.');
            }
        } else {
            if ($record->agent_id !== $agentId) {
                abort(403, 'Unauthorized access to this record.');
            }
        }
    }

    protected function authorizePropertyAccess($record): void
    {
        $user = auth()->user();
        if (! $user || $user->isAdmin()) {
            return;
        }
        if ($record->assigned_agent_id !== $user->agent_id) {
            abort(403, 'Unauthorized access to this record.');
        }
    }

    protected function authorizeViaDeal($record): void
    {
        $user = auth()->user();
        if (! $user || $user->isAdmin()) {
            return;
        }
        if ($record->deal && $record->deal->agent_id !== $user->agent_id) {
            abort(403, 'Unauthorized access to this record.');
        }
    }
}
