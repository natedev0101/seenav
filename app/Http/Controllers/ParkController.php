<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ParkController extends Controller
{
    private $parkingData = [];
    
    public function __construct()
    {
        // Betöltjük a parkolóhelyek alapadatait a config fájlból
        $slots = Config::get('parking.slots', []);
        
        // Hozzáadjuk a tulajdonosokat (ezt továbbra is adatbázisban tároljuk)
        foreach ($slots as $slot) {
            $this->parkingData[$slot['id']] = [
                'id' => $slot['id'],
                'owner' => null, // Alapértelmezetten nincs tulajdonos
                'type' => $slot['type'],
                'panel' => $slot['panel'],
                'parkGroup' => $slot['parkGroup'],
                'x' => $slot['x'],
                'y' => $slot['y']
            ];
        }
    }

    public function index()
    {
        return view('leader.park', [
            'parkList' => $this->parkingData,
            'owned' => collect($this->parkingData)
                ->where('owner', Auth::user()->charactername)
                ->pluck('id')
        ]);
    }

    public function request(Request $request)
    {
        $validated = $request->validate([
            'parkId' => 'required|integer'
        ]);

        $parkId = $validated['parkId'];
        
        if (!isset($this->parkingData[$parkId])) {
            return response()->json(['error' => 'Érvénytelen parkolóhely!'], 400);
        }

        if ($this->parkingData[$parkId]['type'] !== 'free') {
            return response()->json(['error' => 'A parkolóhely már foglalt!'], 400);
        }

        $this->parkingData[$parkId]['owner'] = Auth::user()->charactername;
        $this->parkingData[$parkId]['type'] = 'reserved';

        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'parkId' => 'required|integer'
        ]);

        $parkId = $validated['parkId'];

        if (!isset($this->parkingData[$parkId])) {
            return response()->json(['error' => 'Érvénytelen parkolóhely!'], 400);
        }

        if ($this->parkingData[$parkId]['owner'] !== Auth::user()->charactername && !Auth::user()->isAdmin) {
            return response()->json(['error' => 'Nincs jogosultságod a művelethez!'], 403);
        }

        $this->parkingData[$parkId]['owner'] = null;
        $this->parkingData[$parkId]['type'] = 'free';

        return response()->json(['success' => true]);
    }
}               'error' => 'Érvénytelen parkolóhely!'
            ], 404);
        }

        if ($parking->owner !== Auth::id() && !Auth::user()->isAdmin) {
            return response()->json([
                'success' => false,
                'error' => 'Nincs jogosultságod a parkolóhely lemondásához!'
            ], 403);
        }

        $parking->update([
            'owner' => null,
            'handled_by' => null,
            'request_date' => null,
            'is_occupied' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parkolóhely sikeresen felszabadítva'
        ]);
    }
}