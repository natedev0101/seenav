<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankChange extends Model
{
    protected $fillable = [
        'user_id',
        'old_rank_id',
        'new_rank_id',
        'changed_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function oldRank()
    {
        return $this->belongsTo(Rank::class, 'old_rank_id');
    }

    public function newRank()
    {
        return $this->belongsTo(Rank::class, 'new_rank_id');
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
