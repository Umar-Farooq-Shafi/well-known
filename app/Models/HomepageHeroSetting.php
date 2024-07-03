<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $content
 * @property string|null $custom_background_name
 * @property int|null $custom_background_size
 * @property string|null $custom_background_mime_type
 * @property string|null $custom_background_original_name
 * @property string|null $custom_background_dimensions (DC2Type:simple_array)
 * @property int|null $show_search_box
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $homepage_featured_events_nb
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HomepageHeroSettingTranslation> $homepageHeroSettingTranslations
 * @property-read int|null $homepage_hero_setting_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereCustomBackgroundDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereCustomBackgroundMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereCustomBackgroundName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereCustomBackgroundOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereCustomBackgroundSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereHomepageFeaturedEventsNb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereShowSearchBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomepageHeroSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HomepageHeroSetting extends Model
{
    use HasFactory;

    public const CREATED_AT = null;

    protected $table = 'eventic_homepage_hero_setting';

    protected $fillable = [
        'content',
        'custom_background_name',
        'custom_background_size',
        'custom_background_mime_type',
        'custom_background_original_name',
        'custom_background_dimensions',
        'show_search_box',
        'homepage_featured_events_nb'
    ];

    /**
     * @return HasMany
     */
    public function homepageHeroSettingTranslations(): HasMany
    {
        return $this->hasMany(HomepageHeroSettingTranslation::class, 'translatable_id');
    }

}
