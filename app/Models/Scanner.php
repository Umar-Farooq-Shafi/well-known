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
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scanner whereUserId($value)
 * @mixin \Eloquent
 */
class Scanner extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_scanner';

    protected $fillable = [
        'organizer_id',
        'user_id',
        'name'
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
