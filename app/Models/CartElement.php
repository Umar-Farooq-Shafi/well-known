<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartElement extends Model
{
    use HasFactory;

    protected $table = 'eventic_cart_element';

    protected $fillable = [
        'user_id',
        'eventticket_id',
        'quantity',
        'ticket_fee',
        'chosen_event_date'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
