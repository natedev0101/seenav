<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrackUserTime
{
    public function handle($request, Closure $next)
    {
        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        if (Auth::check()) {
            $user = Auth::user();
            $now = Carbon::now();

            // Frissítjük az utolsó aktivitás időpontját és az online státuszt
            $user->update([
                'last_active' => $now,
                'is_online' => true
            ]);
        }

        return $next($request);
    }
}