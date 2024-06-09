<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->belongsTo(PaymentGateway::class, 'paymentgateway_id', 'id');
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
