<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $icon
 * @property int $hidden
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpCenterArticle> $helpCenterArticles
 * @property-read int|null $help_center_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, HelpCenterCategory> $helpCenterCategories
 * @property-read int|null $help_center_categories_count
 * @property-read HelpCenterCategory|null $helpCenterCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpCenterCategoryTranslation> $helpCenterCategoryTranslations
 * @property-read int|null $help_center_category_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterCategory withoutTrashed()
 * @mixin \Eloquent
 */
class HelpCenterCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_help_center_category';

    protected $fillable = [
        'parent_id',
        'icon',
        'hidden'
    ];

    public function getNameAttribute()
    {
        return $this->helpCenterCategoryTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    public function getSlugAttribute()
    {
        return $this->helpCenterCategoryTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory(): BelongsTo
    {
        return $this->belongsTo(HelpCenterCategory::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterCategories(): HasMany
    {
        return $this->hasMany(HelpCenterCategory::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterCategoryTranslations(): HasMany
    {
        return $this->hasMany(HelpCenterCategoryTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterArticles(): HasMany
    {
        return $this->hasMany(HelpCenterArticle::class, 'category_id');
    }

}
