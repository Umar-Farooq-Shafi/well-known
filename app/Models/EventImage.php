<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $event_id
 * @property string|null $image_name
 * @property int|null $image_size
 * @property string|null $image_mime_type
 * @property string|null $image_original_name
 * @property string|null $image_dimensions (DC2Type:simple_array)
 * @property int|null $position
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventImage extends Model
{
    use HasFactory;

    public const CREATED_AT = null;

    protected $table = 'eventic_event_image';

    protected $fillable = [
        'event_id',
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'position'
    ];

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

}
