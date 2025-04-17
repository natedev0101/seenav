<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        try {
            Mail::raw('Ez egy teszt email a SeeNAV rendszerből.', function($message) {
                $message->to('natedev@mws.hu')
                    ->subject('SeeNAV Email Teszt');
            });

            return response()->json(['message' => 'Email sikeresen elküldve!']);
        } catch (\Exception $e) {
            \Log::error('Email küldési hiba: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
