<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DutyTime;
use App\Models\DutyTimeClosed;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DutyTimeManager extends Component
{
    public $activeDuty = null;
    public $proofLink;
    public $elapsedTime = '00:00:00';
    public $error = '';

    protected $rules = [
        'proofLink' => 'required|url'
    ];

    protected $messages = [
        'proofLink.required' => 'A link megadása kötelező',
        'proofLink.url' => 'Érvényes URL címet kell megadni'
    ];

    public function mount()
    {
        $this->loadActiveDuty();
    }

    public function loadActiveDuty()
    {
        $this->activeDuty = DutyTime::where('user_id', auth()->id())->first();
        if ($this->activeDuty) {
            $this->updateElapsedTime();
        }
    }

    public function updateTimer()
    {
        $this->updateElapsedTime();
    }

    public function updateElapsedTime()
    {
        if (!$this->activeDuty) {
            return;
        }

        $start = Carbon::parse($this->activeDuty->started_at);
        $now = Carbon::now();
        $diff = $now->diffInSeconds($start);
        
        // Levonjuk a szüneteket
        $diff -= $this->activeDuty->total_pause_duration;
        
        // Ha épp szünetel, akkor azt az időt is levonjuk
        if ($this->activeDuty->is_paused) {
            $pauseStart = Carbon::parse($this->activeDuty->paused_at);
            $diff -= $now->diffInSeconds($pauseStart);
        }

        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $seconds = $diff % 60;

        $this->elapsedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function startDuty()
    {
        DutyTime::create([
            'user_id' => auth()->id(),
            'started_at' => now(),
        ]);

        $this->loadActiveDuty();
    }

    public function pauseDuty()
    {
        if (!$this->activeDuty || $this->activeDuty->is_paused) {
            return;
        }

        $this->activeDuty->update([
            'is_paused' => true,
            'paused_at' => now(),
        ]);

        $this->loadActiveDuty();
        $this->dispatch('duty-status-updated');
    }

    public function resumeDuty()
    {
        if (!$this->activeDuty || !$this->activeDuty->is_paused) {
            return;
        }

        $pauseDuration = Carbon::parse($this->activeDuty->paused_at)->diffInSeconds(now());
        
        $this->activeDuty->update([
            'is_paused' => false,
            'paused_at' => null,
            'total_pause_duration' => $this->activeDuty->total_pause_duration + $pauseDuration,
        ]);

        $this->loadActiveDuty();
        $this->dispatch('duty-status-updated');
    }

    public function endDuty()
    {
        $this->validate();

        if (!$this->activeDuty) {
            return;
        }

        try {
            $totalDuration = Carbon::parse($this->activeDuty->started_at)->diffInSeconds(now()) - $this->activeDuty->total_pause_duration;

            DutyTimeClosed::create([
                'user_id' => auth()->id(),
                'started_at' => $this->activeDuty->started_at,
                'ended_at' => now(),
                'total_duration' => $totalDuration,
                'total_pause_duration' => $this->activeDuty->total_pause_duration,
                'proof_link' => $this->proofLink
            ]);

            $this->activeDuty->delete();
            $this->loadActiveDuty();
            $this->proofLink = null;
            $this->error = '';
            $this->dispatch('duty-ended');
        } catch (\Exception $e) {
            $this->error = 'Hiba történt a szolgálat lezárása közben. Kérjük próbáld újra.';
        }
    }

    public function render()
    {
        return view('livewire.duty-time-manager');
    }
}
