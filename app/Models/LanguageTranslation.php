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
 * @property-read \App\Models\Language|null $language
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class LanguageTranslation extends Model
{
    use HasFactory, TranslationSlugTrait;

    protected $table = 'eventic_language_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'translatable_id');
    }
}
