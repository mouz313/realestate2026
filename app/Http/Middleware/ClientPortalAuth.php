<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientPortalAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('client_id')) {
            return redirect()->route('portal.login');
        }

        $lastActivity = session('portal_last_activity');
        $timeout = 30;
        if ($lastActivity && now()->diffInMinutes($lastActivity) > $timeout) {
            session()->forget(['client_id', 'client_name', 'portal_last_activity']);
            return redirect()->route('portal.login')->withErrors(['Session expired due to inactivity.']);
        }

        session(['portal_last_activity' => now()]);
        return $next($request);
    }
}
