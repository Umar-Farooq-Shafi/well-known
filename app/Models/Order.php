<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $paymentgateway_id
 * @property int|null $payment_id
 * @property string $reference
 * @property string|null $note
 * @property int $status
 * @property string $ticket_fee
 * @property int $ticket_price_percentage_cut
 * @property string $currency_ccy
 * @property string $currency_symbol
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventDateTicket> $eventDateTickets
 * @property-read int|null $event_date_tickets_count
 * @property-read \App\Models\OrderElement|null $orderElement
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read \App\Models\PaymentGateway|null $paymentGateway
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrencyCcy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentgatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTicketFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTicketPricePercentageCut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 * @property-read \App\Models\Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderElement> $orderElements
 * @property-read int|null $order_elements_count
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_order';

    protected $fillable = [
        'user_id',
        'paymentgateway_id',
        'payment_id',
        'reference',
        'note',
        'status',
        'ticket_fee',
        'ticket_price_percentage_cut',
        'currency_ccy',
        'currency_symbol'
    ];

    public function stringifyStatus(): string
    {
        return match ($this->status) {
            -2 => "Failed",
            -1 => "Canceled",
            0 => "Awaiting payment",
            1 => "Paid",
            default => "Unknown",
        };
    }

    public function getStatusClass()
    {
        return match ($this->status) {
            0 => "warning",
            1 => "success",
            default => "danger",
        };
    }

    public function getPaymentStatusClass($status)
    {
        if ($status == "new") {
            return "info";
        }

        if ($status == "pending") {
            return "warning";
        }

        if ($status == "authorized") {
            return "success";
        }

        if ($status == "captured") {
            return "success";
        }

        if ($status == "canceled") {
            return "danger";
        }

        if ($status == "suspended") {
            return "danger";
        }

        if ($status == "failed") {
            return "danger";
        }

        if ($status == "unknown") {
            return "danger";
        }

        return "";
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'paymentgateway_id');
    }

    /**
     * @return HasOneThrough
     */
    public function organizer(): HasOneThrough
    {
        return $this->hasOneThrough(
            Organizer::class,
            PaymentGateway::class,
            'organizer_id',
            'id',
            'paymentgateway_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @return HasMany
     */
    public function orderElements(): HasMany
    {
        return $this->hasMany(OrderElement::class);
    }

    /**
     * @return HasManyThrough
     */
    public function eventDateTickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            EventDateTicket::class,
            OrderElement::class,
            'order_id',
            'eventdate_id',
        );
    }

}
