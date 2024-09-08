<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @mixin \Eloquent
 */
class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'discount'
    ];

    /**
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }
}
