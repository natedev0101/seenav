<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user() || !auth()->user()->is_superadmin) {
            return redirect()->route('ranks.index')->with('error', 'Nincs jogosultságod.');
        }
        return $next($request);
    }
}
