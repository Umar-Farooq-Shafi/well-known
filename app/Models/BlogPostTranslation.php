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
 * @property string $content
 * @property string|null $tags
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\BlogPost|null $blogPost
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class BlogPostTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_blog_post_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'content',
        'tags',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'translatable_id');
    }
}
