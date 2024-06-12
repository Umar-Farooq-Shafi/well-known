<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpCenterCategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_help_center_category_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function helpCenterCategory(): BelongsTo
    {
        return $this->belongsTo(HelpCenterCategory::class, 'translatable_id');
    }
}
