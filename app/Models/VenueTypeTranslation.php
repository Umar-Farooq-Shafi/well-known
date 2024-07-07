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
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\VenueType|null $venueType
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueTypeTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class VenueTypeTranslation extends Model
{
    use HasFactory, TranslationSlugTrait;

    public $timestamps = false;

    protected $table = 'eventic_venue_type_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function venueType(): BelongsTo
    {
        return $this->belongsTo(VenueType::class, 'translatable_id');
    }
}
