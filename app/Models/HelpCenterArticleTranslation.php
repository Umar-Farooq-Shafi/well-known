<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * @return BelongsTo
     */
    public function helpCenterArticle(): BelongsTo
    {
        return $this->belongsTo(HelpCenterArticle::class, 'translatable_id');
    }

}
