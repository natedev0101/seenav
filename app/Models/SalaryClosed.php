<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryClosed extends Model
{
    use HasFactory;

    protected $table = 'salaries_closed';

    protected $fillable = [
        'closed_week_id',
        'user_id',
        'minutes',
        'report_count',
        'merkur_count',
        'ado_count',
        'knyf_count',
        'beo_count',
        'sanitec_count',
        'top_report_count',
        'base_salary',
        'total_salary',
        'is_paid',
        'paid_by',
        'paid_at',
        'week_number',
        'year'
    ];

    protected $casts = [
        'minutes' => 'integer',
        'report_count' => 'integer',
        'merkur_count' => 'integer',
        'ado_count' => 'integer',
        'knyf_count' => 'integer',
        'beo_count' => 'integer',
        'sanitec_count' => 'integer',
        'top_report_count' => 'integer',
        'base_salary' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'week_number' => 'integer',
        'year' => 'integer'
    ];

    public function closedWeek()
    {
        return $this->belongsTo(ClosedWeek::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
