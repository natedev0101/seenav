<?php

namespace App\Http\Controllers;

use App\Models\{Report, User, DutyTime};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user:id,charactername', 'partners:id,charactername', 'handler:id,charactername']);

        // Statisztikák lekérése
        $stats = [
            'total' => Report::count(),
            'approved' => Report::where('status', 'APPROVED')->count(),
            'pending' => Report::where('status', 'PENDING')->count(),
            'rejected' => Report::where('status', 'REJECTED')->count(),
            'total_fine' => Report::sum('fine_amount'),
            'top_report' => Report::orderBy('fine_amount', 'desc')->with(['user:id,charactername'])->first()
        ];

        // Jelentés dátuma szerinti szűrés
        if ($request->filled('report_date_from')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->report_date_from)->startOfDay();
            $query->whereDate('report_date', '>=', $date);
        }
        if ($request->filled('report_date_to')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->report_date_to)->endOfDay();
            $query->whereDate('report_date', '<=', $date);
        }

        // Leadás dátuma szerinti szűrés
        if ($request->filled('created_at_from')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->created_at_from)->startOfDay();
            $query->whereDate('created_at', '>=', $date);
        }
        if ($request->filled('created_at_to')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->created_at_to)->endOfDay();
            $query->whereDate('created_at', '<=', $date);
        }

        // Beadó szerinti szűrés
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('charactername', 'like', '%' . $request->user . '%');
            });
        }

        // Járőrtárs szerinti szűrés
        if ($request->filled('partner')) {
            $query->whereHas('partners', function ($q) use ($request) {
                $q->where('charactername', 'like', '%' . $request->partner . '%');
            });
        }

        // Elkövető szerinti szűrés
        if ($request->filled('suspect')) {
            $query->where('suspect_name', 'like', '%' . $request->suspect . '%');
        }

        // Típus szerinti szűrés
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Összeg szerinti szűrés
        if ($request->filled('amount_min')) {
            $query->where('fine_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('fine_amount', '<=', $request->amount_max);
        }

        // Státusz szerinti szűrés
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Kezelő szerinti szűrés
        if ($request->filled('handler')) {
            $query->whereHas('handler', function ($q) use ($request) {
                $q->where('charactername', 'like', '%' . $request->handler . '%');
            });
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('reports.index', compact('reports', 'stats'));
    }

    public function create()
    {
        $activeUsers = User::where('id', '!=', Auth::id())
            ->orderBy('charactername')
            ->get(['id', 'charactername', 'is_on_duty']);

        return view('reports.create', compact('activeUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'suspect_name' => 'required|string|max:255',
            'type' => 'required|in:ELŐÁLLÍTÁS,IGAZOLTATÁS',
            'fine_amount' => 'required|numeric|min:0|max:300000',
            'partner_ids' => 'nullable|array|max:2',
            'partner_ids.*' => 'exists:users,id',
            'image_url' => 'nullable|url', // TODO: később required|url lesz
            'report_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subDays(6)->startOfDay()->format('Y-m-d')
            ],
        ]);

        DB::transaction(function () use ($validated) {
            // Jelentés létrehozása
            $report = Report::create([
                'user_id' => Auth::id(),
                'report_date' => $validated['report_date'],
                'suspect_name' => $validated['suspect_name'],
                'type' => $validated['type'],
                'fine_amount' => $validated['fine_amount'],
                'image_url' => $validated['image_url'],
                'status' => 'PENDING'
            ]);

            // Járőrtársak hozzáadása
            if (!empty($validated['partner_ids'])) {
                $report->partners()->attach($validated['partner_ids']);
            }

            // Növeljük a jelentések számát és a függő jelentések számát
            Auth::user()->increment('report_count');
            Auth::user()->increment('pending_reports');

            // Járőrtársaknál is növeljük
            if (!empty($validated['partner_ids'])) {
                User::whereIn('id', $validated['partner_ids'])->increment('report_count');
                User::whereIn('id', $validated['partner_ids'])->increment('pending_reports');
            }
        });

        return redirect()->route('reports.index')
            ->with('success', 'Jelentés sikeresen létrehozva!');
    }

    public function approve(Report $report)
    {
        if (!Auth::user()->isAdmin) {
            return response()->json(['error' => 'Nincs jogosultságod a művelethez!'], 403);
        }

        DB::transaction(function () use ($report) {
            // Státusz frissítése
            $report->update([
                'status' => 'APPROVED',
                'handled_by' => Auth::id()
            ]);

            // Csökkentjük a függő jelentések számát és növeljük az elfogadottak számát
            $report->user->decrement('pending_reports');
            $report->user->increment('approved_reports');

            // Járőrtársaknál is frissítjük
            foreach ($report->partners as $partner) {
                $partner->decrement('pending_reports');
                $partner->increment('approved_reports');
            }
        });

        return response()->json(['success' => true]);
    }

    public function reject(Request $request, Report $report)
    {
        if (!Auth::user()->isAdmin) {
            return response()->json(['error' => 'Nincs jogosultságod a művelethez!'], 403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:50'
        ]);

        DB::transaction(function () use ($report, $validated) {
            // Státusz frissítése
            $report->update([
                'status' => 'REJECTED',
                'rejection_reason' => $validated['reason'],
                'handled_by' => Auth::id()
            ]);

            // Csökkentjük a függő jelentések számát és növeljük az elutasítottak számát
            $report->user->decrement('pending_reports');
            $report->user->increment('declined_reports');

            // Járőrtársaknál is frissítjük
            foreach ($report->partners as $partner) {
                $partner->decrement('pending_reports');
                $partner->increment('declined_reports');
            }
        });

        return response()->json(['success' => true]);
    }

    public function destroy(Report $report)
    {
        // Ellenőrizzük, hogy a felhasználó törölheti-e a jelentést
        if (!Auth::user()->isAdmin && Auth::id() !== $report->user_id) {
            return redirect()->back()->with('error', 'Nincs jogosultságod a jelentés törléséhez!');
        }

        // Csak függőben lévő jelentéseket lehet törölni
        if ($report->status !== 'PENDING') {
            return redirect()->back()->with('error', 'Csak függőben lévő jelentéseket lehet törölni!');
        }

        DB::transaction(function () use ($report) {
            // Csökkentjük a függő jelentések számát
            $report->user->decrement('report_count');
            $report->user->decrement('pending_reports');

            // Járőrtársaknál is csökkentjük
            foreach ($report->partners as $partner) {
                $partner->decrement('report_count');
                $partner->decrement('pending_reports');
            }

            // Jelentés törlése
            $report->delete();
        });

        return redirect()->back()->with('success', 'A jelentés sikeresen törölve lett!');
    }

    public function show(Report $report)
    {
        $report->load(['user:id,charactername', 'partners:id,charactername', 'handler:id,charactername']);
        return view('reports.show', compact('report'));
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query');
        
        $users = User::where('id', '!=', Auth::id())
            ->where('charactername', 'like', '%' . $query . '%')
            ->orderBy('is_on_duty', 'desc') // Szolgálatban lévők előre
            ->orderBy('charactername')
            ->get(['id', 'charactername', 'is_on_duty']);

        return response()->json($users);
    }

    public function statistics(User $user)
    {
        // Statisztikák lekérése
        $stats = [
            'accepted' => Report::where('user_id', $user->id)->where('status', 'APPROVED')->count(),
            'rejected' => Report::where('user_id', $user->id)->where('status', 'REJECTED')->count(),
            'pending' => Report::where('user_id', $user->id)->where('status', 'PENDING')->count(),
            'partner' => Report::whereHas('partners', function($query) use ($user) {
                $query->where('report_partners.partner_id', $user->id);
            })->count(),
        ];

        // Jelentések lekérése a report_date és created_at mezőkkel
        $reports = Report::with(['user', 'partners'])
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('partners', function($q) use ($user) {
                        $q->where('report_partners.partner_id', $user->id);
                    });
            })
            ->select('id', 'user_id', 'type', 'status', 'report_date', 'created_at')
            ->orderBy('report_date', 'desc')
            ->get();

        // Szolgálati idők lekérése
        $dutyTimes = DutyTime::where('user_id', $user->id)
            ->select('id', 'user_id', 'started_at', 'ended_at', 'created_at', 'total_duration')
            ->orderBy('started_at', 'desc')
            ->get();

        return view('reports.statistics', compact('stats', 'reports', 'dutyTimes', 'user'));
    }

    public function salaries()
    {
        // Lekérjük az összes felhasználót és a hozzájuk tartozó szolgálati időket és jelentéseket
        $users = User::with(['dutyTimes', 'reports', 'rank', 'partnerReports'])->get();

        // Megkeressük a top 5 legtöbb jelentéssel rendelkező felhasználót
        $topReporters = $users->sortByDesc(function($user) {
            $ownReports = $user->reports ? $user->reports->where('status', 'APPROVED')->count() : 0;
            $partnerReports = $user->partnerReports ? $user->partnerReports->where('status', 'APPROVED')->count() : 0;
            return $ownReports + $partnerReports;
        })->take(5)->pluck('id')->toArray();

        // Csoportosítjuk a felhasználókat
        $groupedUsers = [
            'Vezetőség' => $users->filter(fn($user) => $user->isAdmin || $user->is_superadmin),
            'Tisztikar' => $users->filter(fn($user) => !($user->isAdmin || $user->is_superadmin) && $user->isOfficer),
            'Állomány' => $users->filter(fn($user) => !($user->isAdmin || $user->is_superadmin) && !$user->isOfficer)
        ];

        // Átalakítjuk a fizetési adatokat
        $salaryGroups = collect($groupedUsers)->map(function($users, $groupName) use ($topReporters) {
            return [
                'name' => $groupName,
                'salaries' => $users->map(function($user) use ($topReporters) {
                    $minutes = $user->dutyTimes ? $user->dutyTimes->sum('total_duration') : 0;
                    $reports = $user->reports ? $user->reports->where('status', 'APPROVED') : collect();
                    $partnerReports = $user->partnerReports ? $user->partnerReports->where('status', 'APPROVED') : collect();
                    
                    // Összesített jelentések
                    $allReports = $reports->concat($partnerReports);

                    return (object)[
                        'user' => $user,
                        'rankName' => $user->rank ? $user->rank->name : null,
                        'rankColor' => $user->rank ? $user->rank->color : null,
                        'minutes' => $minutes,
                        'reports_count' => $allReports->count(),
                        'merkur_count' => $allReports->where('type', 'MERKUR')->count(),
                        'ado_count' => $allReports->where('type', 'ADO')->count(),
                        'knyf_count' => $allReports->where('type', 'KNYF')->count(),
                        'beo_count' => $allReports->where('type', 'BEO')->count(),
                        'sanitec_count' => $allReports->where('type', 'SZANITEC')->count(),
                        'top_report_count' => in_array($user->id, $topReporters) ? ($allReports->sum('fine_amount') * 0.25) : 0,
                        'base_salary' => $user->rank ? $user->rank->salary : 0,
                        'total_salary' => $user->rank ? 
                        ($user->rank->salary * round($minutes/3600)) + // Alapfizetés: rang × órák (kerekítve)
                        (in_array($user->id, $topReporters) ? ($allReports->sum('fine_amount') * 0.25) : 0) // Top reporter bónusz
                        : 0
                    ];
                })->sortBy(function($salary) {
                    return $salary->user->charactername;
                })
            ];
        });

        return view('reports.salaries', ['salaryGroups' => $salaryGroups]);
    }

    public function paySalary($salaryId)
    {
        $user = auth()->user();
        $salaries = $this->salaries()->first(function($salary) use ($salaryId) {
            return $salary->id == $salaryId;
        });

        if (!$salaries) {
            return response()->json(['success' => false, 'message' => 'A fizetés nem található!']);
        }

        if ($salaries->paid) {
            return response()->json(['success' => false, 'message' => 'Ez a fizetés már ki lett fizetve!']);
        }

        // Fizetés végrehajtása
        $salaries->paid = true;
        $salaries->paid_by = $user->charactername;

        return response()->json(['success' => true]);
    }
}
