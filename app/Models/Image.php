<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['subdivision_id', 'name', 'path'];

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }
}