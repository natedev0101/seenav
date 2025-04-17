<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateUserOfflineStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->update(['is_online' => false]);
        }

        return $next($request);
    }
}