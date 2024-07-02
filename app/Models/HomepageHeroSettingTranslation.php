<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
