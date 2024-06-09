<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_event';

    protected $fillable = [
        'category_id',
        'country_id',
        'organizer_id',
        'isonhomepageslider_id',
        'reference',
        'views',
        'youtubeurl',
        'externallink',
        'phonenumber',
        'email',
        'twitter',
        'instagram',
        'facebook',
        'googleplus',
        'linkedin',
        'artists',
        'tags',
        'year',
        'image_name',
        'image_size',
        'image_mime_type',
        'image_original_name',
        'image_dimensions',
        'published',
        'enablereviews',
        'showattendees',
        'is_featured',
        'eventtimezone'
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
