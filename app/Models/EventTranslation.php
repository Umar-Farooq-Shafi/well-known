<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $translatable_id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\Event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class EventTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_event_translation';

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
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'translatable_id');
    }

}
