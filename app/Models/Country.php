<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_country';

    protected $fillable = ['code', 'hidden'];

    /**
     * @return HasMany
     */
    public function countryTranslations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class, 'translatable_id');
    }
}
