<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\ServiceStatus;
use App\Http\Livewire\CookieConsent;
use App\Livewire\VehicleList;
use App\Livewire\VehicleForm;
use App\Livewire\VehicleTypeList;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Livewire::component('service-status', ServiceStatus::class);
        Livewire::component('cookie-consent', CookieConsent::class);
        Livewire::component('vehicle-list', VehicleList::class);
        Livewire::component('vehicle-form', VehicleForm::class);
        Livewire::component('vehicle-type-list', VehicleTypeList::class);
    }
}
