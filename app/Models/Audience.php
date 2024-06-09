<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audience extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_audience';

    protected $fillable = [
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'hidden'
    ];

    /**
     * @return HasMany
     */
    public function audienceTranslations(): HasMany
    {
        return $this->hasMany(AudienceTranslation::class, 'translatable_id');
    }
}
