<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudienceTranslation extends Model
{
    use HasFactory;

    protected $table = 'eventic_audience_translation';

    public $timestamps = false;

    protected $fillable = [
        'translatable_id',
        'name',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class, 'translatable_id');
    }
}
