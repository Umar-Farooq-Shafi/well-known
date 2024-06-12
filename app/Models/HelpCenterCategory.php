<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpCenterCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_help_center_category';

    protected $fillable = [
        'parent_id',
        'icon',
        'hidden'
    ];

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory(): BelongsTo
    {
        return $this->belongsTo(HelpCenterCategory::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterCategories(): HasMany
    {
        return $this->hasMany(HelpCenterCategory::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterCategoryTranslations(): HasMany
    {
        return $this->hasMany(HelpCenterCategoryTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function helpCenterArticles(): HasMany
    {
        return $this->hasMany(HelpCenterArticle::class, 'category_id');
    }

}
