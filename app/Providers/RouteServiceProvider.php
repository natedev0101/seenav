<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\CaseReport;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/fooldal';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::pattern('id', '[0-9]+');
        
        // Magyar URL-ek definiálása
        Route::resourceVerbs([
            'create' => 'letrehozas',
            'edit' => 'szerkesztes',
            'index' => 'lista',
            'show' => 'megtekintes',
            'store' => 'mentes',
            'update' => 'frissites',
            'destroy' => 'torles'
        ]);
        
        Route::bind('user', function ($value) {
            return \App\Models\User::findOrFail($value);
        });

        Route::bind('case_report', function ($value) {
            return \App\Models\CaseReport::findOrFail($value);
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Magyar URL-ek definiálása a route-okhoz
     */
    protected function defineHungarianUrls(): void
    {
        // Szolgálati rendszer
        // URL átírások definiálása
        Route::patterns([
            'service' => 'szolgalat',
            'dashboard' => 'fooldal',
            'cases' => 'esetek',
            'case-reports' => 'jelentesek',
            'announcements' => 'kozlemenyek',
            'warnings' => 'figyelmeztetesek',
            'ranks' => 'rangok',
            'subdivisions' => 'alegysegek',
            'users' => 'felhasznalok',
            'fines' => 'birsagok',
            'help' => 'segitseg',
            'profile' => 'profil',
            'name-change' => 'nevvaltas',
            'admin' => 'adminisztracio',
        ]);
    }
}
