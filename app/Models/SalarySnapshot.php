<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'duty_time_minutes',
        'reports_count',
        'calculated_amount',
        'calculation_details',
        'closed_at',
        'closed_by'
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'duty_time_minutes' => 'integer',
        'reports_count' => 'integer',
        'calculated_amount' => 'integer',
        'calculation_details' => 'json',
        'closed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
