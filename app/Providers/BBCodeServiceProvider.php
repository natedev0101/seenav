<?php

namespace App\Providers;

use App\Services\BBCodeService;
use Illuminate\Support\ServiceProvider;

class BBCodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('bbcode', function ($app) {
            return new BBCodeService();
        });
    }

    public function boot()
    {
        //
    }
}
