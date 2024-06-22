<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $table = 'eventic_page';

    /**
     * @return HasMany
     */
    public function pageTranslations(): HasMany
    {
        return $this->hasMany(PageTranslation::class, 'translatable_id');
    }
}
