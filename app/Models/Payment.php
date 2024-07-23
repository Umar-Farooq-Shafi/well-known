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
 * @property int|null $order_id
 * @property int|null $country_id
 * @property string|null $number
 * @property string|null $description
 * @property string|null $client_email
 * @property string|null $client_id
 * @property int|null $total_amount
 * @property string|null $currency_code
 * @property array $details (DC2Type:json_array)
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $state
 * @property string|null $city
 * @property string|null $postalcode
 * @property string|null $street
 * @property string|null $street2
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereClientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePostalcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStreet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withoutTrashed()
 * @property-read string $stringify_address
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_payment';

    protected $fillable = [
        'order_id',
        'country_id',
        'number',
        'description',
        'client_email',
        'client_id',
        'total_amount',
        'currency_code',
        'details',
        'firstname',
        'lastname',
        'state',
        'city',
        'postalcode',
        'street',
        'street2'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    /**
     * @return string
     */
    public function getStringifyAddressAttribute(): string
    {
        return $this->street . " " . $this->street2 . ", " . $this->city . " " . $this->state . " " . $this->postalcode;
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
