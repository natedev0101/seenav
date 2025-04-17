<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyTimeClosed extends Model
{
    use HasFactory;

    protected $table = 'duty_times_closed';

    protected $fillable = [
        'closed_week_id',
        'original_duty_time_id',
        'user_id',
        'started_at',
        'ended_at',
        'total_duration',
        'total_pause_duration',
        'week_number',
        'year'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'total_duration' => 'integer',
        'total_pause_duration' => 'integer',
        'week_number' => 'integer',
        'year' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
