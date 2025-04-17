<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\HelpRequestMail;

class HelpRequestController extends Controller
{
    // Módosítjuk a címzett email címét localhost-ra
    protected $webmasterEmail = 'natedev@mws.hu';

    public function sendRequest(Request $request)
    {
        Log::info('Help request received', [
            'user' => Auth::user()->name,
            'request' => $request->all()
        ]);

        try {
            $request->validate([
                'problem' => 'required|string|max:1000'
            ]);

            $user = Auth::user();
            
            // Email küldése
            Mail::to($this->webmasterEmail)->send(new HelpRequestMail(
                $user,
                $request->problem
            ));

            // Log létrehozása
            Log::info('2FA Help Request Sent', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'problem' => $request->problem,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

            return response()->json([
                'message' => 'A segítségkérés sikeresen elküldve! Hamarosan felvesszük Önnel a kapcsolatot.'
            ]);
        } catch (\Exception $e) {
            Log::error('2FA Help Request Error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Hiba történt a segítségkérés küldése során: ' . $e->getMessage()
            ], 500);
        }
    }
}
