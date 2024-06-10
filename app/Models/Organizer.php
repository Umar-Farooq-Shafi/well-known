<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eventic_organizer';

    protected $fillable = [
        'user_id',
        'country_id',
        'name',
        'slug',
        'description',
        'website',
        'email',
        'phone',
        'facebook',
        'twitter',
        'instagram',
        'googleplus',
        'linkedin',
        'youtubeurl',
        'logo_name',
        'logo_size',
        'logo_mime_type',
        'logo_original_name',
        'logo_dimensions',
        'cover_name',
        'cover_size',
        'cover_mime_type',
        'cover_original_name',
        'cover_dimensions',
        'views',
        'showvenuesmap',
        'showfollowers',
        'showreviews',
        'show_event_date_stats_on_scanner_app',
        'allow_tap_to_check_in_on_scanner_app'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    /**
     * @return HasMany
     */
    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }
}
