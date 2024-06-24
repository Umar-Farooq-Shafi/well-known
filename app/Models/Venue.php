<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_venue';

    protected $fillable = [
        'organizer_id',
        'type_id',
        'country_id',
        'seatedguests',
        'standingguests',
        'neighborhoods',
        'foodbeverage',
        'pricing',
        'availibility',
        'hidden',
        'showmap',
        'quoteform',
        'street',
        'street2',
        'city',
        'state',
        'postalcode',
        'lat',
        'lng',
        'listedondirectory',
        'contactemail'
    ];

    protected $casts = [
        'listedondirectory' => 'boolean',
        'quoteform' => 'boolean',
        'showmap' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * @return HasMany
     */
    public function eventDates(): HasMany
    {
        return $this->hasMany(EventDate::class);
    }

    /**
     * @return HasManyThrough
     */
    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(
            Event::class,
            EventDate::class,
            'event_id',
            'id',
            'id',
            'event_id'
        );
    }

    /**
     * @return HasMany
     */
    public function venueTranslations(): HasMany
    {
        return $this->hasMany(VenueTranslation::class, 'translatable_id');
    }

    /**
     * @return BelongsTo
     */
    public function venueType(): BelongsTo
    {
        return $this->belongsTo(VenueType::class, 'type_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'eventic_venue_amenity');
    }

    /**
     * @return HasMany
     */
    public function venueImages(): HasMany
    {
        return $this->hasMany(VenueImage::class);
    }

}
