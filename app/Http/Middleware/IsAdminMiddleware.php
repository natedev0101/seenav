<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Be kell jelentkezned!');
    }


    if (Auth::user()->isAdmin || Auth::user()->is_superadmin) {
        return $next($request);
    }

    return redirect()->route('dashboard')->with('error', 'Nincs jogosultságod a közlemények kezelésére!');
}
}