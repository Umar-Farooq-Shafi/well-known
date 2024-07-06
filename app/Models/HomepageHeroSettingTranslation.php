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
 * @property string|null $title
 * @property string|null $paragraph
 * @property string $locale
 * @property-read \App\Models\HomepageHeroSetting|null $homepageHeroSetting
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation whereParagraph($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSettingTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class HomepageHeroSettingTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_homepage_hero_setting_translation';

    protected $fillable = [
        'translatable_id',
        'title',
        'paragraph',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function homepageHeroSetting(): BelongsTo
    {
        return $this->belongsTo(HomepageHeroSetting::class, 'translatable_id');
    }

}
