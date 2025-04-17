<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Rank;
use App\Models\RankChange;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Show extends Component
{
    public $user;
    public $isMaxRank = false;
    public $isMinRank = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->checkRankLimits();
    }

    public function render()
    {
        return view('livewire.user.show');
    }

    public function checkRankLimits()
    {
        $highestRank = Rank::orderBy('id', 'desc')->first();
        $lowestRank = Rank::orderBy('id', 'asc')->first();

        $this->isMaxRank = $this->user->rank_id === $highestRank?->id;
        $this->isMinRank = $this->user->rank_id === $lowestRank?->id;
    }

    public function promoteUser()
    {
        try {
            DB::beginTransaction();
            
            // Ha nincs rangja, adjuk neki az első rangot
            if (!$this->user->rank_id) {
                $firstRank = Rank::orderBy('id', 'asc')->first();
                if ($firstRank) {
                    $this->updateUserRank(null, $firstRank->id);
                    DB::commit();
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'Sikeres előléptetés!'
                    ]);
                    return;
                }
                DB::rollBack();
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Nincs elérhető rang!'
                ]);
                return;
            }

            // Következő rang keresése
            $nextRank = Rank::where('id', '>', $this->user->rank_id)
                ->orderBy('id', 'asc')
                ->first();

            if (!$nextRank) {
                DB::rollBack();
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'A felhasználó már a legmagasabb rangon van!'
                ]);
                return;
            }

            $this->updateUserRank($this->user->rank_id, $nextRank->id);
            DB::commit();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Sikeres előléptetés!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt az előléptetés során: ' . $e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Hiba történt az előléptetés során!'
            ]);
        }

        $this->checkRankLimits();
    }

    public function demoteUser()
    {
        try {
            DB::beginTransaction();

            if (!$this->user->rank_id) {
                DB::rollBack();
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'A felhasználónak nincs rangja!'
                ]);
                return;
            }

            // Előző rang keresése
            $previousRank = Rank::where('id', '<', $this->user->rank_id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$previousRank) {
                DB::rollBack();
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'A felhasználó már a legalacsonyabb rangon van!'
                ]);
                return;
            }

            $this->updateUserRank($this->user->rank_id, $previousRank->id);
            DB::commit();
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Sikeres lefokozás!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba történt a lefokozás során: ' . $e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Hiba történt a lefokozás során!'
            ]);
        }

        $this->checkRankLimits();
    }

    private function updateUserRank($oldRankId, $newRankId)
    {
        // Rang frissítése
        $this->user->rank_id = $newRankId;
        $this->user->last_rank_change = now();
        $this->user->save();

        // Rang változás naplózása
        RankChange::create([
            'user_id' => $this->user->id,
            'old_rank_id' => $oldRankId,
            'new_rank_id' => $newRankId,
            'changed_by' => auth()->id()
        ]);
    }
}
