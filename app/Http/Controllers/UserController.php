<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RankChange;
use App\Mail\WebmasterPasswordResetAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $fixedUsers = User::where('is_superadmin', true)
    ->orWhere('isAdmin', true)
    ->orWhere('is_officer', true)
    ->orderBy('charactername')
    ->get(); // Nem lapozzuk

$pagedUsers = User::where('is_superadmin', false)
    ->where('isAdmin', false)
    ->where('is_officer', false)
    ->orderBy('charactername')
    ->paginate(15); // Végtelen görgetéshez AJAX-ot használunk

return view('users.index', compact('fixedUsers', 'pagedUsers'));
       
        
    }
    
    public function search(Request $request)
    {
        $search = $request->input('query');
        $users = User::where('charactername', 'LIKE', "%$search%")
            ->orderBy('charactername')
            ->get();
    
        return response()->json($users);
    }
    
    public function show($id)
    {
        // Ha tömb jön, vegyük az első elemét
        if (is_array($id)) {
            $id = $id[0];
        }
        
        $user = User::findOrFail($id);
        $ranks = \App\Models\Rank::all();
        $subdivisions = \App\Models\Subdivision::all();

        return view('users.show', [
            'user' => $user,
            'ranks' => $ranks,
            'subdivisions' => $subdivisions
        ]);
    }
    public function onlineUsers()
    {
        $onlineUsers = User::where('is_online', true)->get();
    
        return view('online-users', [
            'onlineUsers' => $onlineUsers,
        ]);
    }

    public function updateBadges(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'badge_number' => 'required|integer|min:0'
        ]);

        $user->update([
            'badge_number' => $request->badge_number
        ]);

        return back()->with('success', 'Jelvények száma sikeresen frissítve.');
    }

    public function updatePhone(Request $request, User $user)
    {
        $request->validate([
            'phone' => 'required|string|max:255'
        ]);

        $user->update([
            'phone' => $request->phone
        ]);

        return response()->json(['message' => 'Telefonszám sikeresen frissítve']);
    }

    public function updateSubdivisions(Request $request, User $user)
    {
        $request->validate([
            'subdivisions' => 'array',
            'subdivisions.*' => 'exists:subdivisions,id',
            'subdivision_ids' => 'array',
            'subdivision_ids.*' => 'exists:subdivisions,id'
        ]);

        // Mindkét paraméternevet elfogadjuk
        $subdivisionIds = $request->subdivisions ?? $request->subdivision_ids ?? [];

        $user->subdivisions()->sync($subdivisionIds);

        return response()->json([
            'message' => 'Alosztályok sikeresen frissítve',
            'subdivisions' => $user->subdivisions
        ]);
    }

    /**
     * Felhasználó rangjának közvetlen frissítése
     */
    public function updateRank(User $user, Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Nincs jogosultságod a rang módosításához.'], 403);
        }

        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id'
        ]);

        $oldRankId = $user->rank_id;
        $newRankId = $validated['rank_id'];

        // Csak akkor végezzük el a módosítást, ha tényleg változott a rang
        if ($oldRankId !== $newRankId) {
            // Rang változtatás naplózása
            RankChange::create([
                'user_id' => $user->id,
                'old_rank_id' => $oldRankId,
                'new_rank_id' => $newRankId,
                'changed_by' => auth()->id()
            ]);

            // Pontok levonása (maximum 5 pont, de nem mehet minuszba)
            $user->plus_points = max(0, $user->plus_points - 5);

            $user->rank_id = $newRankId;
            $user->last_rank_change = now();

            // Promotion days frissítése
            if (!$newRankId) {
                $user->custom_promotion_days = 0;
            } else {
                $basePromotionDays = \App\Models\Rank::find($newRankId)->promotion_days ?? 0;
                $maxReduction = 15;
                $reduction = min($user->plus_points * 3, $maxReduction);
                $user->custom_promotion_days = max(0, $basePromotionDays - $reduction);
            }

            $user->save();
        }

        return response()->json(['message' => 'Rang sikeresen frissítve!']);
    }

    /**
     * Felhasználó rangjának frissítése
     */
    public function updateRankOld(Request $request, $id)
    {
        $validated = $request->validate([
            'rank_id' => 'nullable|exists:ranks,id'
        ]);

        $user = User::findOrFail($id);
        $oldRankId = $user->rank_id;
        $newRankId = $validated['rank_id'];

        // Csak akkor végezzük el a módosítást, ha tényleg változott a rang
        if ($oldRankId !== $newRankId) {
            // Rang változtatás naplózása
            RankChange::create([
                'user_id' => $user->id,
                'old_rank_id' => $oldRankId,
                'new_rank_id' => $newRankId,
                'changed_by' => auth()->id()
            ]);

            // Pontok levonása (maximum 5 pont, de nem mehet minuszba)
            $user->plus_points = max(0, $user->plus_points - 5);

            $user->rank_id = $newRankId;
            $user->last_rank_change = now();

            // Promotion days frissítése
            if (!$newRankId) {
                $user->custom_promotion_days = 0;
            } else {
                $basePromotionDays = \App\Models\Rank::find($newRankId)->promotion_days ?? 0;
                $maxReduction = 15;
                $reduction = min($user->plus_points * 3, $maxReduction);
                $user->custom_promotion_days = max(0, $basePromotionDays - $reduction);
            }

            $user->save();
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rang sikeresen frissítve']);
        }

        return back()->with('success', 'Rang sikeresen frissítve');
    }

    public function updateSubdivisionsOld(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'subdivision_ids' => 'nullable|array',
            'subdivision_ids.*' => 'exists:subdivisions,id'
        ]);

        $user->subdivisions()->sync($request->subdivision_ids ?? []);

        return back()->with('success', 'Alosztályok sikeresen frissítve.');
    }

    /**
     * Játékadatok frissítése
     */
    public function updateGameData(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validatedData = $request->validate([
            'field' => 'required|string|in:played_minutes,badge_number,created_at,phone_number,character_id',
            'value' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->field === 'badge_number') {
                        if (!preg_match('/^\d+$/', $value)) {
                            $fail('A jelvényszám csak számokat tartalmazhat!');
                        }
                        if (strlen($value) > 20) {
                            $fail('A jelvényszám maximum 20 karakter lehet!');
                        }
                    }
                    if ($request->field === 'phone_number') {
                        if (!preg_match('/^\d+$/', $value)) {
                            $fail('A telefonszám csak számokat tartalmazhat!');
                        }
                        if (strlen($value) > 30) {
                            $fail('A telefonszám maximum 30 karakter lehet!');
                        }
                    }
                    if ($request->field === 'character_id') {
                        if (!preg_match('/^\d+$/', $value)) {
                            $fail('A Character ID csak számokat tartalmazhat!');
                        }
                    }
                },
            ],
        ]);

        try {
            $user->update([$validatedData['field'] => $validatedData['value']]);
            return response()->json(['message' => 'Adat sikeresen frissítve']);
        } catch (\Exception $e) {
            Log::error('Hiba történt a játékadatok frissítésekor: ' . $e->getMessage());
            return response()->json(['error' => 'Hiba történt az adatok frissítésekor'], 500);
        }
    }

    /**
     * Ellenőrzi, hogy a felhasználó a legmagasabb vagy legalacsonyabb rangon van-e
     */
    public function getRankLimits(User $user)
    {
        $ranks = \App\Models\Rank::orderBy('id')->get();
        $currentRank = $user->rank;

        if (!$currentRank) {
            return response()->json([
                'is_max_rank' => false,
                'is_min_rank' => true
            ]);
        }

        $isMaxRank = $currentRank->id === $ranks->max('id');
        $isMinRank = $currentRank->id === $ranks->min('id');

        return response()->json([
            'is_max_rank' => $isMaxRank,
            'is_min_rank' => $isMinRank
        ]);
    }

    /**
     * Előlépteti a felhasználót a következő rangra
     */
    public function promote(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Nincs jogosultságod az előléptetéshez.'], 403);
        }

        $ranks = \App\Models\Rank::orderBy('id')->get();
        $currentRank = $user->rank;

        if (!$currentRank) {
            $newRank = $ranks->first();
        } else {
            $newRank = $ranks->where('id', '>', $currentRank->id)->first();
        }

        if (!$newRank) {
            return response()->json(['message' => 'A felhasználó már a legmagasabb rangon van.'], 400);
        }

        // Rang változtatás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $currentRank?->id,
            'new_rank_id' => $newRank->id,
            'changed_by' => auth()->id()
        ]);

        $user->rank_id = $newRank->id;
        $user->last_rank_change = now();
        $user->save();

        return response()->json(['message' => 'Sikeres előléptetés!']);
    }

    /**
     * Lefokozza a felhasználót az előző rangra
     */
    public function demote(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Nincs jogosultságod a lefokozáshoz.'], 403);
        }

        $ranks = \App\Models\Rank::orderBy('id')->get();
        $currentRank = $user->rank;

        if (!$currentRank) {
            return response()->json(['message' => 'A felhasználó már a legalacsonyabb rangon van.'], 400);
        }

        $newRank = $ranks->where('id', '<', $currentRank->id)->last();

        if (!$newRank) {
            return response()->json(['message' => 'A felhasználó már a legalacsonyabb rangon van.'], 400);
        }

        // Rang változtatás naplózása
        RankChange::create([
            'user_id' => $user->id,
            'old_rank_id' => $currentRank->id,
            'new_rank_id' => $newRank->id,
            'changed_by' => auth()->id()
        ]);

        $user->rank_id = $newRank->id;
        $user->last_rank_change = now();
        $user->save();

        return response()->json(['message' => 'Sikeres lefokozás!']);
    }

    /**
     * Reset user's password to a random string and force change on next login.
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $initiator = Auth::user();

        if ($user->is_superadmin) {
            try {
                // IP cím lekérése
                $ip = $request->getClientIp();
                if (empty($ip)) {
                    $ip = $request->ip();
                }
                if ($ip == '127.0.0.1' || $ip == 'localhost') {
                    $ip = $this->getServerIp();
                }

                Mail::to('natedev@mws.hu')->send(new WebmasterPasswordResetAttempt($user, $initiator, $ip));
                return back()->with('success', 'A jelszó visszaállítási kísérletről értesítettük a webmestert.');
            } catch (\Exception $e) {
                \Log::error('Email küldési hiba: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'target_email' => 'natedev@mws.hu'
                ]);
                return back()->with('error', 'Hiba történt az email küldése során. Kérjük próbálja újra később.');
            }
        }

        // Normál jelszó reset logika...
        $password = Str::random(12);
        $user->password = Hash::make($password);
        $user->force_password_change = true;
        $user->save();

        return back()
            ->with('success', 'A jelszó sikeresen visszaállítva.')
            ->with('password', $password);
    }

    /**
     * Get the server's public IP address
     */
    private function getServerIp()
    {
        try {
            $ip = file_get_contents('https://api.ipify.org');
            return $ip ?: 'Nem sikerült lekérni';
        } catch (\Exception $e) {
            return 'Nem sikerült lekérni';
        }
    }

    /**
     * Felhasználó nevének frissítése admin által
     */
    public function updateName(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin) {
            return response()->json(['message' => 'Nincs jogosultságod a név módosításához.'], 403);
        }

        $request->validate([
            'new_name' => 'required|string|max:255'
        ]);

        $oldName = $user->charactername;
        $newName = $request->new_name;

        // Csak akkor végezzük el a módosítást, ha tényleg változott a név
        if ($oldName !== $newName) {
            DB::transaction(function () use ($user, $oldName, $newName) {
                // Név változtatás mentése a name_change_requests táblába
                \App\Models\NameChangeRequest::create([
                    'user_id' => $user->id,
                    'current_name' => $oldName,
                    'requested_name' => $newName,
                    'reason' => 'Névváltási kérelmen kívül',
                    'status' => 'approved',
                    'admin_comment' => 'Névváltási kérelmen kívül',
                    'processed_by' => auth()->id(),
                    'processed_at' => now()
                ]);

                // Felhasználó nevének frissítése
                $user->update([
                    'charactername' => $newName
                ]);
            });
        }

        return response()->json(['message' => 'Név sikeresen frissítve!']);
    }
}