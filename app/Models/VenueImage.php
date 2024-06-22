<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenueImage extends Model
{
    use HasFactory;

    public const CREATED_AT = null;

    protected $table = 'eventic_venue_image';

    protected $fillable = [
        'venue_id',
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'position',
    ];

    /**
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
