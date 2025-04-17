<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateLastActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $now = Carbon::now();

            // Ha már volt utolsó aktív idő, számoljuk ki az eltelt perceket
            if ($user->last_active) {
                $minutesSpent = $user->last_active->diffInMinutes($now);
                $user->increment('time_spent', $minutesSpent);
            }

            // Frissítjük az utolsó aktív időt
            $user->update(['last_active' => $now]);
        }

        return $next($request);
    }
}