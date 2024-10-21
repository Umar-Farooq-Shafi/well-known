<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

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
 * @property-read EventDate|null $eventDate
 * @property-read Collection<int, OrderElement> $orderElements
 * @property-read int|null $order_elements_count
 * @method static Builder|EventDateTicket newModelQuery()
 * @method static Builder|EventDateTicket newQuery()
 * @method static Builder|EventDateTicket query()
 * @method static Builder|EventDateTicket whereActive($value)
 * @method static Builder|EventDateTicket whereCurrencyCodeId($value)
 * @method static Builder|EventDateTicket whereCurrencySymbolPosition($value)
 * @method static Builder|EventDateTicket whereDescription($value)
 * @method static Builder|EventDateTicket whereEventdateId($value)
 * @method static Builder|EventDateTicket whereFree($value)
 * @method static Builder|EventDateTicket whereId($value)
 * @method static Builder|EventDateTicket whereName($value)
 * @method static Builder|EventDateTicket wherePosition($value)
 * @method static Builder|EventDateTicket wherePrice($value)
 * @method static Builder|EventDateTicket wherePromotionalprice($value)
 * @method static Builder|EventDateTicket whereQuantity($value)
 * @method static Builder|EventDateTicket whereReference($value)
 * @method static Builder|EventDateTicket whereSalesenddate($value)
 * @method static Builder|EventDateTicket whereSalesstartdate($value)
 * @method static Builder|EventDateTicket whereTicketFee($value)
 * @method static Builder|EventDateTicket whereTicketsperattendee($value)
 * @property-read \App\Models\Currency|null $currency
 * @property-read Collection<int, \App\Models\TicketReservation> $ticketReservations
 * @property-read int|null $ticket_reservations_count
 * @property-read Collection<int, \App\Models\OrderTicket> $eventDateTickets
 * @property-read int|null $event_date_tickets_count
 * @property-read Collection<int, \App\Models\PaymentGateway> $paymentGateways
 * @property-read int|null $payment_gateways_count
 * @property-read Collection<int, \App\Models\CartElement> $cartElements
 * @property-read int|null $cart_elements_count
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

    protected $casts = [
        'salesstartdate' => 'datetime',
        'salesenddate' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $uniqueStr = Str::random(8);

            while(EventDateTicket::where('reference', $uniqueStr)->exists()) {
                $uniqueStr = Str::random(8);
            }

            $model->reference = $uniqueStr;
        });
    }

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

    public function stringifyStatus(): string
    {
        if (!$this->eventDate?->event?->organizer?->user->enabled) {
            return "Organizer is disabled";
        }

        if (!$this->eventDate?->event->published) {
            return "Event is not published";
        }

        if (!$this->eventDate->active) {
            return "Event date is disabled";
        }

        if (Carbon::make($this->eventDate->startdate)->lessThan(now()) && !$this->eventDate->recurrent) {
            return "Event already started";
        }

        if (!$this->active) {
            return "Event ticket is disabled";
        }

        if ($this->isSoldOut()) {
            return "Sold out";
        }

        if ($this->salesstartdate && Carbon::make($this->salesstartdate)->greaterThan(now())) {
            return "Sale didn't start yet";
        }

        if ($this->salesstartdate && Carbon::make($this->salesenddate)->lessThan(now())) {
            return "Sale ended";
        }

        if ($this->eventDate->payoutRequests()) {
            return "Locked (Payout request " . strtolower(
                    $this->eventDate->payoutRequestStatus(),
                ) . ")";
        }

        return "On sale";
    }

    public function stringifyStatusClass(): string
    {
        if (!$this->eventDate->event->organizer->user->enabled) {
            return "danger";
        }

        if (!$this->eventDate->event->published) {
            return "danger";
        }

        if (!$this->eventDate->active) {
            return "danger";
        }

        if (Carbon::make($this->eventDate->startdate)->lessThan(now())) {
            return "info";
        }

        if (!$this->active) {
            return "danger";
        }

        if ($this->isSoldOut()) {
            return "warning";
        }

        if ($this->salesstartdate && Carbon::make($this->salesstartdate)->greaterThan(now())) {
            return "info";
        }

        if ($this->salesstartdate && Carbon::make($this->salesenddate)->lessThan(now())) {
            return "warning";
        }

        if ($this->eventDate->payoutRequested()) {
            return "warning";
        }

        return "success";
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
            // $orderElement->order?->status === 1
            if (($role == "all" || $orderElement->order?->user?->hasRole($role))
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
            // $orderElement->order?->status === 1
            if ($orderElement->order?->ticket_fee && ($role == "all" || $orderElement->order?->user?->hasRole($role))
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

        // Get the event's timezone or fallback to a default timezone (e.g., UTC)
        $eventTimezone = $this->eventDate->event->eventtimezone ?? 'UTC';

        // Convert both enddate and recurrent_enddate to the event's timezone if they exist
        $endDateInUserTimezone = $this->eventDate->enddate ? Carbon::make($this->eventDate->enddate)->timezone($eventTimezone) : null;
        $recurrentEndDateInUserTimezone = $this->eventDate->recurrent == true && $this->eventDate->recurrent_enddate
            ? Carbon::make($this->eventDate->recurrent_enddate)->timezone($eventTimezone)
            : null;

        return $this->eventDate->event->organizer->user->enabled
            && $this->eventDate->event->published
            && $this->eventDate->active
            && (($endDateInUserTimezone && $endDateInUserTimezone->greaterThan(now()))
                || ($recurrentEndDateInUserTimezone && $recurrentEndDateInUserTimezone->greaterThan(now())))
            && $this->active
            && !$this->isSoldOut()
            && !$this->eventDate->payoutRequested();
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

    /**
     * @return BelongsToMany
     */
    public function paymentGateways(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentGateway::class,
            'eventic_event_payment_gateway',
            'Ticket_id',
            'Payment_Gateway_id',
        );
    }

    /**
     * @return HasMany
     */
    public function cartElements(): HasMany
    {
        return $this->hasMany(CartElement::class, 'eventticket_id');
    }

}
