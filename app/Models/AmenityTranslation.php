<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmenityTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_amenity_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function amenity(): BelongsTo
    {
        return $this->belongsTo(Amenity::class, 'translatable_id');
    }
}
