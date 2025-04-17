<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

class UpdateUserOfflineStatus
{
    public function handle(Logout $event)
    {
        if (Auth::check()) {
            Auth::user()->update(['is_online' => false]);
        }
    }
}
