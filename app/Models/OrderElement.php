<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $eventticket_id
 * @property string|null $unitprice
 * @property int|null $quantity
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $chosen_event_date
 * @property-read \App\Models\EventDateTicket|null $eventDateTicket
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereChosenEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereEventticketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement whereUnitprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderElement withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderTicket> $orderTickets
 * @property-read int|null $order_tickets_count
 * @property-read mixed $sub_total
 * @mixin \Eloquent
 */
class OrderElement extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'eventic_order_element';

    protected $fillable = [
        'order_id',
        'eventticket_id',
        'unitprice',
        'quantity',
        'chosen_event_date',
    ];

    public function getSubTotalAttribute()
    {
        return $this->quantity * $this->unitprice;
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function eventDateTicket(): BelongsTo
    {
        return $this->belongsTo(EventDateTicket::class, 'eventticket_id');
    }

    public function orderTickets(): HasMany
    {
        return $this->hasMany(OrderTicket::class, 'orderelement_id');
    }

    /**
     * @param $slug
     * @return bool
     */
    public function belongsToOrganizer($slug): bool
    {
        return $this->eventDateTicket->eventDate->event->organizer->slug == $slug;
    }

    /**
     * @return float
     */
    public function displayUnitPrice(): float
    {
        return (float) $this->unitprice;
    }

}
