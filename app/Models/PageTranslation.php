<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTranslation extends Model
{
    use HasFactory;

    protected $table = 'eventic_page_translation';

    protected $fillable = [
        'translatable_id',
        'title',
        'slug',
        'content',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'translatable_id');
    }
}
