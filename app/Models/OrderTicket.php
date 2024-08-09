<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property int|null $orderelement_id
 * @property string $reference
 * @property int $scanned
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\OrderElement|null $orderElement
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereOrderelementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereScanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket withoutTrashed()
 * @mixin \Eloquent
 */
class OrderTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_order_ticket';

    protected $fillable = [
        'orderelement_id',
        'reference',
        'scanned',
    ];

    public function orderElement(): BelongsTo
    {
        return $this->belongsTo(OrderElement::class, 'orderelement_id');
    }

    public function eventDateTicket(): HasOneThrough
    {
        return $this->hasOneThrough(
            EventDateTicket::class, // The final model you want to access
            OrderElement::class,    // The intermediate model
            'id',                   // Foreign key on the intermediate model (OrderElement) referencing its primary key
            'id',                   // Foreign key on the final model (EventDateTicket) referencing its primary key
            'orderelement_id',      // Local key on this model (OrderTicket)
            'eventticket_id'        // Local key on the intermediate model (OrderElement)
        );
    }

}
