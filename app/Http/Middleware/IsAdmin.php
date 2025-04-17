<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isAdmin && !auth()->user()->is_superadmin)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nincs jogosultsága ehhez a művelethez!'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Nincs jogosultsága ehhez a művelethez!');
        }

        return $next($request);
    }
}
