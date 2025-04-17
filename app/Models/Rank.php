<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'salary',
        'promotion_days'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'promotion_days' => 'integer'
    ];

    protected $attributes = [
        'promotion_days' => 0
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getMaxId()
    {
        return static::max('id');
    }
}
