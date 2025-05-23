<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\NameChangeRequest;

class PreviousName extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'previous_name',
        'changed_to',
        'name_change_request_id',
        'changed_at'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nameChangeRequest()
    {
        return $this->belongsTo(NameChangeRequest::class);
    }
}
