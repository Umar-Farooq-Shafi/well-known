<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'recurrent_enddate'
    ];

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

}
