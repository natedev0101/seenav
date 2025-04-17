<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_type_id',
        'veh_id',
        'registration_expiry',
        'warnings',
        'notes',
    ];

    protected $casts = [
        'registration_expiry' => 'date',
        'warnings' => 'array',
    ];

    /**
     * A jármű típusa
     */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Az autó tulajdonosai (maximum 2)
     */
    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vehicle_user', 'vehicle_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Az autóhoz tartozó alosztály
     */
    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class);
    }

    /**
     * Az autóhoz tartozó rang
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }
}
