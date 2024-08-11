<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int|null $menu_id
 * @property string|null $icon
 * @property string|null $link
 * @property int $position
 * @property string|null $custom_link
 * @property-read mixed $label
 * @property-read \App\Models\Menu|null $menu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuElementTranslation> $menuElementTranslations
 * @property-read int|null $menu_element_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement whereCustomLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuElement wherePosition($value)
 * @mixin \Eloquent
 */
class MenuElement extends Model
{
    use HasFactory;

    protected $table = 'eventic_menu_element';

    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'icon',
        'link',
        'position',
        'custom_link'
    ];

    public function getLabelAttribute()
    {
        return $this->menuElementTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->label;
    }

    /**
     * @return BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @return HasMany
     */
    public function menuElementTranslations(): HasMany
    {
        return $this->hasMany(MenuElementTranslation::class, 'translatable_id');
    }

}
