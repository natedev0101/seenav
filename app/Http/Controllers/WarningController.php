<?php

namespace App\Http\Controllers;

use App\Models\Warning;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WarningController extends Controller
{
    // Figyelmeztetés létrehozásának nézete
    public function create()
    {
        $users = User::all();
        $types = Warning::getTypes();
        return view('warnings.create', compact('users', 'types'));
    }

    // Figyelmeztetés tárolása
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:plusz_pont,minusz_pont,figyelmeztetés',
            'description' => 'required|string|max:500',
            'points' => 'required_if:type,plusz_pont,minusz_pont|nullable|integer|min:1|max:100',
            'expires_at' => 'required_if:type,figyelmeztetés|nullable|date',
        ]);
    
        // Ellenőrzés, hogy ne legyen Webmester kiválasztva
        $user = User::find($request->user_id);
        if ($user->is_superadmin) {
            return redirect()->back()->with('error', __('Webmestert nem lehet figyelmeztetni.'));
        }
    
        // Figyelmeztetés létrehozása
        $warning = Warning::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->id(),
            'type' => $request->type,
            'description' => $request->description,
            'points' => in_array($request->type, [Warning::TYPE_PLUS, Warning::TYPE_MINUS]) ? $request->points : null,
            'expires_at' => $request->type === Warning::TYPE_WARNING ? Carbon::parse($request->expires_at) : null,
        ]);

        // Értesítés létrehozása
        $notificationText = match($request->type) {
            Warning::TYPE_PLUS => "Plusz pontot kaptál (+{$request->points} pont)",
            Warning::TYPE_MINUS => "Minusz pontot kaptál (-{$request->points} pont)",
            Warning::TYPE_WARNING => "Figyelmeztetést kaptál",
        };

        Notification::create([
            'user_id' => $request->user_id,
            'title' => $notificationText,
            'content' => $request->description,
            'type' => 'warning',
            'data' => json_encode([
                'warning_id' => $warning->id,
                'type' => $request->type,
                'points' => $request->points,
                'expires_at' => $request->expires_at
            ])
        ]);
    
        return redirect()->back()->with('success', __('Figyelmeztetés sikeresen létrehozva.'));
    }

    // Figyelmeztetés részleteinek lekérése
    public function show(Warning $warning)
    {
        $this->authorize('view', $warning);
        
        return response()->json([
            'id' => $warning->id,
            'type' => $warning->type,
            'description' => $warning->description,
            'points' => $warning->points,
            'created_at' => $warning->created_at,
            'expires_at' => $warning->expires_at,
            'creator' => [
                'name' => $warning->admin->name,
                'profile_picture' => $warning->admin->profile_picture
            ]
        ]);
    }

    // Lejárt figyelmeztetések törlése
    public function deleteExpired()
    {
        $expiredWarnings = Warning::where('expires_at', '<', now())->get();
        foreach ($expiredWarnings as $warning) {
            $warning->delete();
        }
        return redirect()->back()->with('success', __('Lejárt figyelmeztetések törölve.'));
    }

    // Plusz pontok lekérdezése
    public function getPluszPontok()
    {
        $user = Auth::user();
        $pluszPontok = $user->warnings()->where('type', Warning::TYPE_PLUS)->get();
    
        return view('pluszPontok', compact('pluszPontok'));
    }
    
    public function getMinuszPontok()
    {
        $user = Auth::user();
        $minuszPontok = $user->warnings()->where('type', Warning::TYPE_MINUS)->get();
    
        return view('minuszPontok', compact('minuszPontok'));
    }
    
    public function getFigyelmeztetesek()
    {
        $user = Auth::user();
        $figyelmeztetesek = $user->warnings()->where('type', Warning::TYPE_WARNING)->get();
    
        return view('figyelmeztetesek', compact('figyelmeztetesek'));
    }
}
