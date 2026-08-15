<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (! method_exists($user, 'hasRoleAccess')) {
            abort(403);
        }

        if (! $user->hasRoleAccess($role)) {
            abort(403, "You do not have the '{$role}' role.");
        }

        return $next($request);
    }
}
