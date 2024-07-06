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

    /**
     * @return BelongsToMany
     */
    public function scanners(): BelongsToMany
    {
        return $this->belongsToMany(
            Scanner::class,
            'eventic_eventdate_scanner',
            'eventdate_id',
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

}
