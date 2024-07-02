<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
