<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_venue_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'description',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'translatable_id');
    }
}
