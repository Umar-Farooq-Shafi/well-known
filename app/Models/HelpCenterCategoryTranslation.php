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
 * @property-read \App\Models\HelpCenterCategory|null $helpCenterCategory
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategoryTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class HelpCenterCategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_help_center_category_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory(): BelongsTo
    {
        return $this->belongsTo(HelpCenterCategory::class, 'translatable_id');
    }
}
