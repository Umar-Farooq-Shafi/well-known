<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpCenterArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_help_center_article';

    protected $fillable = [
        'category_id',
        'views',
        'hidden',
        'featured'
    ];

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory()
    {
        return $this->belongsTo(HelpCenterCategory::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterTranslations(): HasMany
    {
        return $this->hasMany(HelpCenterArticleTranslation::class, 'translatable_id');
    }
}
