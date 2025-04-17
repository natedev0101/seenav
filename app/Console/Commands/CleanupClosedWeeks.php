<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClosedWeek;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CleanupClosedWeeks extends Command
{
    protected $signature = 'app:cleanup-closed-weeks';
    protected $description = 'Törli a 30 napnál régebbi lezárt heteket';

    public function handle()
    {
        /*
        return DB::transaction(function () {
            $date = Carbon::now()->subDays(30);
            
            // Megkeressük a régi lezárt heteket
            $oldWeeks = ClosedWeek::where('closed_at', '<', $date)->get();
            
            foreach ($oldWeeks as $week) {
                // Töröljük a kapcsolódó jelentéseket és partnereket
                DB::table('report_partners_closed')
                    ->whereIn('report_id', function($query) use ($week) {
                        $query->select('id')
                            ->from('reports_closed')
                            ->where('closed_week_id', $week->id);
                    })
                    ->delete();
                
                // Töröljük a jelentéseket
                DB::table('reports_closed')
                    ->where('closed_week_id', $week->id)
                    ->delete();
                
                // Töröljük a duty time-okat
                DB::table('duty_times_closed')
                    ->whereBetween('started_at', [$week->start_date, $week->end_date])
                    ->delete();
                
                // Töröljük a hetet
                $week->delete();
            }
            
            $this->info('Sikeresen törölve ' . $oldWeeks->count() . ' régi lezárt hét.');
        });
        */
        
        $this->info('A parancs jelenleg ki van kapcsolva. A régi adatok törlése nem aktív.');
    }
}
