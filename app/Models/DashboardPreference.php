<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardPreference extends Model
{
    protected $table = 'dashboard_preferences';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'visible_cards'
    ];

    protected $casts = [
        'visible_cards' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
