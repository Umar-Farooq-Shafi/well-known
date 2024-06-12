<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
