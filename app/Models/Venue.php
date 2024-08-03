<?php

namespace App\Models;

use App\Observers\VenueObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
 * @property-read \App\Models\Country|null $country
 * @property-read mixed $name
 * @property-read string $stringify_address
 * @property-read mixed $description
 * @property-read mixed $slug
 * @mixin \Eloquent
 */
#[ObservedBy([VenueObserver::class])]
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
     * @return string
     */
    public function getStringifyAddressAttribute(): string
    {
        return $this->street . " " . $this->street2 . ", " . $this->city . " " . $this->state . " " . $this->postalcode;
    }

    public function getSlugAttribute()
    {
        return $this->venueTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    public function getNameAttribute()
    {
        return $this->venueTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    public function getDescriptionAttribute()
    {
        return $this->venueTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->description;
    }

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
     * @return string
     */
    public function getFirstImageOrPlaceholder(): string
    {
        if (count($this->venueImages) > 0) {
            return $this->venueImages->first()->getImagePath();
        }

        return $this->getImagePlaceholder();
    }

    /**
     * @param string $size
     * @return string
     */
    public function getImagePlaceholder(string $size = "default"): string
    {
        if ($size == "small") {
            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAMAAABG8BK2AAAAS1BMVEUAAAD2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhH2dhGpYChqAAAAGHRSTlMAAgYHDyMmKT9JTlFSVVaAhZ2oqq3g+/1i504YAAAAwklEQVRYw+2XQQ7CIBREx1aLFkRtFef+J3UBERtNAMUF+t9mNuSFMJDwAUH4NTYXprmeVglNjoXkNqHJs1CnNWebIE9jkV7TpEZZ0wPojVUxOh8FGkcaAIZ0MUYfBZoQlmQM7aNAM9EpAMpxijH4gBQuhf9T4YMeOwDdqIenkMKlcCm89cIracIRLyk/4pebKi88XL8l5dcvPIb14RjYvfUYAvv49/ykKSuar2hmfWd+mBIieZoao0elQajSWCYI7XEDcpBQF5AyIN0AAAAASUVORK5CYII=";
        }

        return "data:image/png;base64,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";
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
