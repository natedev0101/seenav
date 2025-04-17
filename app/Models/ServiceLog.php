<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'minutes',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'minutes' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
