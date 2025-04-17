<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Felhasználók online státuszának frissítése minden percben
        $schedule->command('users:update-online-status')->everyMinute();
        
        // Előléptetési napok frissítése minden nap éjfélkor
        $schedule->command('users:update-promotion-days')->dailyAt('00:00');
        
        // Minden nap éjfélkor törli a 30 napnál régebbi lezárt heteket
        $schedule->command('app:cleanup-closed-weeks')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
