<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function setup()
    {
        $user = Auth::user();

        if ($user->two_factor_secret) {
            return redirect()->route('dashboard')
                ->with('error', 'A két faktoros hitelesítés már be van állítva!');
        }

        // Hosszabb kulcs generálása, minimum 16 karakter
        $secret = str_pad($this->google2fa->generateSecretKey(), 16, 'A', STR_PAD_RIGHT);
        
        // QR kód URL generálása
        $otpUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        
        // QR kód kép generálása
        $qrCodeSvg = 'data:image/png;base64,' . base64_encode(file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($otpUrl)));

        session(['2fa_secret' => $secret]);

        return view('auth.2fa.setup', [
            'qrCodeSvg' => $qrCodeSvg,
            'secret' => $secret
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $secret = session('2fa_secret');

        if (!$secret) {
            return back()->withErrors(['error' => 'Érvénytelen munkamenet. Kérjük, próbálja újra!']);
        }

        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $user->two_factor_secret = $secret;
            $user->two_factor_recovery_codes = $this->generateRecoveryCodes();
            $user->save();

            session()->forget('2fa_secret');
            session(['2fa_verified' => true]);

            return redirect()->route('dashboard')
                ->with('status', 'A két faktoros hitelesítés sikeresen beállítva!');
        }

        return back()->withErrors(['code' => 'A megadott kód érvénytelen.']);
    }

    public function showVerify()
    {
        if (session('2fa_verified')) {
            return redirect()->route('dashboard');
        }

        return view('auth.2fa.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            session(['2fa_verified' => true]);
            
            // Ha van intended URL, oda irányítunk
            if ($url = session('url.intended')) {
                session()->forget('url.intended');
                return redirect($url)
                    ->with('status', 'Sikeres hitelesítés! Üdvözöljük a rendszerben.');
            }

            // Ha nincs intended URL, akkor a dashboard-ra
            return redirect()->route('dashboard')
                ->with('status', 'Sikeres hitelesítés! Üdvözöljük a rendszerben.');
        }

        return back()->withErrors(['code' => 'A megadott kód érvénytelen.']);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();

        // Ha kötelező a 2FA, nem lehet kikapcsolni
        if ($user->two_factor_required) {
            return back()->withErrors(['error' => 'A két faktoros hitelesítés kötelező, nem kapcsolható ki.']);
        }

        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valid) {
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->save();

            session()->forget([
                '2fa_verified',
                '2fa_secret',
                'url.intended',
                'auth.2fa.remember'
            ]);

            return redirect()->route('profile.edit')
                ->with('status', 'A két faktoros hitelesítés sikeresen kikapcsolva.');
        }

        return back()->withErrors(['code' => 'A megadott kód érvénytelen.']);
    }

    protected function generateRecoveryCodes()
    {
        return collect()->times(8, function () {
            return Str::random(10);
        })->toJson();
    }
}
