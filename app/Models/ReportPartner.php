<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportPartner extends Pivot
{
    use SoftDeletes;

    protected $table = 'report_partners';

    protected $fillable = [
        'report_id',
        'partner_id',
        'deleted_by',
        'closing_metadata'
    ];

    protected $casts = [
        'closing_metadata' => 'json'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
