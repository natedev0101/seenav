<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'month',
        'year',
        'status',
        'paid_at',
        'deleted_by',
        'closing_metadata'
    ];

    protected $casts = [
        'amount' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'paid_at' => 'datetime',
        'closing_metadata' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
