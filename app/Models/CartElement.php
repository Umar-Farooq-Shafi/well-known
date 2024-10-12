<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $eventticket_id
 * @property int|null $quantity
 * @property string|null $ticket_fee
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $chosen_event_date
 * @property-read \App\Models\User|null $user
 * @method static Builder|CartElement newModelQuery()
 * @method static Builder|CartElement newQuery()
 * @method static Builder|CartElement query()
 * @method static Builder|CartElement whereChosenEventDate($value)
 * @method static Builder|CartElement whereCreatedAt($value)
 * @method static Builder|CartElement whereEventticketId($value)
 * @method static Builder|CartElement whereId($value)
 * @method static Builder|CartElement whereQuantity($value)
 * @method static Builder|CartElement whereTicketFee($value)
 * @method static Builder|CartElement whereUpdatedAt($value)
 * @method static Builder|CartElement whereUserId($value)
 * @property string|null $code
 * @property-read EventDateTicket|null $eventDateTicket
 * @method static Builder|CartElement whereCode($value)
 * @mixin \Eloquent
 */
class CartElement extends Model
{
    use HasFactory;

    protected $table = 'eventic_cart_element';

    protected $fillable = [
        'user_id',
        'eventticket_id',
        'quantity',
        'ticket_fee',
        'code',
        'chosen_event_date'
    ];

    protected $casts = [
        'chosen_event_date' => 'datetime'
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
    public function eventDateTicket(): BelongsTo
    {
        return $this->belongsTo(EventDateTicket::class, 'eventticket_id');
    }
}
