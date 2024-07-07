<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * 
 *
 * @property int $id
 * @property int|null $translatable_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $tags
 * @property string $locale
 * @property-read \App\Models\HelpCenterArticle|null $helpCenterArticle
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCenterArticleTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class HelpCenterArticleTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_help_center_article_translation';

    protected $fillable = [
        'translatable_id',
        'title',
        'slug',
        'content',
        'tags',
        'locale',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        self::updating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    /**
     * @return BelongsTo
     */
    public function helpCenterArticle(): BelongsTo
    {
        return $this->belongsTo(HelpCenterArticle::class, 'translatable_id');
    }

}
