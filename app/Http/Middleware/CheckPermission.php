<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // "|" separates alternatives: permission:a|b passes if the user has a OR b.
        // Note: Laravel splits middleware parameters on commas into separate
        // invocations, so a plain comma-list would abort on the first check.
        $permissions = array_filter(array_map('trim', explode('|', $permission)));

        if (empty($permissions)) {
            return $next($request);
        }

        foreach ($permissions as $perm) {
            if ($user->hasPermission($perm)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}
