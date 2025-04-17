<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OfficerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isOfficer) {
            abort(403, 'Nincs jogosultságod az oldal eléréséhez.');
        }
        return $next($request);
    }
}
