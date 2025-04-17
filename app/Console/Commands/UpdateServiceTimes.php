<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\DutyTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateServiceTimes extends Command
{
    protected $signature = 'service:update-times';
    protected $description = 'Frissíti az összes felhasználó szolgálati idejét a duty_times tábla alapján';

    public function handle()
    {
        $this->info('Szolgálati idők frissítése kezdődik...');

        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach ($users as $user) {
            // Összegezzük a befejezett szolgálatok idejét (másodpercekben)
            $totalDurationSeconds = DutyTime::where('user_id', $user->id)
                ->whereNotNull('ended_at')
                ->sum('total_duration');

            // Átváltjuk percekre és frissítjük a felhasználó adatait
            $totalDurationMinutes = floor($totalDurationSeconds / 60);
            
            $user->service_time = $totalDurationMinutes;
            $user->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Szolgálati idők sikeresen frissítve!');
    }
}
