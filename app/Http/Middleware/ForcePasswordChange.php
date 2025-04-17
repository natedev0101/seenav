<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->force_password_change) {
            // Ha a felhasználó a jelszóváltoztató oldalon van, engedjük tovább
            if ($request->route()->getName() === 'password.force-change') {
                return $next($request);
            }
            
            // Egyébként átirányítjuk a jelszóváltoztató oldalra
            return redirect()->route('password.force-change');
        }

        return $next($request);
    }
}
