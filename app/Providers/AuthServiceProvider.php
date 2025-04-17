<?php

namespace App\Providers;

use App\Models\Subdivision;
use App\Policies\SubdivisionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Subdivision::class => SubdivisionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-name-changes', function ($user) {
            return $user->isAdmin || $user->is_superadmin;
        });
    }
}
