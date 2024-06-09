<?php

namespace App\Models;

use App\Filament\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_category';

    protected $fillable = [
        'icon',
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'hidden',
        'featured',
        'featuredorder'
    ];

    /**
     * @return HasMany
     */
    public function categoryTranslations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

}
