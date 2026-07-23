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
        return $next($request);
    }
}
