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
 * @property-read \App\Models\Audience|null $audience
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AudienceTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class AudienceTranslation extends Model
{
    use HasFactory, TranslationSlugTrait;

    protected $table = 'eventic_audience_translation';

    public $timestamps = false;

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class, 'translatable_id');
    }
}
