<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    public function getOccupancy()
    {
        try {
            $totalSpots = Parking::count();
            $occupiedSpots = Parking::where('is_occupied', true)->count();
            $percentage = $totalSpots > 0 ? round(($occupiedSpots / $totalSpots) * 100) : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalSpots,
                    'occupied' => $occupiedSpots,
                    'percentage' => $percentage
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a foglaltsági adatok lekérése közben'
            ], 500);
        }
    }

    public function requestSpot(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|string'
        ]);

        $parking = Parking::where('spot_id', $request->spot_id)->first();
        
        if (!$parking) {
            return response()->json(['success' => false, 'message' => 'Parkolóhely nem található'], 404);
        }

        if ($parking->is_occupied) {
            return response()->json(['success' => false, 'message' => 'A parkolóhely már foglalt'], 400);
        }

        // Ellenőrizzük, hogy a felhasználónak van-e már foglalt parkolóhelye
        $existingSpot = Parking::where('owner', Auth::user()->name)
            ->where('is_occupied', true)
            ->first();

        if ($existingSpot) {
            return response()->json([
                'success' => false, 
                'message' => 'Már van egy foglalt parkolóhelyed (' . $existingSpot->spot_id . ')'
            ], 400);
        }

        try {
            $parking->update([
                'owner' => Auth::user()->name,
                'handled_by' => Auth::user()->name,
                'request_date' => now(),
                'is_occupied' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Parkolóhely sikeresen lefoglalva'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a parkolóhely foglalása közben'
            ], 500);
        }
    }

    public function releaseSpot(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|string'
        ]);

        $parking = Parking::where('spot_id', $request->spot_id)->first();
        
        if (!$parking) {
            return response()->json(['success' => false, 'message' => 'Parkolóhely nem található'], 404);
        }

        if (!$parking->is_occupied) {
            return response()->json(['success' => false, 'message' => 'A parkolóhely már szabad'], 400);
        }

        // Csak a tulajdonos vagy admin mondhatja le a foglalást
        if ($parking->owner !== Auth::user()->name && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs jogosultságod a parkolóhely lemondásához'
            ], 403);
        }

        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a parkolóhely felszabadítása közben'
            ], 500);
        }
    }
}
