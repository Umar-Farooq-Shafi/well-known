<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
