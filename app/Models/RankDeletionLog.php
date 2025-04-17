<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankDeletionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'deleted_at',
        'deleted_by',
        'requested_by',
    ];
}
