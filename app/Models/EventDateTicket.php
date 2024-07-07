<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int|null $eventdate_id
 * @property int $active
 * @property string $reference
 * @property string $name
 * @property string|null $description
 * @property int $free
 * @property string|null $price
 * @property string|null $promotionalprice
 * @property int|null $quantity
 * @property int|null $ticketsperattendee
 * @property string|null $salesstartdate
 * @property string|null $salesenddate
 * @property int $position
 * @property int|null $currency_code_id
 * @property int|null $ticket_fee
 * @property string $currency_symbol_position
 * @property-read \App\Models\EventDate|null $eventDate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderElement> $orderElements
 * @property-read int|null $order_elements_count
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereCurrencyCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereCurrencySymbolPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereEventdateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket wherePromotionalprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereSalesenddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereSalesstartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereTicketFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDateTicket whereTicketsperattendee($value)
 * @property-read \App\Models\Currency|null $currency
 * @mixin \Eloquent
 */
class EventDateTicket extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_event_date_ticket';

    protected $fillable = [
        'eventdate_id',
        'active',
        'reference',
        'name',
        'description',
        'free',
        'price',
        'promotionalprice',
        'quantity',
        'ticketsperattendee',
        'salesstartdate',
        'salesenddate',
        'position',
        'currency_code_id',
        'ticket_fee',
        'currency_symbol_position'
    ];

    /**
     * @return HasMany
     */
    public function orderElements(): HasMany
    {
        return $this->hasMany(OrderElement::class);
    }

    /**
     * @return BelongsTo
     */
    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class, 'eventdate_id');
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code_id');
    }

}
