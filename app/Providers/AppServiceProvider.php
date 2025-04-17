<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\ReCaptcha;
use Livewire\Livewire;
use App\Livewire\DutyTimeManager;
use App\Livewire\CreateVehicleModal;
use App\Livewire\EditVehicleModal;
use App\Livewire\VehicleTypeList;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Greater than
        Validator::extend('greater_than', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value > $min_value;
        });
        Validator::replacer('greater_than', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });

        // Not more than now
        Validator::extend('less_than_now', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value <= $min_value;
        });   

        Validator::replacer('less_than_now', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });

        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            return (new ReCaptcha)->passes($attribute, $value);
        });

        Livewire::component('duty-time-manager', DutyTimeManager::class);
        Livewire::component('create-vehicle-modal', CreateVehicleModal::class);
        Livewire::component('edit-vehicle-modal', EditVehicleModal::class);
        Livewire::component('vehicle-type-list', VehicleTypeList::class);
    }
}
