<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property string $icon
 * @property int $hidden
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AmenityTranslation> $amenityTranslations
 * @property-read int|null $amenity_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues
 * @property-read int|null $venues_count
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Amenity withoutTrashed()
 * @mixin \Eloquent
 */
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
