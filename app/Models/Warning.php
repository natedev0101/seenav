<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'type',
        'description',
        'points',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Típus konstansok
    const TYPE_PLUS = 'plusz_pont';
    const TYPE_MINUS = 'minusz_pont';
    const TYPE_WARNING = 'figyelmeztetés';

    // Típus lista
    public static function getTypes()
    {
        return [
            self::TYPE_PLUS => 'Plusz pont',
            self::TYPE_MINUS => 'Minusz pont',
            self::TYPE_WARNING => 'Figyelmeztetés'
        ];
    }

    // Típus szín osztályok
    public static function getTypeColors()
    {
        return [
            self::TYPE_PLUS => 'text-green-400',
            self::TYPE_MINUS => 'text-yellow-400',
            self::TYPE_WARNING => 'text-red-400'
        ];
    }
}
