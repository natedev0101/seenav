<?php

namespace App\Http\Controllers;

use App\Models\DutyTime;
use App\Models\Report;
use App\Models\ReportPartner;
use App\Models\SalarySnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeeklyClosingController extends Controller
{
    public function index()
    {
        $closedWeeks = DB::table('closed_weeks')
            ->select([
                'closed_weeks.*',
                DB::raw('(SELECT COUNT(*) FROM reports_closed WHERE closed_week_id = closed_weeks.id) as reports_count'),
                DB::raw('(SELECT COUNT(*) FROM duty_times_closed WHERE closed_week_id = closed_weeks.id) as duty_times_count')
            ])
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        $defaultStartDate = now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $defaultEndDate = now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');

        return view('admin.weekly-closing.index', [
            'closedWeeks' => $closedWeeks,
            'defaultStartDate' => $defaultStartDate,
            'defaultEndDate' => $defaultEndDate
        ]);
    }

    public function close(Request $request)
    {
        try {
            // Debug információk
            Log::channel('daily')->info('Beérkező adatok:', [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'all_data' => $request->all(),
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'user_id' => auth()->id()
            ]);

            // Validáció
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ], [
                'start_date.required' => 'A kezdő dátum megadása kötelező.',
                'start_date.date' => 'A kezdő dátum nem megfelelő formátumú.',
                'end_date.required' => 'A záró dátum megadása kötelező.',
                'end_date.date' => 'A záró dátum nem megfelelő formátumú.',
                'end_date.after_or_equal' => 'A záró dátum nem lehet korábbi, mint a kezdő dátum.'
            ]);

            // Ellenőrizzük, hogy vasárnaptól vasárnapig tart-e az időszak
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            if ($startDate->dayOfWeek !== Carbon::SUNDAY) {
                throw new \Exception('A kezdő dátumnak vasárnapnak kell lennie!');
            }

            if ($endDate->dayOfWeek !== Carbon::SUNDAY) {
                throw new \Exception('A záró dátumnak vasárnapnak kell lennie!');
            }

            DB::beginTransaction();

            try {
                Log::channel('daily')->info('Dátumok feldolgozva:', [
                    'start_date' => $startDate->format('Y-m-d H:i:s'),
                    'end_date' => $endDate->format('Y-m-d H:i:s')
                ]);

                // Először létrehozzuk a closed_week bejegyzést
                $closedWeek = DB::table('closed_weeks')->insertGetId([
                    'closed_by' => auth()->id(),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'week_number' => $startDate->week(),
                    'year' => $startDate->year,
                    'metadata' => json_encode([
                        'closed_at' => now(),
                        'closed_by_name' => auth()->user()->name
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Még aktív szolgálatok lezárása
                $activeDuties = DB::table('duty_times')
                    ->whereNull('ended_at')
                    ->get();

                foreach ($activeDuties as $duty) {
                    // Szolgálat lezárása
                    DB::table('duty_times')
                        ->where('id', $duty->id)
                        ->update([
                            'ended_at' => now()->format('Y-m-d H:i:s'),
                            'end_reason' => 'Hét zárás miatt leállítva'
                        ]);

                    // Felhasználó szolgálati státuszának frissítése
                    DB::table('users')
                        ->where('id', $duty->user_id)
                        ->update([
                            'is_on_duty' => false
                        ]);

                    // Rendszerüzenet küldése
                    DB::table('system_messages')->insert([
                        'user_id' => $duty->user_id,
                        'message' => 'A szolgálatod automatikusan leállításra került a hét zárása miatt.',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Szolgálat automatikusan leállítva hét zárás miatt', [
                        'duty_id' => $duty->id,
                        'user_id' => $duty->user_id
                    ]);
                }

                // Jelentések lezárása
                $reports = Report::where('report_date', '>=', $startDate)
                    ->where('report_date', '<=', $endDate)
                    ->get();

                foreach ($reports as $report) {
                    // Jelentés mentése a reports_closed táblába
                    $reportDate = Carbon::parse($report->report_date);
                    $closedReportId = DB::table('reports_closed')->insertGetId([
                        'closed_week_id' => $closedWeek,
                        'original_report_id' => $report->id,
                        'user_id' => $report->user_id,
                        'suspect_name' => $report->suspect_name,
                        'type' => $report->type,
                        'fine_amount' => $report->fine_amount,
                        'image_url' => $report->image_url,
                        'description' => $report->description,
                        'status' => $report->status,
                        'rejection_reason' => $report->rejection_reason,
                        'handled_by' => $report->handled_by,
                        'report_date' => $report->report_date,
                        'week_number' => $reportDate->week(),
                        'year' => $reportDate->year,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Jelentés partnerek mentése a report_partners_closed táblába
                    $partners = ReportPartner::where('report_id', $report->id)->get();
                    foreach ($partners as $partner) {
                        DB::table('report_partners_closed')->insert([
                            'report_id' => $closedReportId,
                            'partner_id' => $partner->partner_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    $report->closing_metadata = [
                        'closed_at' => now(),
                        'closed_by' => auth()->id(),
                        'week_start' => $startDate->format('Y-m-d'),
                        'week_end' => $endDate->format('Y-m-d'),
                        'closed_week_id' => $closedWeek
                    ];
                    $report->deleted_by = auth()->id();
                    $report->save();
                    $report->delete();

                    // Jelentés partnerek lezárása
                    ReportPartner::where('report_id', $report->id)->update([
                        'closing_metadata' => [
                            'closed_at' => now(),
                            'closed_by' => auth()->id(),
                            'report_id' => $report->id
                        ],
                        'deleted_by' => auth()->id()
                    ]);
                    
                    ReportPartner::where('report_id', $report->id)->delete();
                }

                Log::channel('daily')->info('Jelentések lezárva', [
                    'count' => $reports->count()
                ]);

                // Szolgálatok lezárása
                $dutyTimes = DutyTime::where(function($query) use ($startDate, $endDate) {
                    $query->whereBetween('started_at', [$startDate, $endDate])
                        ->orWhereBetween('ended_at', [$startDate, $endDate]);
                })->get();

                foreach ($dutyTimes as $dutyTime) {
                    // Szolgálat mentése a duty_times_closed táblába
                    DB::table('duty_times_closed')->insert([
                        'closed_week_id' => $closedWeek,
                        'original_duty_time_id' => $dutyTime->id,
                        'user_id' => $dutyTime->user_id,
                        'started_at' => $dutyTime->started_at,
                        'ended_at' => $dutyTime->ended_at,
                        'total_duration' => $dutyTime->total_duration ?? 0,
                        'total_pause_duration' => $dutyTime->total_pause_duration ?? 0,
                        'week_number' => Carbon::parse($dutyTime->started_at)->week(),
                        'year' => Carbon::parse($dutyTime->started_at)->year,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $dutyTime->closing_metadata = [
                        'closed_at' => now(),
                        'closed_by' => auth()->id(),
                        'week_start' => $startDate->format('Y-m-d'),
                        'week_end' => $endDate->format('Y-m-d'),
                        'closed_week_id' => $closedWeek
                    ];
                    $dutyTime->deleted_by = auth()->id();
                    $dutyTime->save();
                    $dutyTime->delete();
                }

                Log::channel('daily')->info('Szolgálatok lezárva', [
                    'count' => $dutyTimes->count()
                ]);

                // Fizetések kiszámítása felhasználónként
                $users = DB::table('users')
                    ->leftJoin('ranks', 'users.rank_id', '=', 'ranks.id')
                    ->select('users.*', 'ranks.name as rank_name', 'ranks.salary as rank_salary')
                    ->get();

                $salaries = collect();
                foreach ($users as $user) {
                    // Szolgálati idők
                    $dutyTime = DB::table('duty_times_closed')
                        ->where('closed_week_id', $closedWeek)
                        ->where('user_id', $user->id)
                        ->sum('total_duration');

                    // Saját jelentések
                    $reports = DB::table('reports_closed')
                        ->where('closed_week_id', $closedWeek)
                        ->where('user_id', $user->id)
                        ->where('status', 'APPROVED')
                        ->get();

                    // Partner jelentések
                    $partnerReports = DB::table('report_partners_closed')
                        ->join('reports_closed', 'report_partners_closed.report_id', '=', 'reports_closed.id')
                        ->where('reports_closed.closed_week_id', $closedWeek)
                        ->where('report_partners_closed.partner_id', $user->id)
                        ->where('reports_closed.status', 'APPROVED')
                        ->get();

                    // Összesített jelentések
                    $allReports = $reports->concat($partnerReports);

                    // Bónuszok kiszámítása
                    $merkurBonus = $allReports->where('type', 'MERKUR')->count() * 5000;
                    $taxBonus = $allReports->where('type', 'ADO')->count() * 5000;
                    $knyfBonus = $allReports->where('type', 'KNYF')->count() * 5000;
                    $beoBonus = $allReports->where('type', 'BEO')->count() * 5000;
                    $medicBonus = $allReports->where('type', 'SZANITEC')->count() * 5000;

                    // Top 5 jelentésíró meghatározása
                    $topReporters = DB::table('reports_closed')
                        ->where('closed_week_id', $closedWeek)
                        ->where('status', 'APPROVED')
                        ->select('user_id', DB::raw('COUNT(*) as report_count'))
                        ->groupBy('user_id')
                        ->orderByDesc('report_count')
                        ->limit(5)
                        ->pluck('user_id')
                        ->toArray();

                    $topReportBonus = in_array($user->id, $topReporters) ? 
                        ($allReports->sum('fine_amount') * 0.25) : 0;

                    // Alapfizetés kiszámítása
                    $baseSalary = $user->rank_salary ? 
                        ($user->rank_salary * round($dutyTime/3600)) : 0;

                    // Teljes fizetés
                    $totalSalary = $baseSalary + $merkurBonus + $taxBonus + $knyfBonus + 
                          $beoBonus + $medicBonus + $topReportBonus;

                    $salaries->push((object)[
                        'user_id' => $user->id,
                        'charactername' => $user->charactername,
                        'rank_name' => $user->rank_name,
                        'duty_minutes' => $dutyTime,
                        'total_hours' => round($dutyTime/3600, 1),
                        'reports_count' => $allReports->count(),
                        'merkur_bonus' => $merkurBonus,
                        'tax_bonus' => $taxBonus,
                        'knyf_bonus' => $knyfBonus,
                        'beo_bonus' => $beoBonus,
                        'medic_bonus' => $medicBonus,
                        'top_report_bonus' => $topReportBonus,
                        'base_salary' => $baseSalary,
                        'total_salary' => $totalSalary
                    ]);
                }

                // Fizetések rendezése összeg szerint csökkenő sorrendbe
                $salaries = $salaries->sortByDesc('total_salary')->values();

                DB::commit();

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'A hét sikeresen le lett zárva!',
                        'data' => [
                            'reports_count' => $reports->count(),
                            'duty_times_count' => $dutyTimes->count(),
                            'users_with_salary' => $users->count()
                        ]
                    ]);
                }

                return redirect()->route('admin.weekly-closing.index')
                    ->with('success', 'A hét sikeresen le lett zárva!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::channel('daily')->error('Hiba a tranzakció során', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::channel('daily')->error('Hiba történt a heti zárás során', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Hiba történt: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.weekly-closing.index')
                ->with('error', 'Hiba történt a hét lezárása során: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $closedWeek = DB::table('closed_weeks')->where('id', $id)->first();
        
        if (!$closedWeek) {
            return redirect()->route('admin.weekly-closing.index')
                ->with('error', 'A megadott heti zárás nem található!');
        }

        // Debug információk
        Log::channel('daily')->info('Heti zárás megtekintése:', [
            'id' => $id,
            'closed_week' => $closedWeek
        ]);

        // Jelentések lekérése
        $reports = DB::table('reports_closed')
            ->where('closed_week_id', $id)
            ->join('users', 'reports_closed.user_id', '=', 'users.id')
            ->select([
                'reports_closed.*',
                'users.charactername'
            ])
            ->orderBy('report_date', 'desc')
            ->get();

        // Debug információk
        Log::info('Jelentések lekérdezése:', [
            'closed_week_id' => $id,
            'reports_count' => $reports->count(),
            'reports' => $reports->toArray(),
            'sql' => DB::table('reports_closed')
                ->where('closed_week_id', $id)
                ->join('users', 'reports_closed.user_id', '=', 'users.id')
                ->select([
                    'reports_closed.*',
                    'users.charactername'
                ])
                ->orderBy('report_date', 'desc')
                ->toSql(),
            'bindings' => [
                'closed_week_id' => $id
            ]
        ]);

        // Partner jelentések lekérése
        $reportPartners = DB::table('report_partners_closed')
            ->whereIn('report_id', $reports->pluck('id'))
            ->join('users', 'report_partners_closed.partner_id', '=', 'users.id')
            ->select([
                'report_partners_closed.report_id',
                'report_partners_closed.partner_id',
                'users.charactername'
            ])
            ->get()
            ->groupBy('report_id');

        // Debug információk
        Log::info('Partner jelentések lekérdezése:', [
            'report_ids' => $reports->pluck('id'),
            'partners_count' => $reportPartners->count(),
            'partners' => $reportPartners->toArray()
        ]);

        // Szolgálati idők lekérése
        $dutyTimes = DB::table('duty_times_closed')
            ->where('closed_week_id', $id)
            ->join('users', 'duty_times_closed.user_id', '=', 'users.id')
            ->select('duty_times_closed.*', 'users.charactername')
            ->orderBy('started_at', 'desc')
            ->get();

        // Debug információk
        Log::channel('daily')->info('Szolgálati idők:', [
            'count' => $dutyTimes->count(),
            'duty_times' => $dutyTimes->toArray()
        ]);

        // Fizetések kiszámítása felhasználónként
        $users = DB::table('users')
            ->leftJoin('ranks', 'users.rank_id', '=', 'ranks.id')
            ->select('users.*', 'ranks.name as rank_name', 'ranks.salary as rank_salary')
            ->get();

        $salaries = collect();
        foreach ($users as $user) {
            // Szolgálati idők
            $dutyTime = DB::table('duty_times_closed')
                ->where('closed_week_id', $id)
                ->where('user_id', $user->id)
                ->sum('total_duration');

            // Saját jelentések
            $reports = DB::table('reports_closed')
                ->where('closed_week_id', $id)
                ->where('user_id', $user->id)
                ->where('status', 'APPROVED')
                ->get();

            // Partner jelentések
            $partnerReports = DB::table('report_partners_closed')
                ->join('reports_closed', 'report_partners_closed.report_id', '=', 'reports_closed.id')
                ->where('reports_closed.closed_week_id', $id)
                ->where('report_partners_closed.partner_id', $user->id)
                ->where('reports_closed.status', 'APPROVED')
                ->get();

            // Összesített jelentések
            $allReports = $reports->concat($partnerReports);

            // Bónuszok kiszámítása
            $merkurBonus = $allReports->where('type', 'MERKUR')->count() * 5000;
            $taxBonus = $allReports->where('type', 'ADO')->count() * 5000;
            $knyfBonus = $allReports->where('type', 'KNYF')->count() * 5000;
            $beoBonus = $allReports->where('type', 'BEO')->count() * 5000;
            $medicBonus = $allReports->where('type', 'SZANITEC')->count() * 5000;

            // Top 5 jelentésíró meghatározása
            $topReporters = DB::table('reports_closed')
                ->where('closed_week_id', $id)
                ->where('status', 'APPROVED')
                ->select('user_id', DB::raw('COUNT(*) as report_count'))
                ->groupBy('user_id')
                ->orderByDesc('report_count')
                ->limit(5)
                ->pluck('user_id')
                ->toArray();

            $topReportBonus = in_array($user->id, $topReporters) ? 
                ($allReports->sum('fine_amount') * 0.25) : 0;

            // Alapfizetés kiszámítása
            $baseSalary = $user->rank_salary ? 
                ($user->rank_salary * round($dutyTime/3600)) : 0;

            // Teljes fizetés
            $totalSalary = $baseSalary + $merkurBonus + $taxBonus + $knyfBonus + 
                          $beoBonus + $medicBonus + $topReportBonus;

            $salaries->push((object)[
                'user_id' => $user->id,
                'charactername' => $user->charactername,
                'rank_name' => $user->rank_name,
                'duty_minutes' => $dutyTime,
                'total_hours' => round($dutyTime/3600, 1),
                'reports_count' => $allReports->count(),
                'merkur_bonus' => $merkurBonus,
                'tax_bonus' => $taxBonus,
                'knyf_bonus' => $knyfBonus,
                'beo_bonus' => $beoBonus,
                'medic_bonus' => $medicBonus,
                'top_report_bonus' => $topReportBonus,
                'base_salary' => $baseSalary,
                'total_salary' => $totalSalary
            ]);
        }

        // Fizetések rendezése összeg szerint csökkenő sorrendbe
        $salaries = $salaries->sortByDesc('total_salary')->values();

        return view('admin.weekly-closing.view', [
            'closedWeek' => $closedWeek,
            'dutyTimes' => $dutyTimes,
            'reports' => $reports,
            'reportPartners' => $reportPartners,
            'salaries' => $salaries
        ]);
    }

    public function userReports($weekId, $userId)
    {
        $closedWeek = DB::table('closed_weeks')->where('id', $weekId)->first();
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$closedWeek || !$user) {
            return redirect()->route('admin.weekly-closing.index')
                ->with('error', 'A megadott heti zárás vagy felhasználó nem található!');
        }

        // Jelentések lekérése
        $reports = DB::table('reports_closed')
            ->where('closed_week_id', $weekId)
            ->where('user_id', $userId)
            ->join('users', 'reports_closed.user_id', '=', 'users.id')
            ->select([
                'reports_closed.*',
                'users.charactername'
            ])
            ->orderBy('report_date', 'desc')
            ->get();

        // Partner jelentések lekérése
        $reportPartners = DB::table('report_partners_closed')
            ->whereIn('report_id', $reports->pluck('id'))
            ->join('users', 'report_partners_closed.partner_id', '=', 'users.id')
            ->select([
                'report_partners_closed.report_id',
                'report_partners_closed.partner_id',
                'users.charactername'
            ])
            ->get()
            ->groupBy('report_id');

        return view('admin.weekly-closing.user-reports', [
            'closedWeek' => $closedWeek,
            'user' => $user,
            'reports' => $reports,
            'reportPartners' => $reportPartners
        ]);
    }
}
