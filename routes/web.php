<?php

use App\Http\Controllers\{
    ProfileController,
    Auth\PasswordController,
    DashboardController,
    DutyTimeController,
    AdminController,
    RankController,
    LogController,
    SubdivisionController,
    UserController,
    AnnouncementController,
    WarningController,
    ServiceSessionController,
    VersionController,
    Auth\TwoFactorController,
    HelpRequestController,
    HelpController,
    NameChangeController,
    ActivityLogController,
    ServiceController,
    NewsController,
    FAQController,
    LeaderController,
    TestEmailController,
    DutyController,
    ReportController,
    WeeklyClosingController,
    ParkController,
    ParkingController, // Added ParkingController
    TaxController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// GYIK route
Route::get('/gyik', [FAQController::class, 'index'])->name('faq');
Route::get('/banyasz', function () {
    return view('miner');
})->name('miner.index');


// Session expired route
Route::get('/session-expired', function () {
    return redirect()->route('login')->with('status', 'A munkameneted lejárt. Kérjük, jelentkezz be újra.');
})->name('session.expired');

Route::middleware(['auth'])->group(function () {
    // Kötelező jelszóváltoztatás route
    Route::get('/jelszo-valtoztatas', function () {
        return view('auth.force-password-change');
    })->name('password.force-change');
    Route::post('/jelszo-valtoztatas', [PasswordController::class, 'forceChange'])->name('password.force-change.store');

    // Fizetési információk API (auth middleware elég, nem kell 2FA)
    Route::get('/api/salary-info', [SalaryController::class, 'getSalaryInfo'])->name('salary.info');

    // Felhasználó rang kezelés API végpontok
    Route::middleware(['admin'])->group(function () {
        Route::get('/api/users/{user}/rank-limits', [UserController::class, 'getRankLimits']);
        Route::post('/api/users/{user}/promote', [UserController::class, 'promote']);
        Route::post('/api/users/{user}/demote', [UserController::class, 'demote']);
        Route::post('/api/users/{user}/rang', [UserController::class, 'updateRank']);
        Route::post('/api/users/{user}/subdivisions', [UserController::class, 'updateSubdivisions']);
    });

    // Profile routes
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil/{id}/felhasznalonev', [ProfileController::class, 'updateUsername'])->name('profile.updateUsername');
    Route::patch('/profil/{id}/ingame-nev', [ProfileController::class, 'updateCharacterName'])->name('profile.updateCharacterName');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profil/kep', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::post('/profil/kep-torles', [ProfileController::class, 'removePicture'])->name('profile.removePicture');
    Route::patch('/profil/{id}/jatekadatok', [ProfileController::class, 'updateGameData'])->name('profile.updateGameData');
    Route::patch('/profil/{id}/jelszo', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // 2FA routes
    Route::get('/2fa/beallitas', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/aktivalas', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::get('/2fa/ellenorzes', [TwoFactorController::class, 'showVerify'])->name('2fa.verify');
    Route::post('/2fa/ellenorzes', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
    Route::post('/2fa/kikapcsolas', [TwoFactorController::class, 'disable'])->name('2fa.disable');
    
    // Segítségkérés route
    Route::post('/segitsegkeres', [HelpRequestController::class, 'sendRequest'])->name('help.request.send');

    // Hírek route-ok
    Route::prefix('hirek')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('news.index');
        Route::get('/letrehozas', [NewsController::class, 'create'])->name('news.create');
        Route::post('/', [NewsController::class, 'store'])->name('news.store');
        Route::get('/{news}', [NewsController::class, 'show'])->name('news.show');
        Route::post('/{news}/frissites', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
        Route::post('/{news}/olvasott', [NewsController::class, 'markAsRead'])->name('news.read');
        Route::post('/{news}/archivalas', [NewsController::class, 'archive'])->name('news.archive');
    });

    Route::prefix('api/news')->group(function () {
        Route::get('/latest', [NewsController::class, 'getLatest'])->name('news.latest');
        Route::post('/{news}/read', [NewsController::class, 'markAsRead'])->name('news.read');
        Route::post('/{news}/archive', [NewsController::class, 'archive'])->name('news.archive');
    });

    // Jelentések route-ok
    Route::get('/jelentesek', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/jelentesek/uj', [ReportController::class, 'create'])->name('reports.create');
    Route::get('/jelentesek/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/jelentesek/statisztika/{user}', [ReportController::class, 'statistics'])->name('reports.statistics');
    Route::post('/jelentesek', [ReportController::class, 'store'])->name('reports.store');
    Route::delete('/jelentesek/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('/jelentesek/{report}/elfogadas', [ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/jelentesek/{report}/elutasitas', [ReportController::class, 'reject'])->name('reports.reject');
    Route::get('/jelentesek/felhasznalok-keresese', [ReportController::class, 'searchUsers'])->name('reports.search-users');

    // Minden védett route 2FA middleware mögött
    Route::middleware(['force.password.change'])->group(function () {
        // Szolgálat route-ok
        Route::get('/duty', [DutyController::class, 'index'])->name('duty.index');
        Route::post('/duty/start', [DutyController::class, 'startDuty'])->name('duty.start');
        Route::post('/duty/end', [DutyController::class, 'endDuty'])->name('duty.end');
        Route::post('/duty/{userId}/force-end', [DutyController::class, 'forceEndDuty'])
            ->name('duty.force-end')
            ->middleware(['auth', 'verified']);
        
        // Szolgálati rendszer route-ok
        Route::get('/szolgalat', [ServiceController::class, 'index'])->name('service.view');
        Route::post('/szolgalat/valtas', [ServiceController::class, 'toggle'])->name('service.toggle');
        Route::get('/szolgalat/aktiv-felhasznalok', [ServiceController::class, 'activeUsers'])->name('service.activeUsers');
        Route::get('/szolgalat/statisztikak', [ServiceController::class, 'getStats'])->name('service.stats');
        
        Route::get('/fooldal', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/fooldal/beallitasok', [DashboardController::class, 'updatePreferences'])->name('dashboard.preferences');

        // Statisztikák megtekintése
        Route::get('/statisztikaim', [DashboardController::class, 'statistics'])->name('user.statistics');

        // Felhasználók megtekintése (minden bejelentkezett felhasználó számára elérhető)
        Route::get('/felhasznalok', [UserController::class, 'index'])->name('users.index');
        Route::get('/felhasznalok/{id}', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');
        Route::post('/felhasznalok/{id}/jelszo-reset', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::post('/felhasznalok/{id}/nev-valtoztatas', [NameChangeController::class, 'directNameChange'])->name('users.updateName');

        // Leader routes
        Route::group(['prefix' => 'leader', 'as' => 'leader.', 'middleware' => ['auth', 'admin']], function () {
            Route::get('/heti-statisztika', [LeaderController::class, 'weeklyStats'])->name('weekly-stats');
            Route::post('/het-lezarasa', [LeaderController::class, 'closeWeek'])->name('close-week');
            Route::get('/park', [LeaderController::class, 'park'])->name('park');
        });

        // Parkoló API végpontok
        Route::prefix('api/parkings')->middleware(['auth', 'admin'])->group(function () {
            Route::get('/occupancy', [ParkingController::class, 'getOccupancy']);
            Route::post('/request', [ParkingController::class, 'requestSpot']);
            Route::post('/release', [ParkingController::class, 'releaseSpot']);
        });

        // Parkoló kiosztás route-ok
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/parking', [LeaderController::class, 'park'])->name('park');
            Route::post('/parking/igenyel', [ParkController::class, 'request'])->name('park.request');
            Route::post('/parking/torol', [ParkController::class, 'delete'])->name('park.delete');
        });

        // Névváltási kérelmek
        Route::get('/nevvaltas/keres', [NameChangeController::class, 'showRequestForm'])->name('name-change.request');
        Route::post('/nevvaltas/keres', [NameChangeController::class, 'submitRequest'])->name('name-change.submit');
        Route::get('/nevvaltas/admin', [NameChangeController::class, 'adminIndex'])->name('name-change.admin');
        Route::put('/nevvaltas/{nameChangeRequest}/feldolgozas', [NameChangeController::class, 'processRequest'])->name('name-change.process');

        // Admin route-ok
        Route::middleware(['admin'])->group(function () {
            // Weekly Closing Routes
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('/heti-lezaras', [WeeklyClosingController::class, 'index'])->name('weekly-closing.index');
                Route::post('/heti-lezaras/bezars', [WeeklyClosingController::class, 'close'])->name('weekly-closing.close');
                Route::get('/heti-lezaras/{id}', [WeeklyClosingController::class, 'view'])->name('weekly-closing.view');
                Route::get('/heti-lezaras/{weekId}/felhasznalo/{userId}', [WeeklyClosingController::class, 'userReports'])
                    ->name('weekly-closing.user-reports');
            });

            // Leader panel routes
            Route::get('/leader', function () {
                return view('leader.index');
            })->name('leader');
            Route::get('/api/leader/stats', [LeaderController::class, 'getStats'])->name('leader.stats');
            Route::post('/api/leader/promote/{userId}', [LeaderController::class, 'promoteUser'])->name('leader.promote')->where('userId', '[0-9]+');
            Route::post('/api/leader/demote/{userId}', [LeaderController::class, 'demoteUser'])->name('leader.demote')->where('userId', '[0-9]+');
            Route::post('/api/leader/points/add/{userId}', [LeaderController::class, 'addPoint'])->name('leader.points.add')->where('userId', '[0-9]+');
            Route::post('/api/leader/points/remove/{userId}', [LeaderController::class, 'removePoint'])->name('leader.points.remove')->where('userId', '[0-9]+');
            
            // Járművek
            Route::get('/jarmuvek', function () {
                return view('database.jarmuvek');
            })->name('vehicles.index');
            
            Route::get('/adminisztracio/eltoltott-ido', [AdminController::class, 'showTimeSpent'])->name('admin.time-spent');
            Route::get('/regisztracio', [AdminController::class, 'registerUser'])->name('admin.register_user');
            Route::post('/regisztracio', [AdminController::class, 'storeUser'])->name('admin.store_user');
            
            // Közlemények
            Route::get('/kozlemenyek', [AnnouncementController::class, 'index'])->name('announcements.index');
            Route::get('/kozlemenyek/letrehozas', [AnnouncementController::class, 'create'])->name('announcements.create');
            Route::post('/kozlemenyek', [AnnouncementController::class, 'store'])->name('announcements.store');
            Route::get('/kozlemenyek/{announcement}/szerkesztes', [AnnouncementController::class, 'edit'])->name('announcements.edit');
            Route::put('/kozlemenyek/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
            Route::delete('/kozlemenyek/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

            // Figyelmeztetések
            Route::get('/figyelmeztetesek', [WarningController::class, 'index'])->name('warnings.index');
            Route::get('/figyelmeztetesek/letrehozas', [WarningController::class, 'create'])->name('warnings.create');
            Route::post('/figyelmeztetesek', [WarningController::class, 'store'])->name('warnings.store');
            Route::get('/figyelmeztetesek/{warning}/szerkesztes', [WarningController::class, 'edit'])->name('warnings.edit');
            Route::put('/figyelmeztetesek/{warning}', [WarningController::class, 'update'])->name('warnings.update');
            Route::delete('/figyelmeztetesek/{warning}', [WarningController::class, 'destroy'])->name('warnings.destroy');

            // Rangok és alosztályok
            Route::prefix('rangok')->group(function () {
                Route::get('/', [RankController::class, 'index'])->name('ranks.index');
                Route::get('/letrehozas', [RankController::class, 'create'])->name('ranks.create');
                Route::post('/', [RankController::class, 'store'])->name('ranks.store');
                Route::get('/{rank}/szerkesztes', [RankController::class, 'edit'])->name('ranks.edit');
                Route::put('/{rank}', [RankController::class, 'update'])->name('ranks.update');
                Route::delete('/{rank}', [RankController::class, 'destroy'])->name('ranks.destroy');
                Route::get('/{rank}/users-json', [RankController::class, 'getUsersJson'])->name('ranks.users.json');
                Route::get('/{rank}/felhasznalok', [RankController::class, 'showUsers'])->name('ranks.users');
            });

            Route::prefix('alosztalyok')->group(function () {
                Route::get('/', [SubdivisionController::class, 'index'])->name('subdivisions.index');
                Route::get('/letrehozas', [SubdivisionController::class, 'create'])->name('subdivisions.create');
                Route::post('/', [SubdivisionController::class, 'store'])->name('subdivisions.store');
                Route::get('/{subdivision}/szerkesztes', [SubdivisionController::class, 'edit'])->name('subdivisions.edit');
                Route::put('/{subdivision}', [SubdivisionController::class, 'update'])->name('subdivisions.update');
                Route::delete('/{subdivision}', [SubdivisionController::class, 'destroy'])->name('subdivisions.destroy');
                Route::get('/{subdivision}/tagok', [SubdivisionController::class, 'members'])->name('subdivisions.members');
                Route::get('/{subdivision}/felhasznalok', [SubdivisionController::class, 'showUsers'])->name('subdivisions.users');
                Route::get('/{subdivision}/felhasznalok/json', [SubdivisionController::class, 'getUsersJson'])->name('subdivisions.users.json');
                Route::post('/{subdivision}/kep', [SubdivisionController::class, 'uploadImage'])->name('subdivisions.upload-image');
                Route::get('/hozzarendeles', [SubdivisionController::class, 'assign'])->name('subdivisions.assign');
                Route::post('/hozzarendeles', [SubdivisionController::class, 'assignUpdate'])->name('subdivisions.assign.update');
            });

            Route::get('/adminisztracio/napló', [LogController::class, 'index'])->name('admin.logs');
            Route::get('/adminisztracio/napló/keres', [LogController::class, 'search'])->name('admin.logs.search');
            
            Route::get('/adminisztracio/tevekenysegnaplo', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
            Route::delete('/adminisztracio/tevekenysegnaplo/{log}', [ActivityLogController::class, 'destroy'])->name('admin.activity-logs.destroy');
            
            Route::get('/verziók', [VersionController::class, 'getVersions'])->name('admin.versions');
            Route::post('/adminisztracio/verzió', [VersionController::class, 'store'])->name('admin.version.store');
            Route::post('/adminisztracio/verzió/{version}/aktualis', [VersionController::class, 'setCurrent'])->name('admin.version.set-current');
            Route::post('/adminisztracio/verzió/{version}', [VersionController::class, 'destroy'])->name('admin.version.destroy');
            Route::post('/adminisztracio/verzió/{version}/frissites', [VersionController::class, 'updateVersion'])->name('admin.versions.update');

            // Lezárt esetek
            Route::prefix('lezart-esetek')->name('closed-cases.')->group(function () {
                Route::get('/{week}', [ClosedCasesController::class, 'show'])->name('show');
                Route::post('/', [ClosedCasesController::class, 'store'])->name('store');
                Route::delete('/{week}', [ClosedCasesController::class, 'destroy'])->name('destroy');
            });

            // Csak superadmin specifikus route-ok
            Route::middleware(['superadmin'])->group(function () {
                Route::get('/superadmin/webmester', [App\Http\Controllers\SuperAdmin\WebmesterController::class, 'index'])->name('superadmin.webmester');
                Route::post('/superadmin/webmester/2fa/toggle', [App\Http\Controllers\SuperAdmin\WebmesterController::class, 'toggle2FA'])
                    ->name('superadmin.webmester.2fa.toggle');
            });
        });
        
        // Felhasználók kezelése
        Route::middleware(['admin'])->group(function () {
            Route::match(['post', 'patch'], '/felhasznalok/{id}/rang', [UserController::class, 'updateRank'])->name('user.update.rank');
            Route::post('/felhasznalok/{id}/alosztalyok-frissites', [UserController::class, 'updateSubdivisions'])->name('user.update.subdivisions');
            Route::post('/felhasznalok/{id}/jelvenyek-frissites', [UserController::class, 'updateBadges'])->name('user.update.badges');
            Route::post('/felhasznalok/{id}/telefonszam-frissites', [UserController::class, 'updatePhone'])->name('user.update.phone');
            Route::match(['post', 'patch'], '/felhasznalok/{id}/jatekadatok', [UserController::class, 'updateGameData'])->name('user.update.gamedata');
        });

        // Profil kezelése
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profil/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profil/kep', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
        Route::post('/profil/kep-torles', [ProfileController::class, 'removePicture'])->name('profile.removePicture');

        // Jelszó kezelése
        Route::put('/jelszo', [PasswordController::class, 'update'])->name('password.update');

        // 2FA segítségkérés
        Route::post('/segitsegkeres/2fa', [HelpController::class, 'request'])->name('help.2fa.request');

        // Naplózás
        Route::get('/naplo', [LogController::class, 'index'])->name('logs.index');

        // Duty-time route-ok
        Route::resource('duty-time', DutyTimeController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->middleware(['auth', 'verified']);

        // Hírek kezelése
        Route::prefix('hirek')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('news.index');
            Route::get('/letrehozas', [NewsController::class, 'create'])->name('news.create');
            Route::post('/', [NewsController::class, 'store'])->name('news.store');
            Route::get('/{news}', [NewsController::class, 'show'])->name('news.show');
            Route::post('/{news}/frissites', [NewsController::class, 'update'])->name('news.update');
            Route::delete('/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
            Route::post('/{news}/olvasott', [NewsController::class, 'markAsRead'])->name('news.read');
            Route::post('/{news}/archivalas', [NewsController::class, 'archive'])->name('news.archive');
        });

        Route::prefix('api/news')->group(function () {
            Route::get('/latest', [NewsController::class, 'getLatest'])->name('news.latest');
            Route::post('/{news}/read', [NewsController::class, 'markAsRead'])->name('news.read');
            Route::post('/{news}/archive', [NewsController::class, 'archive'])->name('news.archive');
        });
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vehicle-types', function () {
        return view('database.vehicle-types');
    })->name('vehicle-types.index');
    Route::get('/reports/salaries', [ReportController::class, 'salaries'])->name('reports.salaries');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/fizetesek', [SalaryController::class, 'index'])->name('salary.index');
    Route::post('/reports-salary', [ReportsSalarySettingController::class, 'update'])->name('reports-salary.update');
    Route::get('birsagok', [FineController::class, 'index'])->name('fines.index');
    Route::post('birsagok', [FineController::class, 'store'])->name('fines.store');
    Route::put('birsagok/{fine}', [FineController::class, 'update'])->name('fines.update');
    Route::delete('birsagok/{fine}', [FineController::class, 'destroy'])->name('fines.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('lezart-esetek')->name('closed-cases.')->group(function () {
        Route::get('/', [ClosedCasesController::class, 'index'])->name('index');
        Route::get('/{week}', [ClosedCasesController::class, 'show'])->name('show');
        Route::get('/{week}/jelentes/{id}', [ClosedCasesController::class, 'report'])->name('report');
        Route::post('/het-lezaras', [ClosedCasesController::class, 'closeWeek'])->middleware('web')->name('close');
        Route::delete('/{week}', [ClosedCasesController::class, 'destroy'])->name('delete');
    });
});

// Policy routes - publikus hozzáférés
Route::get('/suti-szabalyzat', function () {
    return view('policy.cookie-policy');
})->name('policy.cookie');

Route::get('/adatvedelmi-szabalyzat', function () {
    return view('policy.privacy');
})->name('policy.privacy');

Route::get('/felhasznalasi-feltetelek', function () {
    return view('policy.terms');
})->name('policy.terms');

Route::get('/dupla-kep-ellenorzes', function (Request $request) {
    $exists = CaseReport::where('image_link', $request->image)->exists();
    return response()->json(['exists' => $exists]);
});

Route::post('kijelentkezes', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->middleware('App\Http\Middleware\UpdateUserOfflineStatus')
    ->name('logout');

Route::get('/test-email', [TestEmailController::class, 'sendTestEmail'])
    ->middleware(['auth'])
    ->name('test.email');

//TAX
Route::middleware(['auth'])->group(function () {
    Route::get('/tax', [TaxController::class, 'init'])->name('tax');
    Route::post('/tax/{type}', [TaxController::class, 'handler'])->name('tax.handler');
});

require __DIR__.'/auth.php';