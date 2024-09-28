<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $eventticket_id
 * @property int|null $quantity
 * @property string|null $ticket_fee
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $chosen_event_date
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereChosenEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereEventticketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereTicketFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereUserId($value)
 * @property string|null $code
 * @property-read \App\Models\EventDateTicket|null $eventDateTicket
 * @method static \Illuminate\Database\Eloquent\Builder|CartElement whereCode($value)
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
