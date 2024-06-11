<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_venue_type';

    protected $fillable = [
        'hidden'
    ];

    /**
     * @return HasMany
     */
    public function venueTypeTranslations(): HasMany
    {
        return $this->hasMany(VenueTypeTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class, 'type_id');
    }
}
