<?php

namespace App\Models;

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
 * @property-read \App\Models\Amenity|null $amenity
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmenityTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class AmenityTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_amenity_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function amenity(): BelongsTo
    {
        return $this->belongsTo(Amenity::class, 'translatable_id');
    }
}
