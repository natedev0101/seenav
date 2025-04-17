<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ParkingController;
use App\Models\User;
use App\Models\Subdivision;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Felhasználó online státuszának frissítése
Route::middleware('auth:sanctum')->post('/user/set-offline', function (Request $request) {
    if (Auth::check()) {
        Auth::user()->update(['is_online' => false]);
        return response()->json(['success' => true, 'message' => 'Online státusz frissítve']);
    }
    return response()->json(['success' => false, 'message' => 'Felhasználó nincs bejelentkezve'], 401);
});

// Hírek API
Route::middleware('auth:sanctum')->get('/news/latest', [NewsController::class, 'getLatest']);

// Leader API végpontok
Route::middleware(['auth:sanctum'])->prefix('leader')->group(function () {
    // Statisztikák lekérése
    Route::get('/stats', function (Request $request) {
        $sort = $request->query('sort', 'charactername');
        $direction = $request->query('direction', 'asc');
        
        $users = User::with(['subdivisions' => function($query) {
            $query->select('subdivisions.id', 'subdivisions.name', 'subdivisions.color');
        }])
        ->orderBy($sort, $direction)
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'charactername' => $user->charactername,
                'role' => $user->role,
                'rank' => $user->rank,
                'rank_id' => $user->rank_id,
                'rank_color' => $user->rank_color,
                'plus_points' => $user->plus_points,
                'subdivisions' => $user->subdivisions->map(function($subdivision) {
                    return [
                        'id' => $subdivision->id,
                        'name' => $subdivision->name,
                        'color' => $subdivision->color
                    ];
                })->toArray()
            ];
        });
            
        return response()->json([
            'users' => $users,
            'currentSort' => [
                'column' => $sort,
                'direction' => $direction
            ]
        ]);
    });

    // Alosztályok lekérése tagok számával
    Route::get('/subdivisions', function () {
        $subdivisions = Subdivision::withCount('assignedUsers as users_count')
            ->orderBy('users_count', 'desc')
            ->get();
        return response()->json([
            'subdivisions' => $subdivisions
        ]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/parkings/occupancy', [ParkingController::class, 'getOccupancy']);
    Route::post('/parkings/request', [ParkingController::class, 'requestSpot']);
    Route::post('/parkings/release', [ParkingController::class, 'releaseSpot']);
});
