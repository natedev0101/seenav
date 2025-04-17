<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DutyTime;
use App\Models\DutyTimeClosed;
use App\Models\User;
use App\Models\Rank;
use App\Models\Subdivision;
use Illuminate\Support\Facades\DB;

class DutyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentDuty = null;
        $weeklyDuration = 0;

        // Aktív szolgálat ellenőrzése
        $currentDuty = DutyTime::where('user_id', $user->id)
            ->where('is_weekly_closed', false)
            ->whereNull('ended_at')
            ->orderBy('started_at', 'desc')
            ->first();

        // Heti szolgálati idő számítása a nem lezárt szolgálatokból (másodpercekben)
        $weeklyDuration = DutyTime::where('user_id', $user->id)
            ->where('is_weekly_closed', false)
            ->whereBetween('started_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->sum('total_duration');

        // Ha van aktív szolgálat, annak idejét is hozzáadjuk (másodpercekben)
        if ($currentDuty && !$currentDuty->is_paused) {
            $weeklyDuration += Carbon::parse($currentDuty->started_at)->diffInSeconds(now()) - $currentDuty->total_pause_duration;
        }

        // Átváltjuk percekre a megjelenítéshez
        $weeklyDuration = floor($weeklyDuration / 60);

        // Frissítjük a felhasználó összes szolgálati idejét
        $this->updateUserServiceTime($user->id);

        // Lekérjük az aktív szolgálatban lévő felhasználókat
        $activeUsers = User::where('is_on_duty', true)
            ->with(['activeDuty' => function($query) {
                $query->where('is_weekly_closed', false)
                    ->whereNull('ended_at')
                    ->orderBy('started_at', 'desc');
            }])
            ->select('users.*')
            ->leftJoin('ranks', 'users.rank_id', '=', 'ranks.id')
            ->get();

        // Lekérjük az alosztályokat
        $userSubdivisions = DB::table('subdivision_user')
            ->join('subdivisions', 'subdivision_user.subdivision_id', '=', 'subdivisions.id')
            ->select('subdivision_user.user_id', 'subdivisions.name')
            ->whereIn('subdivision_user.user_id', $activeUsers->pluck('id'))
            ->get()
            ->groupBy('user_id');

        return view('duty.index', [
            'isOnDuty' => $currentDuty !== null && !$currentDuty->is_paused,
            'serviceStart' => $currentDuty ? $currentDuty->started_at : null,
            'weeklyDuration' => $weeklyDuration,
            'totalServiceTime' => $user->service_time,
            'activeUsers' => $activeUsers,
            'userSubdivisions' => $userSubdivisions,
            'isAdmin' => $user->isAdmin,
            'isOfficer' => $user->is_officer
        ]);
    }

    public function startDuty()
    {
        $user = Auth::user();
        $existingDuty = DutyTime::where('user_id', $user->id)
            ->where('is_weekly_closed', false)
            ->whereNull('ended_at')
            ->orderBy('started_at', 'desc')
            ->first();

        if (!$existingDuty) {
            DutyTime::create([
                'user_id' => $user->id,
                'started_at' => now(),
                'total_pause_duration' => 0,
                'is_paused' => false,
                'is_weekly_closed' => false
            ]);

            $user->is_on_duty = true;
            $user->save();
        } elseif ($existingDuty->is_paused) {
            // Ha szüneteltetve volt, folytatjuk
            $existingDuty->is_paused = false;
            $existingDuty->save();

            $user->is_on_duty = true;
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    public function endDuty()
    {
        $user = Auth::user();
        $currentDuty = DutyTime::where('user_id', $user->id)
            ->where('is_weekly_closed', false)
            ->whereNull('ended_at')
            ->orderBy('started_at', 'desc')
            ->first();

        if ($currentDuty && !$currentDuty->is_paused && !$currentDuty->ended_at) {
            $endTime = now();
            $totalDurationSeconds = Carbon::parse($currentDuty->started_at)->diffInSeconds($endTime);
            
            // Frissítjük az aktuális szolgálatot
            $currentDuty->ended_at = $endTime;
            $currentDuty->total_duration = $totalDurationSeconds - $currentDuty->total_pause_duration;
            $currentDuty->save();

            // Felhasználó szolgálati idejének frissítése
            $user->is_on_duty = false;
            $user->save();

            // Frissítjük a felhasználó összes szolgálati idejét
            $this->updateUserServiceTime($user->id);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Frissíti a felhasználó összes szolgálati idejét a duty_times tábla alapján
     */
    private function updateUserServiceTime($userId)
    {
        // Összegezzük a befejezett szolgálatok idejét (másodpercekben)
        $totalDurationSeconds = DutyTime::where('user_id', $userId)
            ->whereNotNull('ended_at')
            ->sum('total_duration');

        // Átváltjuk percekre és frissítjük a felhasználó adatait
        $totalDurationMinutes = floor($totalDurationSeconds / 60);
        
        User::where('id', $userId)->update([
            'service_time' => $totalDurationMinutes
        ]);
    }

    public function closeWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Lezárt hét száma és év
        $weekNumber = $startOfWeek->weekOfYear;
        $year = $startOfWeek->year;

        // Áthelyezzük a heti szolgálatokat a lezárt táblába
        $weeklyDuties = DutyTime::where('is_weekly_closed', false)
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('ended_at')
            ->get();

        foreach ($weeklyDuties as $duty) {
            DutyTimeClosed::create([
                'user_id' => $duty->user_id,
                'started_at' => $duty->started_at,
                'ended_at' => $duty->ended_at,
                'total_duration' => $duty->total_duration,
                'total_pause_duration' => $duty->total_pause_duration,
                'week_number' => $weekNumber,
                'year' => $year
            ]);

            // Jelöljük a szolgálatot lezártként
            $duty->is_weekly_closed = true;
            $duty->save();

            // Frissítjük a felhasználó összes szolgálati idejét
            $this->updateUserServiceTime($duty->user_id);
        }

        return redirect()->back()->with('success', 'A heti szolgálatok sikeresen lezárva!');
    }

    public function forceEndDuty(Request $request, $userId)
    {
        $user = Auth::user();
        
        // Ellenőrizzük a jogosultságot
        if (!$user->isAdmin && !$user->is_officer) {
            return response()->json(['error' => 'Nincs jogosultságod ehhez a művelethez!'], 403);
        }

        // Validáljuk a bemenetet
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $targetUser = User::findOrFail($userId);
        
        // Aktív szolgálat keresése
        $activeDuty = DutyTime::where('user_id', $targetUser->id)
            ->where('is_weekly_closed', false)
            ->whereNull('ended_at')
            ->first();

        if (!$activeDuty) {
            return response()->json(['error' => 'A felhasználó nincs szolgálatban!'], 404);
        }

        // Szolgálat lezárása
        $activeDuty->ended_at = now();
        $activeDuty->total_duration = Carbon::parse($activeDuty->started_at)->diffInSeconds(now()) - $activeDuty->total_pause_duration;
        $activeDuty->force_ended_by = $user->id;
        $activeDuty->force_end_reason = $request->reason;
        $activeDuty->save();

        // Felhasználó státuszának frissítése
        $targetUser->is_on_duty = false;
        $targetUser->save();

        // Frissítjük a felhasználó összes szolgálati idejét
        $this->updateUserServiceTime($targetUser->id);

        return response()->json([
            'success' => true,
            'message' => $targetUser->charactername . ' szolgálata kényszerítve lett lezárva.'
        ]);
    }
}
