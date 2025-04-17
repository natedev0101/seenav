<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::latest()->paginate(10);

        return view('logs.index', compact('logs'));
    }

    public function destroy(Log $log)
    {
        // Törli a megadott log bejegyzést
        $log->delete();
    
        // Ellenőrizzük, hogy maradt-e még bejegyzés
        if (Log::count() === 0) {
            // Reseteljük az auto-increment értéket
            DB::statement('ALTER TABLE logs AUTO_INCREMENT = 1');
        }
    
        // Visszairányítás sikeres üzenettel
        return redirect()->route('logs.index')->with('success', 'A naplóbejegyzés sikeresen törölve!');
    }
};