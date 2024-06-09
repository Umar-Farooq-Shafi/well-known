<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $table = 'eventic_payment_gateway';

    const CREATED_AT = null;

    protected $fillable = [
        'organizer_id',
        'gateway_name',
        'factory_name',
        'config',
        'name',
        'slug',
        'gateway_logo_name',
        'enabled',
        'number',
        'membership_type'
    ];

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

}
