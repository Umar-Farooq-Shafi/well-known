<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $quantity
 * @property string $discount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @method static Builder|Promotion newModelQuery()
 * @method static Builder|Promotion newQuery()
 * @method static Builder|Promotion query()
 * @method static Builder|Promotion whereCreatedAt($value)
 * @method static Builder|Promotion whereDiscount($value)
 * @method static Builder|Promotion whereId($value)
 * @method static Builder|Promotion whereQuantity($value)
 * @method static Builder|Promotion whereUpdatedAt($value)
 * @property-read Collection<int, \App\Models\PromotionQuantity> $promotionQuantities
 * @property-read int|null $promotion_quantities_count
 * @property string $name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @method static Builder|Promotion whereEndDate($value)
 * @method static Builder|Promotion whereName($value)
 * @method static Builder|Promotion whereStartDate($value)
 * @mixin \Eloquent
 */
class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'timezone',
        'organizer_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    /**
     * @return HasMany
     */
    public function promotionQuantities(): HasMany
    {
        return $this->hasMany(PromotionQuantity::class);
    }

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

}
