<?php
use App\Models\Log;

function logAction($action, $details)
{
    $user = auth()->user(); // Aktuális felhasználó
    $username = $user->username ?? 'Ismeretlen';
    $charactername = $user->charactername ?? 'Nincs karakternév';

    Log::create([
        'action' => $action,
        'details' => "$charactername ($username) - $details",
        'user_id' => $user->id ?? null,
    ]);
}