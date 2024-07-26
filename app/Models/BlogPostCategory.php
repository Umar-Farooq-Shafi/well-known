<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int $hidden
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BlogPostCategoryTranslation> $blogPostCategoryTranslations
 * @property-read int|null $blog_post_category_translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BlogPost> $blogPosts
 * @property-read int|null $blog_posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostCategory withoutTrashed()
 * @mixin \Eloquent
 */
class BlogPostCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_blog_post_category';

    protected $fillable = [
        'hidden'
    ];

    public function getSlugAttribute()
    {
        return $this->blogPostCategoryTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    public function getNameAttribute()
    {
        return $this->blogPostCategoryTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    /**
     * @return HasMany
     */
    public function blogPostCategoryTranslations(): HasMany
    {
        return $this->hasMany(BlogPostCategoryTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

}
