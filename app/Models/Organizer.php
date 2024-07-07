<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $country_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $website
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $instagram
 * @property string|null $googleplus
 * @property string|null $linkedin
 * @property string|null $youtubeurl
 * @property string|null $logo_name
 * @property int|null $logo_size
 * @property string|null $logo_mime_type
 * @property string|null $logo_original_name
 * @property string|null $logo_dimensions (DC2Type:simple_array)
 * @property string|null $cover_name
 * @property int|null $cover_size
 * @property string|null $cover_mime_type
 * @property string|null $cover_original_name
 * @property string|null $cover_dimensions (DC2Type:simple_array)
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $views
 * @property int $showvenuesmap
 * @property int $showfollowers
 * @property int $showreviews
 * @property int|null $show_event_date_stats_on_scanner_app
 * @property int|null $allow_tap_to_check_in_on_scanner_app
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PayoutRequest> $payoutRequests
 * @property-read int|null $payout_requests_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues
 * @property-read int|null $venues_count
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereAllowTapToCheckInOnScannerApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCoverDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCoverMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCoverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCoverOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCoverSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereGoogleplus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLogoDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLogoMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLogoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLogoOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereLogoSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereShowEventDateStatsOnScannerApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereShowfollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereShowreviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereShowvenuesmap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer whereYoutubeurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organizer withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PointsOfSale> $pointOfSales
 * @property-read int|null $point_of_sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Scanner> $scanners
 * @property-read int|null $scanners_count
 * @mixin \Eloquent
 */
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

    /**
     * @return HasMany
     */
    public function scanners(): HasMany
    {
        return $this->hasMany(Scanner::class);
    }

    /**
     * @return HasMany
     */
    public function pointOfSales(): HasMany
    {
        return $this->hasMany(PointsOfSale::class);
    }

}
