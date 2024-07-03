<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property string|null $image_name
 * @property int|null $image_size
 * @property string|null $image_mime_type
 * @property string|null $image_original_name
 * @property string|null $image_dimensions (DC2Type:simple_array)
 * @property int $hidden
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, AudienceTranslation> $audienceTranslations
 * @property-read int|null $audience_translations_count
 * @method static Builder|Audience newModelQuery()
 * @method static Builder|Audience newQuery()
 * @method static Builder|Audience onlyTrashed()
 * @method static Builder|Audience query()
 * @method static Builder|Audience whereCreatedAt($value)
 * @method static Builder|Audience whereDeletedAt($value)
 * @method static Builder|Audience whereHidden($value)
 * @method static Builder|Audience whereId($value)
 * @method static Builder|Audience whereImageDimensions($value)
 * @method static Builder|Audience whereImageMimeType($value)
 * @method static Builder|Audience whereImageName($value)
 * @method static Builder|Audience whereImageOriginalName($value)
 * @method static Builder|Audience whereImageSize($value)
 * @method static Builder|Audience whereUpdatedAt($value)
 * @method static Builder|Audience withTrashed()
 * @method static Builder|Audience withoutTrashed()
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @mixin Eloquent
 * @mixin \Eloquent
 */
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

    /**
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'eventic_event_audience');
    }

}
