<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
