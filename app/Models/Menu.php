<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property-read mixed $label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuElement> $menuElements
 * @property-read int|null $menu_elements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuTranslation> $menuTranslations
 * @property-read int|null $menu_translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use HasFactory;

    protected $table = 'eventic_menu';

    public $timestamps = false;

    public function getNameAttribute()
    {
        return $this->menuTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    public function getHeaderAttribute()
    {
        return $this->menuTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->header;
    }

    /**
     * @return HasMany
     */
    public function menuElements(): HasMany
    {
        return $this->hasMany(MenuElement::class);
    }

    /**
     * @return HasMany
     */
    public function menuTranslations(): HasMany
    {
        return $this->hasMany(MenuTranslation::class, 'translatable_id');
    }
}
