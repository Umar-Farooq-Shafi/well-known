<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $eventticket_id
 * @property int|null $user_id
 * @property int|null $orderelement_id
 * @property int|null $quantity
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\EventDateTicket|null $eventDateTicket
 * @property-read \App\Models\OrderElement|null $orderElement
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereEventticketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereOrderelementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReservation withoutTrashed()
 * @mixin \Eloquent
 */
class TicketReservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_ticket_reservation';

    public const UPDATED_AT = null;

    protected $fillable = [
        'eventticket_id',
        'user_id',
        'orderelement_id',
        'quantity',
        'expires_at',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at < new \DateTime;
    }

    /**
     * @return BelongsTo
     */
    public function eventDateTicket(): BelongsTo
    {
        return $this->belongsTo(EventDateTicket::class, 'eventticket_id');
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
    public function orderElement(): BelongsTo
    {
        return $this->belongsTo(OrderElement::class, 'orderelement_id');
    }

}
