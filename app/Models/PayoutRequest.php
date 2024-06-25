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
 * @property int|null $organizer_id
 * @property int|null $payment_gateway_id
 * @property int|null $event_date_id
 * @property string|null $payment (DC2Type:json_array)
 * @property string $reference
 * @property string|null $note
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read PayoutRequest|null $paymentGateway
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereEventDateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest wherePaymentGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PayoutRequest withoutTrashed()
 * @mixin \Eloquent
 */
class PayoutRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_payout_request';

    protected $fillable = [
        'organizer_id',
        'payment_gateway_id',
        'event_date_id',
        'payment',
        'reference',
        'note',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * @return BelongsTo
     */
    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PayoutRequest::class);
    }

}
