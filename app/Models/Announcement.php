<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Golonka\BBCode\BBCodeParser;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by'
    ];

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function likes()
    {
        return $this->belongsToMany(User::class, 'announcement_likes');
    }
    
    public function hearts()
    {
        return $this->belongsToMany(User::class, 'announcement_hearts');
    }

    public function getFormattedContentAttribute()
    {
        $parser = new BBCodeParser;
        
        // Tartalom átalakítása BB kódból HTML-be
        $content = $parser->parse($this->content);
        
        // A BBCodeParser alapértelmezetten kezeli a következő tageket:
        // [b], [i], [u], [s], [size], [color], [center], [quote], [url], [img], [code], [list], [*]
        
        // Új sorok kezelése
        $content = nl2br($content);
        
        return $content;
    }
}
