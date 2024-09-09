<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string $discount
 * @property string $duration
 * @property string $expire_date
 * @property string $limit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @method static Builder|Coupon newModelQuery()
 * @method static Builder|Coupon newQuery()
 * @method static Builder|Coupon query()
 * @method static Builder|Coupon whereCode($value)
 * @method static Builder|Coupon whereCreatedAt($value)
 * @method static Builder|Coupon whereDiscount($value)
 * @method static Builder|Coupon whereDuration($value)
 * @method static Builder|Coupon whereExpireDate($value)
 * @method static Builder|Coupon whereId($value)
 * @method static Builder|Coupon whereLimit($value)
 * @method static Builder|Coupon whereName($value)
 * @method static Builder|Coupon whereType($value)
 * @method static Builder|Coupon whereUpdatedAt($value)
 * @property Carbon|null $start_date
 * @method static Builder|Coupon whereStartDate($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount',
        'start_date',
        'expire_date',
        'limit',
        'timezone',
        'organizer_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_coupon');
    }

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

}
