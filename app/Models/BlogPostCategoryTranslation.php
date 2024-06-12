<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostCategoryTranslation extends Model
{
    use HasFactory;

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
