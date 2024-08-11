<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int|null $translatable_id
 * @property string $label
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\MenuElement|null $menuElement
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElementTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class MenuElementTranslation extends Model
{
    use HasFactory;

    protected $table = 'eventic_menu_element_translation';

    public $timestamps = false;

    protected $fillable = [
        'translatable_id',
        'label',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function menuElement(): BelongsTo
    {
        return $this->belongsTo(MenuElement::class, 'translatable_id');
    }

}
