<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'currency_symbol_position'
    ];

    /**
     * @return HasMany
     */
    public function orderElements(): HasMany
    {
        return $this->hasMany(OrderElement::class);
    }
}
