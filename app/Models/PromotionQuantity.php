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
 * @property int $id
 * @property int $quantity
 * @property string $discount
 * @property int $promotion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|PromotionQuantity whereCreatedAt($value)
 * @method static Builder|PromotionQuantity whereDiscount($value)
 * @method static Builder|PromotionQuantity whereId($value)
 * @method static Builder|PromotionQuantity wherePromotionId($value)
 * @method static Builder|PromotionQuantity whereQuantity($value)
 * @method static Builder|PromotionQuantity whereUpdatedAt($value)
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
