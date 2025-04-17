<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
        'deleted_by',
        'closing_metadata'
    ];

    protected $casts = [
        'fine_amount' => 'integer',
        'report_date' => 'datetime',
        'week_number' => 'integer',
        'year' => 'integer',
        'closing_metadata' => 'json'
    ];

    // Ki adta be a jelentést
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Ki kezelte a jelentést
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Járőrtársak
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'report_partners', 'report_id', 'partner_id')
            ->withTimestamps();
    }
}
