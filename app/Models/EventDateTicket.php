<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketReservation> $ticketReservations
 * @property-read int|null $ticket_reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderTicket> $eventDateTickets
 * @property-read int|null $event_date_tickets_count
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
        'currency_symbol_position',
    ];

    public function getOrderElementsQuantitySum($status = 1, $user = "all", $role = "all"): int
    {
        $sum = 0;

        foreach ($this->orderElements as $orderElement) {
            if (($status == "all" ||
                    $orderElement?->order?->status === $status) &&
                ($user == "all" || $orderElement?->order?->user == $user)
                && ($role == "all" || $orderElement?->order?->user->hasRole($role))) {
                $sum += $orderElement->quantity;
            }
        }

        return $sum;
    }

    public function getTicketPricePercentageCutSum($role = "all"): float|int
    {
        $sum = 0;

        foreach ($this->orderElements as $orderElement) {
            if ($role == "all" || $orderElement->order?->user->hasRole($role)) {
                $sum += (($orderElement->order?->ticket_price_percentage_cut * $orderElement->unitprice) / 100) * $orderElement->quantity;
            }
        }

        return $sum;
    }

    public function getTicketsLeftCount(): ?int
    {
        return $this->quantity - $this->getOrderElementsQuantitySum() - $this->getValidTicketReservationsQuantitySum();
    }

    public function isSoldOut(): bool
    {
        if ($this->quantity == 0 || $this->getTicketsLeftCount() > 0) {
            return false;
        }

        return true;
    }

    public function getValidTicketReservationsQuantitySum($user = null): int
    {
        $sum = 0;

        foreach ($this->ticketReservations as $ticketReservation) {
            if (!$ticketReservation->isExpired() && ($user == null || $ticketReservation->user == $user)) {
                $sum += $ticketReservation->quantity;
            }
        }

        return $sum;
    }

    public function getSalePrice()
    {
        if ($this->promotionalprice) {
            return (float)$this->promotionalprice;
        }

        if ($this->price) {
            return (float)$this->price;
        }

        return 0;
    }

    public function getScannedTicketsCount(): int
    {
        $count = 0;

        foreach ($this->orderElements as $orderElement) {
            foreach ($orderElement->orderTickets as $ticket) {
                if ($ticket->scanned) {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function getSales(
        $role = "all",
        $user = "all",
        $formattedForPayoutApproval = false,
        $includeFees = false,
    ): float|int {
        $sum = 0;
        foreach ($this->orderElements as $orderElement) {
            if ($orderElement->order?->status === 1 &&
                ($role == "all" || $orderElement->order?->user?->hasRole($role))
                && ($user == "all" || $orderElement->order?->user == $user)) {
                $sum += $orderElement->getPrice($formattedForPayoutApproval);
            }
        }

        if ($includeFees) {
            $sum += $this->getTotalFees();
        }

        return $sum;
    }

    public function getTotalFees(): float|int
    {
        $sum = 0;
        $sum += $this->getTotalTicketFees();
        return $sum;
    }

    public function getTotalTicketFees($role = "all", $user = "all"): float|int
    {
        $sum = 0;

        foreach ($this->orderElements as $orderElement) {
            if ($orderElement->order?->status === 1
                && ($role == "all" || $orderElement->order?->user?->hasRole($role))
                && ($user == "all" || $orderElement->order?->user == $user) && !$this->free) {
                $sum += $orderElement->order->ticket_fee * $orderElement->quantity;
            }
        }

        return $sum;
    }

    public function isOnSale(): bool
    {
        if (!$this->eventDate || !$this->eventDate->event || !$this->eventDate->event->organizer || !$this->eventDate->event->organizer->user) {
            return false;
        }

        return $this->eventDate->event->organizer->user->enabled
            && $this->eventDate->event->published
            && $this->eventDate->active
            && (Carbon::make($this->eventDate->startdate)->greaterThanOrEqualTo(now())
                || ($this->eventDate->recurrent == true && Carbon::make(
                        $this->eventDate->recurrent_enddate,
                    )->greaterThan(now())))
            && $this->active
            && !$this->isSoldOut()
            && (
            (!$this->salesstartdate || Carbon::make($this->salesstartdate)->lessThan(now()))
            )
            && (
            (!$this->salesenddate || Carbon::make($this->salesenddate)->greaterThan(now()))
            )
            && (!$this->eventDate->payoutRequested());
    }

    /**
     * @return HasMany
     */
    public function orderElements(): HasMany
    {
        return $this->hasMany(OrderElement::class, 'eventticket_id');
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

    /**
     * @return HasMany
     */
    public function ticketReservations(): HasMany
    {
        return $this->hasMany(TicketReservation::class, 'eventticket_id');
    }

    public function eventDateTickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderTicket::class, // The final model you want to access
            OrderElement::class,    // The intermediate model
            'orderelement_id',      // Foreign key on the intermediate model (OrderElement)
            'id',                   // Foreign key on the final model (EventDateTicket)
            'id',                   // Local key on this model (OrderTicket)
            'eventticket_id',        // Local key on the intermediate model (OrderElement)
        );
    }

}
