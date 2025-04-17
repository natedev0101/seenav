<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\User;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
        'created_by',
        'publish_at',
        'expires_at',
        'is_archived',
        'archived_at',
        'archived_by',
        'archived'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'archived_at' => 'datetime',
        'archived' => 'boolean'
    ];

    /**
     * A hír létrehozója.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Az archiváló felhasználó.
     */
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * A hírt megtekintő felhasználók.
     */
    public function views()
    {
        return $this->belongsToMany(User::class, 'user_news_views')
            ->withTimestamps()
            ->withPivot('viewed_at');
    }

    /**
     * Csak az aktív hírek lekérése.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Csak a nem archivált hírek lekérése.
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Csak az archivált hírek lekérése.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * A hír típusához tartozó szín.
     */
    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'info' => 'blue',
            'warning' => 'yellow',
            'success' => 'green',
            'danger' => 'red',
            default => 'gray'
        };
    }

    /**
     * A hír létrehozása óta eltelt idő.
     */
    public function getTimeAgoAttribute()
    {
        Carbon::setLocale('hu');
        return $this->created_at->diffForHumans();
    }

    /**
     * Az olvasatlan hírek száma.
     */
    public static function unreadCount()
    {
        $user = Auth::user();
        if (!$user) return 0;

        return static::where('archived', false)
            ->whereDoesntHave('views', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();
    }
}
