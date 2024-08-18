<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $venue_id
 * @property int $online
 * @property int $active
 * @property string $reference
 * @property string|null $startdate
 * @property string|null $enddate
 * @property int|null $recurrent
 * @property string|null $recurrent_startdate
 * @property string|null $recurrent_enddate
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\Venue|null $venue
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereEnddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereRecurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereRecurrentEnddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereRecurrentStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventDate whereVenueId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventDateTicket> $eventDateTickets
 * @property-read int|null $event_date_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PointsOfSale> $pointOfSales
 * @property-read int|null $point_of_sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Scanner> $scanners
 * @property-read int|null $scanners_count
 * @property-read mixed $organizer_payout_amount
 * @property-read mixed $ticket_price_percentage_cut_sum
 * @property-read int|float $ticket_sold
 * @property-read mixed $total_ticket_fees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PayoutRequest> $payoutRequests
 * @property-read int|null $payout_requests_count
 * @mixin \Eloquent
 */
class EventDate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_event_date';

    protected $fillable = [
        'event_id',
        'venue_id',
        'online',
        'active',
        'reference',
        'startdate',
        'enddate',
        'recurrent',
        'recurrent_startdate',
        'recurrent_enddate',
    ];

    public function getTicketSoldAttribute(): float|int
    {
        if ($this->getOrderElementsQuantitySum() == 0) {
            return 0;
        }

        return round(($this->getScannedTicketsCount() / $this->getOrderElementsQuantitySum()) * 100);
    }

    public function getTotalCheckInPercentage(): float|int
    {
        if ($this->getOrderElementsQuantitySum() == 0) {
            return 0;
        }

        return round(($this->getScannedTicketsCount() / $this->getOrderElementsQuantitySum()) * 100);
    }

    public function getOrganizerPayoutAmountAttribute()
    {
        return $this->getSales() - $this->getTicketPricePercentageCutSum() - $this->getSales("ROLE_POINTOFSALE");
    }

    public function getTicketPricePercentageCutSumAttribute()
    {
        return $this->getTicketPricePercentageCutSum();
    }

    public function getTotalTicketFeesAttribute()
    {
        return $this->getTotalTicketFees();
    }

    public function stringifyStatus(): string
    {
        if (!$this->event->organizer->user->enabled) {
            return "Organizer is disabled";
        }

        if (!$this->event->published) {
            return "Event is not published";
        }

        if (!$this->active) {
            return "Event date is disabled";
        }

        if ($this->startdate < new \Datetime) {
            return "Event already started";
        }

        if ($this->isSoldOut()) {
            return "Sold out";
        }

        if ($this->payoutRequested()) {
            return "Locked (Payout request " . strtolower($this->payoutRequestStatus()) . ")";
        }

        if (!$this->hasATicketOnSale()) {
            return "No ticket on sale";
        }

        return "On sale";
    }

    public function stringifyStatusClass()
    {
        if (!$this->event->organizer->user->enabled) {
            return "danger";
        }

        if (!$this->active) {
            return "danger";
        }

        if (!$this->event->published) {
            return "warning";
        }

        if ($this->startdate < new \Datetime) {
            return "info";
        }

        if ($this->isSoldOut()) {
            return "warning";
        }

        if ($this->payoutRequested()) {
            return "warning";
        }

        if (!$this->hasATicketOnSale()) {
            return "danger";
        }

        return "success";
    }

    public function hasATicketOnSale(): bool
    {
        foreach ($this->eventDateTickets as $ticket) {
            if ($ticket->isOnSale()) {
                return true;
            }
        }

        return false;
    }

    public function isOnSale(): bool
    {
        dump($this->event->organizer?->user?->enabled);
        dump($this->active);
        dump($this->event->published);
        dump($this->startdate);
        dump(($this->startdate > new \Datetime || $this->recurrent == true));
        dump((!$this->isSoldOut()));
        dump($this->hasATicketOnSale());
        dump(!$this->payoutRequested());
        die();
        return (
            $this->event->organizer?->user?->enabled && $this->active && $this->event->published
                && ($this->startdate > new \Datetime || $this->recurrent == true)
                && (!$this->isSoldOut()) && $this->hasATicketOnSale() && (!$this->payoutRequested())
        );
    }

    public function getCurrencyCode()
    {
        $ccys = [];

        foreach ($this->eventDateTickets as $ticket) {
            if ($ticket->currency && !in_array($ticket->currency->ccy, $ccys)) {
                $ccys[] = $ticket->currency->ccy;
            }
        }

        if (count($ccys) > 1) {
            return '';
        }

        return count($ccys) ? $ccys[0] : '';
    }

    public function payoutRequestStatus()
    {
        foreach ($this->payoutRequests as $payoutRequest) {
            if ($payoutRequest->status == 0 || $payoutRequest->status == 1) {
                return $payoutRequest->stringifyStatus();
            }
        }

        return "Unknown";
    }


    public function payoutRequested(): bool
    {
        foreach ($this->payoutRequests as $payoutRequest) {
            if ($payoutRequest->status == 0 || $payoutRequest->status == 1) {
                return true;
            }
        }

        return false;
    }

    public function isSoldOut(): bool
    {
        foreach ($this->eventDateTickets as $ticket) {
            if (!$ticket->isSoldOut()) {
                return false;
            }
        }

        return true;
    }

    public function getTotalTicketFees(): float|int
    {
        return $this->getSales("all", "all", false, true) - $this->getSales();
    }

    public function getSales(
        $role = "all",
        $user = "all",
        $formattedForPayoutApproval = false,
        $includeFees = false,
    ): float|int {
        $sum = 0;

        foreach ($this->eventDateTickets as $eventDateTicket) {
            $sum += $eventDateTicket->getSales($role, $user, $formattedForPayoutApproval, $includeFees);
        }

        return $sum;
    }

    public function getTicketPricePercentageCutSum($role = "all"): float|int
    {
        $sum = 0;

        foreach ($this->eventDateTickets as $eventDateTicket) {
            $sum += $eventDateTicket->getTicketPricePercentageCutSum($role);
        }

        return $sum;
    }

    public function getOrderElementsQuantitySum($status = 1, $user = "all", $role = "all"): int
    {
        $sum = 0;

        foreach ($this->eventDateTickets as $ticket) {
            $sum += $ticket->getOrderElementsQuantitySum($status, $user, $role);
        }

        return $sum;
    }

    public function getScannedTicketsCount(): int
    {
        $count = 0;

        foreach ($this->eventDateTickets as $ticket) {
            $count += $ticket->getScannedTicketsCount();
        }

        return $count;
    }

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * @return BelongsToMany
     */
    public function scanners(): BelongsToMany
    {
        return $this->belongsToMany(
            Scanner::class,
            'eventic_eventdate_scanner',
            'eventdate_id',
            'scanner_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function pointOfSales(): BelongsToMany
    {
        return $this->belongsToMany(
            PointsOfSale::class,
            'eventic_eventdate_pointofsale',
            'eventdate_id',
            'pointofsale_id',
        );
    }

    /**
     * @return HasMany
     */
    public function eventDateTickets(): HasMany
    {
        return $this->hasMany(EventDateTicket::class, 'eventdate_id');
    }

    /**
     * @return HasMany
     */
    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class, 'event_date_id');
    }

}
