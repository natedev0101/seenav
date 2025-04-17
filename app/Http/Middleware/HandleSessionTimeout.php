<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ha a felhasználó nincs bejelentkezve, de van intended URL
        if (!Auth::check() && $request->session()->has('url.intended')) {
            // Ha a session lejárt, de van intended URL, mentsük el egy cookie-ba
            $intendedUrl = $request->session()->get('url.intended');
            if ($intendedUrl !== route('login')) {
                cookie()->queue('redirect_after_login', $intendedUrl, 5); // 5 perces cookie
            }
        }

        // Ha sikeres bejelentkezés után vagyunk és van mentett cookie
        if (Auth::check() && $request->hasCookie('redirect_after_login')) {
            $url = $request->cookie('redirect_after_login');
            cookie()->queue(cookie()->forget('redirect_after_login'));
            
            // Ha a felhasználó a session expired oldalra került, átirányítjuk az eredeti oldalra
            if ($request->is('session-expired') || $request->is('login')) {
                return redirect($url);
            }
            
            // Egyébként csak visszaadjuk a következő middleware-nek
            return $next($request);
        }

        // Ellenőrizzük, hogy a session lejárt-e
        if (Auth::check() && $request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // perc -> másodperc
            
            if (time() - $lastActivity > $sessionLifetime) {
                // Session lejárt, kijelentkeztetjük a felhasználót
                // Frissítsük az online státuszt is
                Auth::user()->update(['is_online' => false]);
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Átirányítjuk a session expired oldalra
                return redirect()->route('session.expired');
            }
        }
        
        // Frissítjük a last_activity időt a session-ben
        if (Auth::check()) {
            $request->session()->put('last_activity', time());
        }
        
        // Ha a kérés egy POST kérés a login oldalra és a CSRF token lejárt
        // Ez a "page has expired" hiba kezelése
        if ($request->is('login') && $request->isMethod('post') && $request->session()->has('errors')) {
            $errors = $request->session()->get('errors');
            if ($errors->has('_token')) {
                // Regeneráljuk a CSRF tokent és átirányítjuk a felhasználót a login oldalra
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('status', 'Az oldal lejárt. Kérjük, próbáld újra a bejelentkezést.');
            }
        }

        return $next($request);
    }
}
