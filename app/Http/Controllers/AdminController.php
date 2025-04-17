<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\DutyTime;
use App\Models\Inactivity;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\BadgeNumberLog;

class AdminController extends Controller
{

    public function showTimeSpent()
    {
        // Csak superadmin érheti el
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Nincs jogosultságod ehhez az oldalhoz.');
        }

        // Lekérdezzük az összes felhasználót és az aktivitási idejüket
        $users = User::select('id', 'charactername', 'username', 'time_spent')->get();

        // Visszaküldjük az adatokat a view-nak
        return view('admin.time-spent', compact('users'));
    }

    public function getWeeklyStatsQuery()
    {
        return DB::table('users')
            ->leftJoin('reports', 'users.id', '=', 'reports.user_id')
            ->select(
                'users.id',
                'users.charactername',
                DB::raw('COALESCE(count(reports.user_id), 0) as reportCount'),
                DB::raw('COALESCE((SELECT MAX(reports.created_at) FROM reports WHERE reports.user_id = users.id), "-") as lastReportDate'),
                DB::raw('COALESCE((SELECT SUM(duty_times.minutes) FROM duty_times WHERE duty_times.user_id = users.id), 0) as dutyMinuteSum'),
                DB::raw('COALESCE((SELECT MAX(duty_times.end) FROM duty_times WHERE duty_times.user_id = users.id), "-") as lastDutyDate')
            )
            ->groupBy('users.id', 'users.charactername')
            ->orderBy('reportCount', 'DESC')
            ->get();
    }

    public function getWeeklyStatsTable()
    {
        $userStats = $this->getWeeklyStatsQuery();

        return view('admin.partials.view_weekly_stats', [
            'userStats' => $userStats,
        ]);
    }

    public function getClosedWeekStatsQuery()
    {
        return DB::table('users_closed')
            ->leftJoin('reports_closed', 'users_closed.id', '=', 'reports_closed.user_id')
            ->select(
                'users_closed.id',
                'users_closed.charactername',
                DB::raw('COALESCE(count(reports_closed.user_id), 0) as reportCount'),
                DB::raw('COALESCE((SELECT MAX(reports_closed.created_at) FROM reports_closed WHERE reports_closed.user_id = users_closed.id), "-") as lastReportDate'),
                DB::raw('COALESCE((SELECT SUM(duty_times_closed.minutes) FROM duty_times_closed WHERE duty_times_closed.user_id = users_closed.id), 0) as dutyMinuteSum'),
                DB::raw('COALESCE((SELECT MAX(duty_times_closed.end) FROM duty_times_closed WHERE duty_times_closed.user_id = users_closed.id), "-") as lastDutyDate')
            )
            ->groupBy('users_closed.id', 'users_closed.charactername')
            ->orderBy('reportCount', 'DESC')
            ->get();
    }

    public function getClosedWeekStatsTable()
    {
        $closedUserStats = $this->getClosedWeekStatsQuery();

        return view('admin.partials.view_closed_week_stats', [
            'closedUserStats' => $closedUserStats,
        ]);
    }

    public function getInactivitiesQuery()
    {
        return DB::table('inactivities')
            ->join('users', 'users.id', '=', 'inactivities.user_id')
            ->select(
                'users.charactername',
                'inactivities.begin',
                'inactivities.end',
                'inactivities.reason',
                'inactivities.id',
                'inactivities.status',
            )
            ->orderBy('inactivities.created_at', 'desc')
            ->get();
    }

    public function getInactivitiesTable()
    {
        $inactivities = $this->getInactivitiesQuery();

        $waitingForAnswerInInactivites = false;
        foreach ($inactivities as $inactivity) {
            if ($inactivity->status == 0) {
                $waitingForAnswerInInactivites = true;
                break;
            }
        }

        return view('admin.partials.view_inactivities', [
            'inactivities' => $inactivities,
            'waitingForAnswerInInactivites' => $waitingForAnswerInInactivites,
        ]);
    }

    public function getRegistratedUsersQuery()
    {
        return DB::table('users')
            ->select('users.id', 'users.charactername', 'users.username', 'users.created_at', 'users.isAdmin', 'users.canGiveAdmin')
            ->orderBy('users.charactername', 'ASC')
            ->get();
    }

    public function getRegistratedUsersTable()
    {
        $users = $this->getRegistratedUsersQuery();

        return view('admin.partials.view_registrated_users', [
            'users' => $users,
        ]);
    }

    public function getAdminLogsQuery()
    {
        return DB::table('admin_logs')
            ->join('users', 'users.id', '=', 'admin_logs.user_id')
            ->select(
                'users.charactername',
                'admin_logs.didWhat',
                'admin_logs.created_at',
            )
            ->orderBy('admin_logs.created_at', 'desc')
            ->get();
    }

    public function getAdminLogsTable()
    {
        $admin_logs = $this->getAdminLogsQuery();

        return view('admin.partials.view_admin_logs', [
            'admin_logs' => $admin_logs,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userStats = $this->getWeeklyStatsQuery();
        $closedUserStats = $this->getClosedWeekStatsQuery();
        $inactivities = $this->getInactivitiesQuery();
        $users = $this->getRegistratedUsersQuery();
        $admin_logs = $this->getAdminLogsQuery();

        $waitingForAnswerInInactivites = false;
        foreach ($inactivities as $inactivity) {
            if ($inactivity->status == 0) {
                $waitingForAnswerInInactivites = true;
                break;
            }
        }

        return view('admin.view_admin', [
            'users' => $users,
            'userStats' => $userStats,
            'closedUserStats' => $closedUserStats,
            'admin_logs' => $admin_logs,
            'inactivities' => $inactivities,
            'waitingForAnswerInInactivites' => $waitingForAnswerInInactivites,
        ]);
    }

    public function closeWeek()
    {
        $lockCloseWeek = DB::table('locks')
            ->where('name', 'close_week')
            ->where('isLocked', 0)
            ->update(['isLocked' => 1]);

        if ($lockCloseWeek == 0) {
            return Redirect::route('admin.index')->with('close-failed', 'A hét lezárása sikertelen. Művelet már folyamatban van.');
        }

        $currentWeekReports = DB::table('reports')
            ->select('reports.id')
            ->get();
        $currentWeekDuties = DB::table('duty_times')
            ->select('duty_times.id')
            ->get();

        if ($currentWeekReports->count() == 0 && $currentWeekDuties->count() == 0) {
            DB::table('locks')
                ->where('name', 'close_week')
                ->update(['isLocked' => 0]);
            return Redirect::route('admin.index')->with('close-failed', 'A hét lezárása sikertelen. Üres a jelenlegi hét.');
        }

        try {
            DB::delete('DELETE FROM reports_closed');
            DB::delete('DELETE FROM duty_times_closed');
            DB::delete('DELETE FROM users_closed');

            DB::insert('INSERT INTO users_closed SELECT id, charactername FROM users');
            DB::insert('INSERT INTO reports_closed SELECT * FROM reports');
            DB::insert('INSERT INTO duty_times_closed SELECT * FROM duty_times');

            DB::delete('DELETE FROM reports');
            DB::delete('DELETE FROM duty_times');

            DB::table('admin_logs')->insert(
                    ['user_id' => Auth::user()->id, 'didWhat' => 'Lezárta a hetet']
                );
        } catch (\Exception $e) {
            DB::rollBack();
            
            DB::table('locks')
                ->where('name', 'close_week')
                ->update(['isLocked' => 0]);

            return Redirect::route('admin.index')->with('close-failed', 'A hét lezárása sikertelen.');
        }

        DB::table('locks')
        ->where('name', 'close_week')
        ->update(['isLocked' => 0]);
        
        return Redirect::route('admin.index')->with('close-success', 'A hét sikeresen lezárva.');
    }


    public function userRegistrationPage()
    {
        return view('admin.register_user');
    }

    public function registerUser()
    {
        if (!auth()->user()->isAdmin) {
            abort(403, 'Nincs jogosultságod ehhez az oldalhoz.');
        }

        $ranks = DB::table('ranks')->select('id', 'name', 'color')->orderBy('name')->get();
        $subdivisions = DB::table('subdivisions')->select('id', 'name', 'color')->orderBy('name')->get();
        
        // Lekérjük az utolsó kiadott jelvényszámot
        $lastBadge = BadgeNumberLog::getLastBadgeNumber();
        
        return view('admin.register_user', [
            'ranks' => $ranks,
            'subdivisions' => $subdivisions,
            'lastBadge' => $lastBadge
        ]);
    }

    public function storeUser(Request $request)
    {
        if (!auth()->user()->isAdmin) {
            abort(403, 'Nincs jogosultságod ehhez az oldalhoz.');
        }

        $messages = [
            'charactername.unique' => 'Ez az IC név már foglalt.',
            'badge_number.unique' => 'Ez a jelvényszám már foglalt.',
        ];

        $request->validate([
            'charactername' => 'required|string|max:255|unique:users,charactername',
            'character_id' => 'required|string|max:255',
            'played_minutes' => 'required|integer|min:0',
            'phone_number' => 'required|string|max:255',
            'badge_number' => 'required|string|max:255|unique:users,badge_number',
            'recommended_by' => 'required|string|max:255',
            'rank_id' => 'nullable|exists:ranks,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
        ], $messages);

        // Generálunk egy random felhasználónevet (8 karakter)
        $username = Str::random(8);
        while (User::where('username', $username)->exists()) {
            $username = Str::random(8);
        }

        // Generálunk egy random jelszót (12 karakter)
        $password = Str::random(12);

        // A teljes karakter URL összeállítása
        $characterUrl = 'https://ucp.see-game.com/v4/character/' . $request->character_id;

        // Létrehozzuk a felhasználót
        $user = User::create([
            'charactername' => $request->charactername,
            'username' => $username,
            'password' => Hash::make($password),
            'character_id' => $characterUrl,
            'played_minutes' => $request->played_minutes,
            'phone_number' => $request->phone_number,
            'badge_number' => $request->badge_number,
            'recommended_by' => $request->recommended_by,
            'rank_id' => $request->rank_id,
            'subdivision_id' => $request->subdivision_id,
            'isAdmin' => false,
            'canGiveAdmin' => false,
            'is_superadmin' => false,
        ]);

        // Jelvényszám log mentése
        BadgeNumberLog::create([
            'badge_number' => $request->badge_number,
            'username' => auth()->user()->username,
            'assigned_to' => $request->charactername
        ]);

        // Admin log létrehozása
        DB::table('admin_logs')->insert([
            'user_id' => Auth::user()->id,
            'didWhat' => 'Regisztrált egy új felhasználót ' . $request->charactername . ' IC néven (ID: ' . $user->id . ')'
        ]);

        // Betöltjük a kapcsolódó adatokat
        $user->load(['rank', 'subdivision']);

        return view('admin.user_created', [
            'user' => $user,
            'password' => $password
        ]);
    }
    
    public function viewUserReports (string $id)
    {
        $reports = DB::table('reports')
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->select('reports.id', 'reports.price', 'reports.diagnosis', 'reports.withWho', 'reports.img', 'reports.created_at', 'users.charactername')
            ->where('user_id', '=', $id)
            ->get();

        if ($reports->isEmpty()) {
            return Redirect::route('admin.index');
        } else {
            return view('admin.view_user_reports', [
                'reports' => $reports,
                'charactername' => $reports[0]->charactername,
            ]);
        }
    }

    public function viewUserDuty (string $id)
    {
        $dutyTimes = DB::table('duty_times')
            ->join('users', 'users.id', '=', 'duty_times.user_id')
            ->select('duty_times.id', 'duty_times.begin', 'duty_times.end', 'duty_times.minutes', 'users.charactername')
            ->where('user_id', '=', $id)
            ->get();

        if ($dutyTimes->isEmpty()) {
            return Redirect::route('admin.index');
        } else {
            return view('admin.view_user_duty', [
                'dutyTimes' => $dutyTimes,
                'charactername' => $dutyTimes[0]->charactername,
            ]);
        }
    }

    public function viewClosedUserReports (string $id)
    {
        $reports = DB::table('reports_closed')
            ->join('users_closed', 'users_closed.id', '=', 'reports_closed.user_id')
            ->select('reports_closed.id', 'reports_closed.price', 'reports_closed.diagnosis', 'reports_closed.withWho', 'reports_closed.img', 'reports_closed.created_at', 'users_closed.charactername')
            ->where('user_id', '=', $id)
            ->get();

        return view('admin.view_user_reports', [
            'reports' => $reports,
            'charactername' => $reports[0]->charactername,
        ]);
    }

    public function viewClosedUserDuty (string $id)
    {
        $dutyTimes = DB::table('duty_times_closed')
            ->join('users_closed', 'users_closed.id', '=', 'duty_times_closed.user_id')
            ->select('duty_times_closed.id', 'duty_times_closed.begin', 'duty_times_closed.end', 'duty_times_closed.minutes', 'users_closed.charactername')
            ->where('user_id', '=', $id)
            ->get();

        return view('admin.view_user_duty', [
            'dutyTimes' => $dutyTimes,
            'charactername' => $dutyTimes[0]->charactername,
        ]);
    }

    public function editUser(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.update_user', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $usernameCheck = ($request->input('username') !== $user->username);

        if (Auth::user()->canGiveAdmin == 1 && Auth::user()->username != $user->username) {
            if ($request->has('admin')) {
                if ($user->isAdmin == 0) {
                    DB::table('admin_logs')->insert(
                        ['user_id' => Auth::user()->id, 'didWhat' => 'Frissítette a(z) ' . $user->id . ' ID-val rendelkező felhasználó admin rangját (0 -> 1)']
                    );
                }
                $user->isAdmin = 1;
            } else {
                if ($user->isAdmin == 1) {
                    DB::table('admin_logs')->insert(
                        ['user_id' => Auth::user()->id, 'didWhat' => 'Frissítette a(z) ' . $user->id . ' ID-val rendelkező felhasználó admin rangját (1 -> 0)']
                    );
                }
                $user->isAdmin = 0;
            }
        }

        // Check if username was changed, if not, then don't validate for unique
        if ($usernameCheck) {
            $validatedData = $request->validate([
                'username' => ['string', 'max:255', 'unique:users'],
            ], [
                'username.string' => 'A felhasználónév nem lehet üres.',
                'username.unique' => 'Ez a felhasználónév már foglalt.',
                'username.max' => 'Túl hosszú a felhasználónév.',
            ]);
        } else {
            $validatedData = $request->validate([
                'username' => ['string', 'max:255'],
            ], [
                'username.string' => 'A felhasználónév nem lehet üres.',
                'username.max' => 'Túl hosszú a felhasználónév.',
            ]);
        }

        $validatedData = $request->validate([
            'charactername' => ['string', 'max:255'],
        ], [
            'charactername.string' => 'Az IC név nem lehet üres.',
            'charactername.max' => 'Túl hosszú az IC név.',
        ]);

        if ($request->input('username') !== $user->username) {
            $oldusername = $user->username;
            $user->username = $request->input('username');

            DB::table('admin_logs')->insert(
                ['user_id' => Auth::user()->id, 'didWhat' => 'Frissítette a(z) ' . $user->id . ' ID-val rendelkező felhasználó felhasználónevét (' . $oldusername . ' -> ' . $request->input('username') . ')']
            );
        }

        if ($request->input('charactername') !== $user->charactername) {
            $oldcharactername = $user->charactername;
            $user->charactername = $request->input('charactername');

            DB::table('admin_logs')->insert(
                ['user_id' => Auth::user()->id, 'didWhat' => 'Frissítette a(z) ' . $user->id . ' ID-val rendelkező felhasználó IC nevét (' . $oldcharactername . ' -> ' . $request->input('charactername') . ')']
            );
        }

        try {
            $user->save();

            return Redirect::route('admin.index')->with('user-updated', 'A felhasználó frissítése sikeres.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.index')->with('user-not-updated', 'A felhasználó frissítése sikertelen.');
        }
    }

    public function deleteUser(string $id)
    {
        if (Auth::user()->id != $id) {
            try {
                $user = User::findOrFail($id);
                $user->delete();

                DB::table('admin_logs')->insert(
                    ['user_id' => Auth::user()->id, 'didWhat' => 'Kitörölte a(z) ' . $user->charactername . ' (ID: ' . $user->id . ') felhasználót']
                );

                return to_route('admin.index')->with('successful-user-deletion', 'A felhasználó törlése sikeres.');
            } catch (\Throwable $th) {
                return to_route('admin.index')->with('unsuccessful-user-deletion', 'A felhasználó törlése sikertelen.');
            }
        }
    }

    public function deleteReport(string $id)
    {
        $report = Report::findOrFail($id);
        $user = $report->user_id;
        try {
            $charactername = DB::table('users')
                ->join('reports', 'reports.user_id', '=', 'users.id')
                ->select('users.charactername')
                ->where('user_id', '=', $user)
                ->get();

            $report->delete();

            DB::table('admin_logs')->insert(
                ['user_id' => Auth::user()->id, 'didWhat' => 'Kitörölte a(z) ' . $charactername[0]->charactername . ' (Jelentés ID: ' . $id . ') felhasználó jelentését']
            );

            return Redirect::route('admin.viewUserReports', $user)->with('successful-user-report-deletion', 'A felhasználó jelentésének törlése sikeres.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.viewUserReports', $user)->with('unsuccessful-user-report-deletion', 'A felhasználó jelentésének törlése sikertelen.');
        }
    }

    public function deleteDutyTime(string $id)
    {
        $duty = DutyTime::findOrFail($id);
        $user = $duty->user_id;
        try {
            $charactername = DB::table('users')
                ->join('duty_times', 'duty_times.user_id', '=', 'users.id')
                ->select('users.charactername')
                ->where('user_id', '=', $user)
                ->get();

            $duty->delete();

            DB::table('admin_logs')->insert(
                ['user_id' => Auth::user()->id, 'didWhat' => 'Kitörölte a(z) ' . $charactername[0]->charactername  . ' (Szolgálat ID: ' . $id . ') felhasználó szolgálatát']
            );

            return Redirect::route('admin.viewUserDuty', $user)->with('successful-user-duty-deletion', 'A felhasználó szolgálatának törlése sikeres.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.viewUserDuty', $user)->with('unsuccessful-user-duty-deletion', 'A felhasználó szolgálatának törlése sikertelen.');
        }
    }

    public function destroyInactivity(string $id)
    {
        $inactivity = Inactivity::findOrFail($id);
        $user = $inactivity->user_id;
        try {
            $charactername = DB::table('users')
                ->join('inactivities', 'inactivities.user_id', '=', 'users.id')
                ->select('users.charactername')
                ->where('user_id', '=', $user)
                ->get();

            $inactivity->delete();

            DB::table('admin_logs')->insert(
                ['user_id' => Auth::user()->id, 'didWhat' => 'Kitörölte a(z) ' . $charactername[0]->charactername  . ' (Inaktivitás ID: ' . $id . ') felhasználó inaktivitási kérelmét']
            );
            
            return Redirect::route('admin.index')->with('destroyinactivity-success', 'Az inaktivitási kérelem sikeresen törölve.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.index')->with('destroyinactivity-failed', 'Az inaktivitási kérelem törlése meghiúsult.');
        }
    }

    public function acceptInactivity($id)
    {
        try {
            $inactivity = Inactivity::findOrFail($id);

            // 0 --> Válaszra vár
            // 1 --> Elfogadva
            // 2 --> Elutasítva

            if ($inactivity->status == 0) {
                DB::table('inactivities')
                    ->where('id', $id)
                    ->update(['status' => 1]);

                DB::table('admin_logs')->insert([
                    'user_id' => Auth::user()->id,
                    'didWhat' => 'Frissítette a(z) ' . $id . ' ID-val rendelkező inaktivitási kérelmet (Válaszra vár -> Elfogadva)'
                ]);
            }
            if ($inactivity->status == 2) {
                DB::table('inactivities')
                    ->where('id', $id)
                    ->update(['status' => 1]);

                DB::table('admin_logs')->insert([
                    'user_id' => Auth::user()->id,
                    'didWhat' => 'Frissítette a(z) ' . $id . ' ID-val rendelkező inaktivitási kérelmet (Elutasítva -> Elfogadva)'
                ]);
            }
            
            return Redirect::route('admin.index')->with('updateinactivity-success', 'Az inaktivitási kérelem sikeresen elfogadva.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.index')->with('updateinactivity-failed', 'Az inaktivitási kérelem elfogadása meghiúsult.');
        }
    }

    public function denyInactivity($id)
    {
        try {
            $inactivity = Inactivity::findOrFail($id);

            // 0 --> Válaszra vár
            // 1 --> Elfogadva
            // 2 --> Elutasítva

            if ($inactivity->status == 0) {
                DB::table('inactivities')
                    ->where('id', $id)
                    ->update(['status' => 2]);

                DB::table('admin_logs')->insert([
                    'user_id' => Auth::user()->id,
                    'didWhat' => 'Frissítette a(z) ' . $id . ' ID-val rendelkező inaktivitási kérelmet (Válaszra vár --> Elutasítva)'
                ]);
            }
            if ($inactivity->status == 1) {
                DB::table('inactivities')
                    ->where('id', $id)
                    ->update(['status' => 2]);

                DB::table('admin_logs')->insert([
                    'user_id' => Auth::user()->id,
                    'didWhat' => 'Frissítette a(z) ' . $id . ' ID-val rendelkező inaktivitási kérelmet (Elfogadva --> Elutasítva)'
                ]);
            }
            
            return Redirect::route('admin.index')->with('updateinactivity-success', 'Az inaktivitási kérelem sikeresen elutasítva.');
        } catch (\Throwable $th) {
            return Redirect::route('admin.index')->with('updateinactivity-failed', 'Az inaktivitási kérelem elutasítása meghiúsult.');
        }
    }
}