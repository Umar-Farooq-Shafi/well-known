<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AudienceTranslation> $audienceTranslations
 * @property-read int|null $audience_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Audience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Audience onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Audience query()
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Audience withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Audience withoutTrashed()
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
