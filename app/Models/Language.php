<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_language';

    protected $fillable = [
        'code',
        'hidden'
    ];

    /**
     * @return HasMany
     */
    public function languageTranslations(): HasMany
    {
        return $this->hasMany(LanguageTranslation::class, 'translatable_id');
    }
}
