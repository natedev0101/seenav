<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class UpdateUserOnlineStatus
{
    public function handle(Login $event)
    {
        if (Auth::check()) {
            Auth::user()->update(['is_online' => true]);
        }
    }
}
