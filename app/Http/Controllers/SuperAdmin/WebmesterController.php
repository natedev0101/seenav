<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebmesterController extends Controller
{
    public function index()
    {
        // Lekérjük az admin és superadmin felhasználókat
        $admins = User::where('isAdmin', true)
            ->orWhere('is_superadmin', true)
            ->get(['id', 'username', 'two_factor_required']);

        // Ellenőrizzük az aktuális státuszt (az első admin alapján)
        $isEnabled = $admins->first()?->two_factor_required ?? false;

        return view('superadmin.webmester', [
            'admins' => $admins,
            'isEnabled' => $isEnabled
        ]);
    }

    public function toggle2FA(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $required = (bool) $request->input('enabled');
            
            Log::info('2FA toggle requested', [
                'required' => $required,
                'request_data' => $request->all()
            ]);
            
            // Minden admin és superadmin felhasználó 2FA követelményének frissítése
            $affected = DB::table('users')
                ->where(function($query) {
                    $query->where('isAdmin', 1)
                          ->orWhere('is_superadmin', 1);
                })
                ->update(['two_factor_required' => $required]);
            
            Log::info('2FA toggle completed', [
                'affected_users' => $affected
            ]);
            
            DB::commit();
            
            return redirect()->route('superadmin.webmester')->with('status', [
                'type' => 'success',
                'message' => $required 
                    ? '2FA hitelesítés kötelezővé téve az adminok számára.' 
                    : '2FA hitelesítés opcionálissá téve az adminok számára.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('2FA toggle failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('superadmin.webmester')->with('status', [
                'type' => 'error',
                'message' => 'Hiba történt a 2FA beállítások módosítása közben: ' . $e->getMessage()
            ]);
        }
    }
}
