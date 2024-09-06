<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marketing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount',
        'duration',
        'expire_date',
        'limit',
        'organizer_id',
    ];

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

}
