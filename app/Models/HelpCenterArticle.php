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
 * @property int|null $category_id
 * @property int|null $views
 * @property int $hidden
 * @property int $featured
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\HelpCenterCategory|null $helpCenterCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpCenterArticleTranslation> $helpCenterTranslations
 * @property-read int|null $help_center_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticle withoutTrashed()
 * @property-read mixed $slug
 * @property-read mixed $title
 * @mixin \Eloquent
 */
class HelpCenterArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_help_center_article';

    protected $fillable = [
        'category_id',
        'views',
        'hidden',
        'featured'
    ];

    public function getTitleAttribute()
    {
        return $this->helpCenterTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->title;
    }

    public function getSlugAttribute()
    {
        return $this->helpCenterTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory()
    {
        return $this->belongsTo(HelpCenterCategory::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterTranslations(): HasMany
    {
        return $this->hasMany(HelpCenterArticleTranslation::class, 'translatable_id');
    }
}
