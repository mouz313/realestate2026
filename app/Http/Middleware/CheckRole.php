<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! $request->user()) {
            abort(403);
        }

        if ($role === 'super_admin' && ! $request->user()->isSuperAdmin()) {
            abort(403);
        }

        if ($role === 'admin' && ! $request->user()->isAdmin() && ! $request->user()->isSuperAdmin()) {
            abort(403);
        }

        if ($role === 'agent' && ! $request->user()->isAgent()) {
            abort(403);
        }

        return $next($request);
    }
}
