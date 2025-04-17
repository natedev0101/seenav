<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ServiceLog;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceStatus extends Component
{
    public $isOnDuty = false;
    public $serviceStartTime = null;
    public $serviceDuration = 0;
    public $activeUsers = [];
    public $notes = '';

    protected $listeners = ['refreshActiveUsers'];

    public function mount()
    {
        $user = Auth::user();
        $this->isOnDuty = $user->is_on_duty;
        $this->serviceStartTime = $user->service_start;
        $this->loadActiveUsers();
    }

    public function toggleService()
    {
        if ($this->isOnDuty) {
            $this->endService();
        } else {
            $this->startService();
        }
    }

    public function startService()
    {
        $controller = app()->make(ServiceController::class);
        $request = new \Illuminate\Http\Request();
        $request->merge(['notes' => $this->notes]);
        
        $response = $controller->startService($request);
        $data = json_decode($response->getContent());
        
        if ($response->getStatusCode() === 200) {
            $this->isOnDuty = true;
            $this->serviceStartTime = now();
            $this->notes = '';
            $this->emit('refreshActiveUsers');
            $this->dispatchBrowserEvent('service-started');
        }
    }

    public function endService()
    {
        $controller = app()->make(ServiceController::class);
        $request = new \Illuminate\Http\Request();
        $request->merge(['notes' => $this->notes]);
        
        $response = $controller->endService($request);
        $data = json_decode($response->getContent());
        
        if ($response->getStatusCode() === 200) {
            $this->isOnDuty = false;
            $this->serviceStartTime = null;
            $this->notes = '';
            $this->emit('refreshActiveUsers');
            $this->dispatchBrowserEvent('service-ended');
        }
    }

    public function loadActiveUsers()
    {
        $this->activeUsers = User::where('is_on_duty', true)
            ->with(['rank', 'subdivisions'])
            ->get()
            ->map(function ($user) {
                $user->service_duration = Carbon::parse($user->service_start)->diffInMinutes(now());
                return $user;
            });
    }

    public function refreshActiveUsers()
    {
        $this->loadActiveUsers();
    }

    public function getServiceDuration()
    {
        if ($this->isOnDuty && $this->serviceStartTime) {
            return Carbon::parse($this->serviceStartTime)->diffInMinutes(now());
        }
        return 0;
    }

    public function render()
    {
        $this->serviceDuration = $this->getServiceDuration();
        
        return view('livewire.service-status', [
            'serviceDuration' => $this->serviceDuration
        ]);
    }
}
