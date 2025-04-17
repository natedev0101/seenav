<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdatePromotionDays extends Command
{
    protected $signature = 'users:update-promotion-days';
    protected $description = 'Naponta frissíti a felhasználók előléptetési napjait';

    public function handle()
    {
        $users = User::where('status', 'active')
            ->whereNotNull('rank_id')
            ->whereNotNull('last_rank_change')
            ->get();

        foreach ($users as $user) {
            // Kiszámoljuk az utolsó rangup óta eltelt napokat
            $daysSinceLastRankUp = Carbon::parse($user->last_rank_change)->diffInDays(now());
            
            // Az alap promotion_days a rangból jön
            $basePromotionDays = $user->rank->promotion_days;
            
            // Pontok alapján csökkentés (maximum 15 nap)
            $maxReduction = 15;
            $pointsReduction = min($user->plus_points * 3, $maxReduction);
            
            // A hátralévő napok számítása
            $remainingDays = max(0, $basePromotionDays - $pointsReduction - $daysSinceLastRankUp);
            
            // Frissítjük a custom_promotion_days értéket
            $user->custom_promotion_days = $remainingDays;
            $user->save();
        }

        $this->info('Az előléptetési napok sikeresen frissítve!');
    }
}
