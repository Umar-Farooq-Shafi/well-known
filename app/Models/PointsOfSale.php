<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $organizer_id
 * @property int|null $user_id
 * @property string $name
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale query()
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PointsOfSale whereUserId($value)
 * @mixin \Eloquent
 */
class PointsOfSale extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_pointofsale';

    protected $fillable = [
        'organizer_id',
        'user_id',
        'name',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
