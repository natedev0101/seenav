<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportClosed extends Model
{
    use HasFactory;

    protected $table = 'reports_closed';

    protected $fillable = [
        'original_report_id',
        'user_id',
        'suspect_name',
        'type',
        'fine_amount',
        'image_url',
        'description',
        'status',
        'rejection_reason',
        'handled_by',
        'report_date',
        'week_number',
        'year',
        'closed_week_id'
    ];

    protected $casts = [
        'report_date' => 'date',
        'fine_amount' => 'decimal:2',
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

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'report_partners_closed', 'report_id', 'partner_id')
            ->withTimestamps();
    }
}
