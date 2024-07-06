<?php

namespace App\Models;

use App\Traits\TranslationSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int|null $translatable_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\Venue|null $venue
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class VenueTranslation extends Model
{
    use HasFactory, TranslationSlugTrait;

    public $timestamps = false;

    protected $table = 'eventic_venue_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'description',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'translatable_id');
    }
}
