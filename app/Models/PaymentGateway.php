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
 * @property int|null $organizer_id
 * @property string $gateway_name
 * @property string $factory_name
 * @property string $config (DC2Type:json_array)
 * @property string $name
 * @property string $slug
 * @property string|null $gateway_logo_name
 * @property int $enabled
 * @property int|null $number
 * @property \Illuminate\Support\Carbon $updated_at
 * @property array|null $membership_type
 * @property-read \App\Models\Organizer|null $organizer
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereFactoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereGatewayLogoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereGatewayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereMembershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentGateway whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventDateTicket> $eventDateTickets
 * @property-read int|null $event_date_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $deletedOrders
 * @property-read int|null $deleted_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @mixin \Eloquent
 */
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

    protected $casts = [
        'membership_type' => 'array',
        'config' => 'json'
    ];

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    /**
     * @return BelongsToMany
     */
    public function eventDateTickets(): BelongsToMany
    {
        return $this->belongsToMany(
            EventDateTicket::class,
            'eventic_event_payment_gateway',
            'Payment_Gateway_id',
            'Ticket_id',
        );
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'paymentgateway_id');
    }

    /**
     * @return HasMany
     */
    public function deletedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'paymentgateway_id')->withTrashed();
    }

}
