<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $followings
 * @property-read int|null $followings_count
 * @property-read mixed $image
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $paymentGateways
 * @property-read int|null $payment_gateways_count
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

    public function getImageAttribute()
    {
        if ($this->logo_name) {
            return Storage::url('organizers/' . $this->logo_name);
        }

        return "data:image/png;base64,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";
    }

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

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'eventic_organizer_category',
            'Organizer_id',
            'Category_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'eventic_following',
            'Organizer_id',
            'User_id'
        );
    }

    /**
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany
     */
    public function paymentGateways(): HasMany
    {
        return $this->hasMany(PaymentGateway::class);
    }

}
