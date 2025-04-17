<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class UpdateUserOnlineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-online-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Frissíti a felhasználók online státuszát az utolsó aktivitás alapján';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Felhasználók online státuszának frissítése...');

        // Az inaktivitási időkorlát (percben)
        $inactivityThreshold = config('session.lifetime', 120);

        // Lekérjük azokat a felhasználókat, akik online státuszban vannak, de már inaktívak
        $inactiveUsers = User::where('is_online', true)
            ->whereNotNull('last_active')
            ->where('last_active', '<', Carbon::now()->subMinutes($inactivityThreshold))
            ->get();

        // Frissítjük az online státuszt
        foreach ($inactiveUsers as $user) {
            $user->update(['is_online' => false]);
            $this->info("Felhasználó offline státuszba helyezve: {$user->charactername} (ID: {$user->id})");
        }

        $this->info("Összesen {$inactiveUsers->count()} felhasználó státusza frissítve.");
    }
}
