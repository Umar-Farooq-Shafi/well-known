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
 * @property-read \App\Models\BlogPostCategory|null $blogPostCategory
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategoryTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class BlogPostCategoryTranslation extends Model
{
    use HasFactory, TranslationSlugTrait;

    public $timestamps = false;

    protected $table = 'eventic_blog_post_category_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function blogPostCategory(): BelongsTo
    {
        return $this->belongsTo(BlogPostCategory::class, 'translatable_id');
    }
}
