<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Admin és Superadmin 2FA ellenőrzés
        if ($user->two_factor_required) {
            // Ha nincs beállítva 2FA
            if (!$user->two_factor_secret) {
                return redirect()->route('2fa.setup')
                    ->with('warning', 'Az admin fiókhoz kötelező a két faktoros hitelesítés beállítása!');
            }
            
            // Ha kötelező a 2FA és nincs ellenőrizve
            if (!session('2fa_verified')) {
                return redirect()->route('2fa.verify');
            }
        }

        // Ellenőrizzük, hogy kötelező-e a jelszóváltoztatás
        if (Auth::user()->force_password_change) {
            return redirect()->route('password.force-change');
        }

        // Ha van intended URL és az nem a login oldal
        $intended = redirect()->intended()->getTargetUrl();
        if ($intended && $intended !== route('login')) {
            return redirect($intended);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // 2FA session törlése
        session()->forget('2fa_verified');
        session()->forget('2fa_secret');

        return redirect('/');
    }
}
