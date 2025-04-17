<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rank;
use App\Models\RankChange;
use App\Models\Parking;
use App\Models\ParkData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'web']);
    }

    public function getStats(Request $request)
    {
        // Alapértelmezett rendezés név szerint
        $sortColumn = $request->get('sort', 'charactername');
        $sortDirection = $request->get('direction', 'asc');

        // Legmagasabb és legalacsonyabb rangok lekérése
        $lowestRank = Rank::orderBy('id', 'asc')->first();
        $highestRank = Rank::orderBy('id', 'desc')->first();

        // Összes felhasználó lekérése
        $users = User::with(['rank', 'subdivisions'])
            ->where('status', 'active');

        // Rendezés beállítása
        if ($sortColumn === 'charactername') {
            $users = $users->orderBy('charactername', $sortDirection);
        } elseif ($sortColumn === 'plus_points') {
            $users = $users->orderBy('plus_points', $sortDirection)
                         ->orderBy('charactername', 'asc');
        } elseif ($sortColumn === 'warnings') {
            $users = $users->orderBy('warnings', $sortDirection)
                         ->orderBy('charactername', 'asc');
        } elseif ($sortColumn === 'last_rank_up') {
            $users = $users->leftJoin('rank_changes', function($join) {
                $join->on('users.id', '=', 'rank_changes.user_id')
                     ->whereRaw('rank_changes.id = (SELECT id FROM rank_changes rc2 WHERE rc2.user_id = users.id ORDER BY created_at DESC LIMIT 1)');
            })
            ->orderBy('rank_changes.created_at', $sortDirection)
            ->orderBy('users.charactername', 'asc')
            ->select('users.*');
        } elseif ($sortColumn === 'days_in_rank') {
            $users = $users->orderBy('last_rank_change', $sortDirection)
                         ->orderBy('charactername', 'asc');
        } elseif ($sortColumn === 'total_service_time') {
            $users = $users->orderBy('service_time', $sortDirection)
                         ->orderBy('charactername', 'asc');
        }

        $users = $users->get()
            ->map(function ($user) use ($lowestRank, $highestRank) {
                $role = 'Tag';
                $isWebmaster = false;

                if ($user->is_superadmin) {
                    $role = 'Webmester';
                    $isWebmaster = true;
                } elseif ($user->isAdmin) {
                    $role = 'Leader';
                } elseif ($user->is_officer) {
                    $role = 'Tiszt';
                }

                // Utolsó rangemelés lekérése
                $lastRankChange = RankChange::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Rang ellenőrzések
                $isMaxRank = $user->rank_id === $highestRank?->id;
                $isMinRank = $user->rank_id === $lowestRank?->id;

                // Alosztályok feldolgozása
                $subdivisions = $user->subdivisions->map(function ($subdivision) {
                    return [
                        'id' => $subdivision->id,
                        'name' => $subdivision->name,
                        'color' => $subdivision->color
                    ];
                });

                // Eltöltött napok számítása
                $daysInRank = $user->last_rank_change 
                    ? now()->diffInDays($user->last_rank_change)
                    : 0;

            // Szolgálati idő percben
                $serviceMinutes = $user->service_time ?? 0;

                return [
                    'id' => $user->id,
                    'charactername' => $user->charactername,
                    'rank' => $isWebmaster ? 'NR' : ($user->rank->name ?? 'Nincs rang'),
                    'rank_id' => $isWebmaster ? 999999 : ($user->rank_id ?? 0),
                    'rank_color' => $isWebmaster ? null : ($user->rank->color ?? '#6B7280'),
                    'subdivisions' => $isWebmaster ? [] : $subdivisions,
                    'plus_points' => $isWebmaster ? 'NR' : $user->plus_points,
                    'warnings' => $isWebmaster ? 'NR' : '0',
                    'last_rank_up' => $isWebmaster ? 'NR' : ($lastRankChange ? date('Y.m.d', strtotime($lastRankChange->created_at)) : '-'),
                    'days_in_rank' => $isWebmaster ? 'NR' : $daysInRank,
                    'last_service' => $isWebmaster ? 'NR' : '-',
'total_service_time' => $isWebmaster ? 'NR' : $serviceMinutes,
                    'role' => $role,
                    'is_max_rank' => $isWebmaster ? true : $isMaxRank,
                    'is_min_rank' => $isWebmaster ? true : $isMinRank,
                    'promotion_days' => $isWebmaster ? 'NR' : ($user->custom_promotion_days ?? ($user->rank ? $user->rank->promotion_days : 0))
                ];
            });

        return response()->json([
            'users' => $users,
            'currentSort' => [
                'column' => $sortColumn,
                'direction' => $sortDirection
            ]
        ]);
    }

    public function index()
    {
        $ranks = Rank::orderBy('id', 'asc')->get();
        $subdivisions = DB::table('subdivisions')->orderBy('name', 'asc')->get();
        
        return view('leader.index', compact('ranks', 'subdivisions'));
    }

    public function promoteUser(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($userId);
            
            // Ha nincs rangja, adjuk neki az első rangot
            if (!$user->rank_id) {
                $firstRank = Rank::orderBy('id', 'asc')->first();
                if ($firstRank) {
                    // Pontok alapján csökkentés (maximum 15 nap)
                    $maxReduction = 15;
                    $pointsReduction = min($user->plus_points * 3, $maxReduction);
                    
                    // Beállítjuk a custom_promotion_days értéket
                    $user->custom_promotion_days = max(0, $firstRank->promotion_days - $pointsReduction);
                    
                    // Frissítjük a last_rank_change dátumot
                    $user->last_rank_change = now();
                    
                    $this->updateUserRank($user, null, $firstRank->id);
                    DB::commit();
                    return response()->json(['success' => true, 'message' => 'Sikeres előléptetés!']);
                }
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Nincs elérhető rang!'], 400);
            }

            // Következő rang keresése
            $nextRank = Rank::where('id', '>', $user->rank_id)
                ->orderBy('id', 'asc')
                ->first();

            if (!$nextRank) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'A felhasználó már a legmagasabb rangon van!'], 400);
            }

            // Pontok levonása (maximum 5 pont, de nem mehet minuszba)
            $user->plus_points = max(0, $user->plus_points - 5);

            // Pontok alapján csökkentés (maximum 15 nap)
            $maxReduction = 15;
            $pointsReduction = min($user->plus_points * 3, $maxReduction);
            
            // Beállítjuk a custom_promotion_days értéket
            $user->custom_promotion_days = max(0, $nextRank->promotion_days - $pointsReduction);
            
            // Frissítjük a last_rank_change dátumot
            $user->last_rank_change = now();

            $this->updateUserRank($user, $user->rank_id, $nextRank->id);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sikeres előléptetés!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt az előléptetés során: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Hiba történt az előléptetés során!'], 500);
        }
    }

    public function demoteUser(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($userId);

            if (!$user->rank_id) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'A felhasználónak nincs rangja!'], 400);
            }

            // Előző rang keresése
            $previousRank = Rank::where('id', '<', $user->rank_id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$previousRank) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'A felhasználó már a legalacsonyabb rangon van!'], 400);
            }

            // Pontok alapján csökkentés (maximum 15 nap)
            $maxReduction = 15;
            $pointsReduction = min($user->plus_points * 3, $maxReduction);
            
            // Beállítjuk a custom_promotion_days értéket
            $user->custom_promotion_days = max(0, $previousRank->promotion_days - $pointsReduction);
            
            // Frissítjük a last_rank_change dátumot
            $user->last_rank_change = now();

            $this->updateUserRank($user, $user->rank_id, $previousRank->id);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sikeres lefokozás!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt a lefokozás során: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Hiba történt a lefokozás során!'], 500);
        }
    }

    private function updateUserRank($user, $oldRankId, $newRankId)
    {
        // Rang frissítése
        $user->rank_id = $newRankId;
        $user->last_rank_change = now();
        $user->save();

        // Rang változás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $oldRankId,
            'new_rank_id' => $newRankId,
            'changed_by' => auth()->id()
        ]);
    }

    public function addPoint(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($userId);
            $user->plus_points++;

            // Frissítjük a custom_promotion_days értéket
            if ($user->custom_promotion_days === null) {
                $user->custom_promotion_days = $user->rank->promotion_days;
            }

            // Kiszámoljuk az új értéket, de maximum 15 napot vonhatunk le
            $basePromotionDays = $user->rank->promotion_days;
            $maxReduction = 15; // Maximum 15 nap levonás
            $reduction = min($user->plus_points * 3, $maxReduction); // Plusz pontonként 3 nap, de max 15
            $user->custom_promotion_days = $basePromotionDays - $reduction;

            $user->save();
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pont sikeresen hozzáadva!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt a pont hozzáadása során: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Hiba történt a pont hozzáadása során!'], 500);
        }
    }

    public function removePoint(Request $request, $userId)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($userId);
            
            // Ellenőrizzük, hogy van-e még levonható pont
            if ($user->plus_points <= 0) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Nincs több levonható pont!'], 400);
            }

            $user->plus_points--;

            // Frissítjük a custom_promotion_days értéket
            if ($user->custom_promotion_days === null) {
                $user->custom_promotion_days = $user->rank->promotion_days;
            }

            // Újraszámoljuk az értéket a csökkentett pontszámmal
            $basePromotionDays = $user->rank->promotion_days;
            $maxReduction = 15; // Maximum 15 nap levonás
            $reduction = min($user->plus_points * 3, $maxReduction); // Plusz pontonként 3 nap, de max 15
            $user->custom_promotion_days = $basePromotionDays - $reduction;

            $user->save();
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pont sikeresen levonva!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt a pont levonása során: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Hiba történt a pont levonása során!'], 500);
        }
    }

    public function park()
    {
        // Parkolóhelyek lekérése a konfigból
        $parkingConfig = config('parking.spots');
        
        // Parkolóhelyek lekérése az adatbázisból
        $parkingSpots = Parking::all()->keyBy('spot_id');

        // Összefésüljük a config és adatbázis adatokat
        $spots = collect($parkingConfig)->map(function ($spot) use ($parkingSpots) {
            $dbSpot = $parkingSpots->get($spot['id']);
            
            return [
                'id' => $spot['id'],
                'owner' => $dbSpot ? $dbSpot->owner_name : null,
                'request_date' => $dbSpot && $dbSpot->request_date ? $dbSpot->request_date->format('Y.m.d') : null,
                'is_occupied' => $dbSpot ? $dbSpot->is_occupied : false,
                'position' => $spot['position'],
                'number' => $spot['number'],
                'rotation' => $spot['rotation'] ?? 0
            ];
        });

        // A bejelentkezett felhasználó által foglalt parkolóhelyek
        $owned = Parking::where('owner_name', Auth::user()->name)
            ->where('is_occupied', true)
            ->pluck('spot_id')
            ->toArray();

        return view('leader.park', [
            'spots' => $spots,
            'owned' => $owned
        ]);
    }
}
