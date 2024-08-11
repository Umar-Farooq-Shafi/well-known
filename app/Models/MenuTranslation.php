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
 * @property string $name
 * @property string|null $header
 * @property string $slug
 * @property string $locale
 * @property-read \App\Models\Menu|null $menu
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation whereTranslatableId($value)
 * @mixin \Eloquent
 */
class MenuTranslation extends Model
{
    use HasFactory;

    protected $table = 'eventic_menu_translation';

    public $timestamps = false;

    protected $fillable = [
        'translatable_id',
        'name',
        'header',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'translatable_id');
    }

}
