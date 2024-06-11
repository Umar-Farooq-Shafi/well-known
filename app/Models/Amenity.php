<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_amenity';

    protected $fillable = [
        'icon',
        'hidden'
    ];

    /**
     * @return HasMany
     */
    public function amenityTranslations(): HasMany
    {
        return $this->hasMany(AmenityTranslation::class, 'translatable_id');
    }

    /**
     * @return BelongsToMany
     */
    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'eventic_venue_amenity');
    }

}
