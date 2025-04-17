<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Require2FA
{
    protected $allowedRoutes = [
        '2fa.setup',
        '2fa.enable',
        '2fa.verify',
        '2fa.verify.post',
        '2fa.disable',
        'help.request',
        'logout'
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        // Debug log
        Log::info('2FA Check', [
            'user' => $user->username,
            'route' => $currentRoute,
            'required' => $user->two_factor_required,
            'has_secret' => !empty($user->two_factor_secret),
            'session' => [
                '2fa_verified' => session('2fa_verified'),
                '2fa_secret' => session('2fa_secret')
            ]
        ]);

        // Ha a route engedélyezett 2FA nélkül is
        if (in_array($currentRoute, $this->allowedRoutes)) {
            return $next($request);
        }

        // Ha nincs kötelező 2FA
        if (!$user->two_factor_required) {
            // Töröljük az összes 2FA-val kapcsolatos session adatot
            $this->clearTwoFactorSession();
            return $next($request);
        }

        // Ha kötelező a 2FA és nincs beállítva
        if (!$user->two_factor_secret) {
            return redirect()->route('2fa.setup')
                ->with('warning', 'A fiókhoz kötelező a két faktoros hitelesítés beállítása!');
        }

        // Ha kötelező a 2FA, be van állítva, de nincs ellenőrizve
        if (!session('2fa_verified')) {
            session(['url.intended' => $request->url()]);
            return redirect()->route('2fa.verify')
                ->with('warning', 'Kérjük, adja meg a két faktoros hitelesítési kódot!');
        }

        return $next($request);
    }

    protected function clearTwoFactorSession()
    {
        session()->forget([
            '2fa_verified',
            '2fa_secret',
            'url.intended',
            'auth.2fa.remember'
        ]);
    }
}
