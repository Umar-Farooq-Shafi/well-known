<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderElement extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'eventic_order_element';

    protected $fillable = [
        'order_id',
        'eventticket_id',
        'unitprice',
        'quantity',
        'chosen_event_date',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function eventDateTicket(): BelongsTo
    {
        return $this->belongsTo(EventDateTicket::class);
    }
}
