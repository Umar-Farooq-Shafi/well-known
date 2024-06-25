<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int|null $organizer_id
 * @property int|null $type_id
 * @property int|null $country_id
 * @property int|null $seatedguests
 * @property int|null $standingguests
 * @property string|null $neighborhoods
 * @property string|null $foodbeverage
 * @property string|null $pricing
 * @property string|null $availibility
 * @property int $hidden
 * @property bool $showmap
 * @property bool $quoteform
 * @property string $street
 * @property string|null $street2
 * @property string $city
 * @property string $state
 * @property string $postalcode
 * @property string|null $lat
 * @property string|null $lng
 * @property bool $listedondirectory
 * @property string|null $contactemail
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Amenity> $amenities
 * @property-read int|null $amenities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventDate> $eventDates
 * @property-read int|null $event_dates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VenueImage> $venueImages
 * @property-read int|null $venue_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VenueTranslation> $venueTranslations
 * @property-read int|null $venue_translations_count
 * @property-read \App\Models\VenueType|null $venueType
 * @method static \Illuminate\Database\Eloquent\Builder|Venue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereAvailibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereContactemail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereFoodbeverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereListedondirectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereNeighborhoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue wherePostalcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue wherePricing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereQuoteform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereSeatedguests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereShowmap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereStandingguests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereStreet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue withoutTrashed()
 * @mixin \Eloquent
 */
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

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
