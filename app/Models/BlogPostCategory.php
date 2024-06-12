<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPostCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_blog_post_category';

    protected $fillable = [
        'hidden'
    ];

    /**
     * @return HasMany
     */
    public function blogPostCategoryTranslations(): HasMany
    {
        return $this->hasMany(BlogPostCategoryTranslation::class, 'translatable_id');
    }

}
