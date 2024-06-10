<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_event_translation';

    protected $fillable = [
        'translatable_id',
        'name',
        'description',
        'slug',
        'locale'
    ];

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'translatable_id');
    }

}
