<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedWeek extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'closed_by',
        'closed_at',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'closed_at' => 'datetime'
    ];

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function reports()
    {
        return $this->hasMany(ReportClosed::class);
    }
}
