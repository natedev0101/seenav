<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rank;
use App\Models\User;
use App\Models\RankChange; // Új modell hozzáadása
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankController extends Controller
{
    // Rang lista oldal
    public function index()
    {
        $ranks = Rank::withCount('users')->get();
        return view('ranks.index', compact('ranks'));
    }

    // Rang létrehozása oldal
    public function create()
    {
        $nextId = Rank::getMaxId() + 1;
        return view('ranks.create', compact('nextId'));
    }

    public function store(Request $request)
    {
        // Jogosultság ellenőrzés (admin)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('ranks.index')->with('error', 'Nincs jogosultságod új rang létrehozásához!');
        }

        $request->validate([
            'name' => 'required|string|max:25|unique:ranks,name',
            'color' => 'required|string',
            'next_id' => 'required|integer|min:1',
            'salary_raw' => 'required|numeric|min:0',
            'promotion_days' => 'required|integer|min:0'
        ]);

        try {
            // Debug információk
            Log::info('Rang létrehozás adatok:', [
                'request_all' => $request->all(),
                'promotion_days' => $request->promotion_days
            ]);

            // Közvetlenül az adatbázisba szúrjuk be az ID-vel együtt
            $rank = DB::table('ranks')->insert([
                'id' => $request->next_id,
                'name' => $request->name,
                'color' => $request->color,
                'salary' => $request->salary_raw,
                'promotion_days' => (int)$request->promotion_days,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Debug információk
            Log::info('Rang létrehozva:', [
                'rank_data' => [
                    'id' => $request->next_id,
                    'name' => $request->name,
                    'salary' => $request->salary_raw,
                    'promotion_days' => (int)$request->promotion_days
                ]
            ]);

            // Logolás
            logAction('Rang létrehozás', "Új rang létrehozva: {$request->name}, ID: {$request->next_id}");

            return redirect()->route('ranks.index')
                ->with('success', 'A rang sikeresen létrehozva!');
        } catch (\Exception $e) {
            // Hiba esetén
            Log::error('Rang létrehozás hiba:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            logAction('Rang létrehozás hiba', "Hiba: {$e->getMessage()}");
            
            return redirect()->route('ranks.index')
                ->with('error', 'Hiba történt a rang létrehozása közben.');
        }
    }

    public function destroy(Rank $rank)
    {
        // Jogosultság ellenőrzés (admin)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('ranks.index')->with('error', 'Nincs jogosultságod a rang törléséhez!');
        }

        try {
            // Rang ID mentése a törlés előtt
            $deletedId = $rank->id;
            $rankName = $rank->name;

            // Rang törlése
            $rank->delete();
            
            // Logolás
            logAction('Rang törlés', "Törölt rang: {$rankName}, ID: {$deletedId}, Törölte: " . auth()->user()->charactername);
            
            return redirect()->route('ranks.index')
                ->with('success', 'A rang sikeresen törölve!');
        } catch (\Exception $e) {
            // Hiba esetén
            logAction('Rang törlés hiba', "Rang: {$rank->name}, Hiba: {$e->getMessage()}");
            
            return redirect()->route('ranks.index')
                ->with('error', 'Hiba történt a rang törlése közben.');
        }
    }

    // Rang szerkesztése oldal
    public function edit(Rank $rank)
    {
        // Jogosultság ellenőrzés (admin)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('ranks.index')->with('error', 'Nincs jogosultságod a rang szerkesztéséhez!');
        }

        return view('ranks.edit', compact('rank'));
    }

    // Rang frissítése
    public function update(Request $request, Rank $rank)
    {
        // Jogosultság ellenőrzés (admin)
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('ranks.index')->with('error', 'Nincs jogosultságod a rang módosításához!');
        }

        $request->validate([
            'name' => 'required|string|max:25|unique:ranks,name,' . $rank->id,
            'color' => 'required|string',
            'salary_raw' => 'required|numeric|min:0|max:10000000',
            'promotion_days' => 'required|integer|min:0'
        ]);

        try {
            $oldName = $rank->name;
            
            // Debug információk
            Log::info('Rang frissítés adatok:', [
                'request_all' => $request->all(),
                'request_promotion_days' => $request->promotion_days,
                'request_promotion_days_type' => gettype($request->promotion_days),
                'old_rank' => $rank->toArray()
            ]);

            // Adatok előkészítése
            $updateData = [
                'name' => $request->name,
                'color' => $request->color,
                'salary' => $request->salary_raw,
                'promotion_days' => (int)$request->promotion_days
            ];

            // Debug az update előtt
            Log::info('Update data előkészítve:', [
                'update_data' => $updateData,
                'promotion_days_type' => gettype($updateData['promotion_days'])
            ]);

            // Frissítés
            $rank->update($updateData);
            
            // Debug információk
            Log::info('Rang frissítés után:', [
                'updated_rank' => $rank->fresh()->toArray(),
                'update_data' => $updateData,
                'final_promotion_days' => $rank->fresh()->promotion_days,
                'final_promotion_days_type' => gettype($rank->fresh()->promotion_days)
            ]);
            
            // Logolás
            logAction('Rang szerkesztés', "Rang módosítva: {$oldName} -> {$rank->name}, Módosította: " . auth()->user()->charactername);
            
            return redirect()->route('ranks.index')
                ->with('success', 'A rang sikeresen módosítva!');
        } catch (\Exception $e) {
            // Hiba esetén
            Log::error('Rang frissítés hiba:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            logAction('Rang szerkesztés hiba', "Rang: {$rank->name}, Hiba: {$e->getMessage()}");
            
            return redirect()->route('ranks.index')
                ->with('error', 'Hiba történt a rang módosítása közben.');
        }
    }

    // Rang hozzárendelése felhasználókhoz
    public function assign()
    {
        $users = User::with('rank')->get();
        $ranks = Rank::all();
        return view('ranks.assign', compact('users', 'ranks'));
    }

    public function assignToUser(Request $request, User $user)
    {
        $request->validate([
            'rank_id' => 'required|exists:ranks,id',
        ]);

        $oldRankId = $user->rank_id;
        $user->rank_id = $request->rank_id;
        $this->updatePromotionDays($user);
        $user->save();

        // Rang változtatás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $oldRankId,
            'new_rank_id' => $request->rank_id,
            'changed_by' => auth()->id()
        ]);

        return redirect()->route('ranks.assign')->with('success', 'Rang hozzárendelve a felhasználóhoz!');
    }

    public function updateRank(Request $request, $id)
    {
        $request->validate([
            'rank_id' => 'nullable|exists:ranks,id', // A kiválasztott rangnak léteznie kell
        ]);

        $user = User::findOrFail($id); // A felhasználó keresése
        $oldRankId = $user->rank_id; // Régi rang mentése
        $newRankId = $request->input('rank_id'); // Új rang

        // Rang változtatás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $oldRankId,
            'new_rank_id' => $newRankId,
            'changed_by' => auth()->id()
        ]);

        $user->rank_id = $newRankId; // Új rang hozzárendelése
        $user->last_rank_change = now(); // Rang változtatás időpontjának rögzítése
        $this->updatePromotionDays($user);
        $user->save(); // Mentés az adatbázisba

        return redirect()->back()->with('success', 'A rang sikeresen frissítve!');
    }

    public function remove($id)
    {
        $user = User::findOrFail($id); // A felhasználó keresése
        
        // Rang változtatás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $user->rank_id,
            'new_rank_id' => null,
            'changed_by' => auth()->id()
        ]);

        $user->rank_id = null; // Rang eltávolítása
        $user->last_rank_change = now(); // Rang változtatás időpontjának rögzítése
        $this->updatePromotionDays($user);
        $user->save(); // Mentés az adatbázisba

        return redirect()->back()->with('success', 'A rang sikeresen eltávolítva!');
    }

    public function updateAll(Request $request)
    {
        $ranks = $request->input('ranks', []);
        $removeRanks = $request->input('remove_rank', []);

        foreach ($ranks as $userId => $rankId) {
            $user = User::find($userId);
            if ($user) {
                $oldRankId = $user->rank_id;
                $newRankId = isset($removeRanks[$userId]) ? null : $rankId;

                // Csak akkor naplózzuk, ha tényleg változott a rang
                if ($oldRankId !== $newRankId) {
                    // Rang változtatás naplózása
                    RankChange::create([
                        'user_id' => $user->id,
                        'old_rank_id' => $oldRankId,
                        'new_rank_id' => $newRankId,
                        'changed_by' => auth()->id()
                    ]);

                    $user->rank_id = $newRankId;
                    $user->last_rank_change = now();
                    $this->updatePromotionDays($user);
                    $user->save();
                }
            }
        }

        return redirect()->back()->with('success', 'A rangok sikeresen frissítve!');
    }

    // Privát metódus a promotion_days frissítésére
    private function updatePromotionDays($user)
    {
        // Ha nincs rang, akkor nincs promotion days
        if (!$user->rank_id) {
            $user->custom_promotion_days = 0;
            return;
        }

        // Alap promotion days a rang alapján
        $basePromotionDays = $user->rank->promotion_days ?? 0;

        // Módosítás a plus_points alapján (maximum 15 nap levonás)
        $maxReduction = 15;
        $reduction = min($user->plus_points * 3, $maxReduction);
        $user->custom_promotion_days = max(0, $basePromotionDays - $reduction);
    }

    // Rang felhasználóinak listázása
    public function showUsers(Request $request, Rank $rank)
    {
        $search = $request->input('search');
        
        $users = $rank->users()
            ->when($search, function ($query) use ($search) {
                return $query->where('charactername', 'like', '%' . $search . '%');
            })
            ->orderBy('charactername')
            ->paginate(20);
            
        return view('ranks.users', compact('rank', 'users', 'search'));
    }

    public function getUsersJson(Rank $rank)
    {
        // Csak admin és superadmin férhet hozzá
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = $rank->users()->select('charactername', 'is_online')->get();
        
        return response()->json([
            'users' => $users
        ]);
    }
}
