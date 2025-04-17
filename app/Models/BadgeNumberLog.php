<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeNumberLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_number',
        'username',
        'assigned_to'
    ];

    /**
     * Az utolsó kiadott jelvényszám lekérése
     */
    public static function getLastBadgeNumber()
    {
        return static::latest()->first();
    }
}
