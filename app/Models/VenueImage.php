<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $venue_id
 * @property string|null $image_name
 * @property int|null $image_size
 * @property string|null $image_mime_type
 * @property string|null $image_original_name
 * @property string|null $image_dimensions (DC2Type:simple_array)
 * @property int|null $position
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Venue|null $venue
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VenueImage whereVenueId($value)
 * @mixin \Eloquent
 */
class VenueImage extends Model
{
    use HasFactory, ImageTrait;

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

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            self::saveImage($model, 'venues', true);
        });

        self::updating(function ($model) {
            self::saveImage($model, 'venues');
        });
    }

    /**
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
