<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int $hidden
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VenueTypeTranslation> $venueTypeTranslations
 * @property-read int|null $venue_type_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues
 * @property-read int|null $venues_count
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueType withoutTrashed()
 * @property-read mixed $name
 * @mixin \Eloquent
 */
class VenueType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_venue_type';

    protected $fillable = [
        'hidden'
    ];

    public function getNameAttribute()
    {
        return $this->venueTypeTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

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
