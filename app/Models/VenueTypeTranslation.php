<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueTypeTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_venue_type_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function venueType(): BelongsTo
    {
        return $this->belongsTo(VenueType::class, 'translatable_id');
    }
}
