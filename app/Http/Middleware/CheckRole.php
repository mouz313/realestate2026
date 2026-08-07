<?php

namespace App\Http\Middleware;

use App\Models\Role;
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

        if ($role === 'super_admin' && ! $user->isSuperAdmin()) {
            abort(403);
        }

        if ($role === 'admin' && ! $user->isAdmin() && ! $user->isSuperAdmin()) {
            abort(403);
        }

        if ($role === 'agent' && ! $user->isAgent() && ! $user->isAdmin() && ! $user->isSuperAdmin()) {
            abort(403);
        }

        if (in_array($role, ['staff', 'client', 'owner'])) {
            $companyId = current_company_id();
            $hasRole = Role::where('slug', $role)
                ->where(function ($q) use ($companyId) {
                    $q->whereNull('company_id')->orWhere('company_id', $companyId);
                })
                ->exists();

            if (! $hasRole) {
                abort(403, "Role '{$role}' not found for this company.");
            }

            if (! $user->isSuperAdmin() && ! $user->isAdmin() && ! $user->hasRole($role)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
