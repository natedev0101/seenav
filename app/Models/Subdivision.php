<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;

    /**
     * A modellek kitölthető mezői.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'salary',
        'color'
    ];

    /**
     * A modellek típusai.
     *
     * @var array
     */
    protected $casts = [
        'salary' => 'decimal:2'
    ];

    /**
     * Egy alosztályhoz tartozó felhasználók.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Egy alosztályhoz kapcsolódó, hozzárendelt felhasználók.
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'subdivision_user', 'subdivision_id', 'user_id');
    }

    /**
     * Egy alosztályhoz tartozó képek.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Egy alosztály egyetlen képének elérése.
     * (Ha az alosztályhoz csak egyetlen kép tartozik.)
     */
    public function singleImage()
    {
        return $this->hasOne(Image::class);
    }

    /**
     * Accessor: Az alosztály neve mindig **bold** legyen.
     */
    public function getFormattedNameAttribute()
    {
        return '<strong>' . e($this->name) . '</strong>';
    }
}