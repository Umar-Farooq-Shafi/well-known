<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property-read Promotion|null $promotion
 * @method static Builder|PromotionQuantity newModelQuery()
 * @method static Builder|PromotionQuantity newQuery()
 * @method static Builder|PromotionQuantity query()
 * @mixin \Eloquent
 */
class PromotionQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'discount'
    ];

    /**
     * @return BelongsTo
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
