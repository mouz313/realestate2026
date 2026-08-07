<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->isAgent()) {
            return $next($request);
        }

        if (! has_active_subscription()) {
            toastr()->info('Your subscription is missing or expired. Please purchase a package to continue.', 'Subscription required');

            return redirect()->route('billing.index');
        }

        return $next($request);
    }
}
