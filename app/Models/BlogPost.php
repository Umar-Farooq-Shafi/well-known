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
 * @property int|null $readtime
 * @property string|null $image_name
 * @property int|null $image_size
 * @property string|null $image_mime_type
 * @property string|null $image_original_name
 * @property string|null $image_dimensions (DC2Type:simple_array)
 * @property int|null $views
 * @property int $hidden
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BlogPostCategory|null $blogPostCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BlogPostTranslation> $blogPostTranslations
 * @property-read int|null $blog_post_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereReadtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost withoutTrashed()
 * @mixin \Eloquent
 */
class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_blog_post';

    protected $fillable = [
        'category_id',
        'readtime',
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'views',
        'hidden'
    ];

    /**
     * @return BelongsTo
     */
    public function blogPostCategory(): BelongsTo
    {
        return $this->belongsTo(BlogPostCategory::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function blogPostTranslations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class, 'translatable_id');
    }
}
