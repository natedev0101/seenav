<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $table = 'parkings';

    protected $fillable = [
        'spot_id',
        'owner_name',
        'request_date',
        'is_occupied'
    ];

    protected $casts = [
        'is_occupied' => 'boolean',
        'request_date' => 'date'
    ];
}
