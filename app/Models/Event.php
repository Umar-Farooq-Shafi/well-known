<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * 
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $country_id
 * @property int|null $organizer_id
 * @property int|null $isonhomepageslider_id
 * @property string $reference
 * @property int $views
 * @property string|null $youtubeurl
 * @property string|null $externallink
 * @property string|null $phonenumber
 * @property string|null $email
 * @property string|null $twitter
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $googleplus
 * @property string|null $linkedin
 * @property string|null $artists
 * @property string|null $tags
 * @property string|null $year
 * @property string|null $image_name
 * @property int|null $image_size
 * @property string|null $image_mime_type
 * @property string|null $image_original_name
 * @property string|null $image_dimensions (DC2Type:simple_array)
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int $published
 * @property int $enablereviews
 * @property int $showattendees
 * @property int $is_featured
 * @property string $eventtimezone
 * @property-read Category|null $category
 * @property-read Collection<int, EventDate> $eventDates
 * @property-read int|null $event_dates_count
 * @property-read Collection<int, EventTranslation> $eventTranslations
 * @property-read int|null $event_translations_count
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read Collection<int, Review> $reviews
 * @property-read int|null $reviews_count
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event onlyTrashed()
 * @method static Builder|Event query()
 * @method static Builder|Event whereArtists($value)
 * @method static Builder|Event whereCategoryId($value)
 * @method static Builder|Event whereCountryId($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDeletedAt($value)
 * @method static Builder|Event whereEmail($value)
 * @method static Builder|Event whereEnablereviews($value)
 * @method static Builder|Event whereEventtimezone($value)
 * @method static Builder|Event whereExternallink($value)
 * @method static Builder|Event whereFacebook($value)
 * @method static Builder|Event whereGoogleplus($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereImageDimensions($value)
 * @method static Builder|Event whereImageMimeType($value)
 * @method static Builder|Event whereImageName($value)
 * @method static Builder|Event whereImageOriginalName($value)
 * @method static Builder|Event whereImageSize($value)
 * @method static Builder|Event whereInstagram($value)
 * @method static Builder|Event whereIsFeatured($value)
 * @method static Builder|Event whereIsonhomepagesliderId($value)
 * @method static Builder|Event whereLinkedin($value)
 * @method static Builder|Event whereOrganizerId($value)
 * @method static Builder|Event wherePhonenumber($value)
 * @method static Builder|Event wherePublished($value)
 * @method static Builder|Event whereReference($value)
 * @method static Builder|Event whereShowattendees($value)
 * @method static Builder|Event whereTags($value)
 * @method static Builder|Event whereTwitter($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereViews($value)
 * @method static Builder|Event whereYear($value)
 * @method static Builder|Event whereYoutubeurl($value)
 * @method static Builder|Event withTrashed()
 * @method static Builder|Event withoutTrashed()
 * @property-read Collection<int, \App\Models\Audience> $audiences
 * @property-read int|null $audiences_count
 * @property-read Collection<int, Language> $languages
 * @property-read int|null $languages_count
 * @property-read Collection<int, EventImage> $eventImages
 * @property-read int|null $event_images_count
 * @property-read Collection<int, Language> $subtitles
 * @property-read int|null $subtitles_count
 * @property int $completed
 * @property-read Country|null $country
 * @property-read Collection<int, \App\Models\User> $favourites
 * @property-read int|null $favourites_count
 * @method static Builder|Event whereCompleted($value)
 * @property-read mixed $name
 * @property-read mixed $slug
 * @property-read Collection<int, Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read Collection<int, Promotion> $promotions
 * @property-read int|null $promotions_count
 * @property-read mixed $description
 * @mixin \Eloquent
 */
class Event extends Model implements Feedable
{
    use HasFactory, SoftDeletes, ImageTrait;

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
        'eventtimezone',
        'completed',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'completed' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            self::saveImage($model, 'events', true);
        });

        self::updating(function ($model) {
            self::saveImage($model, 'events');
        });
    }

    public function getEventtimezoneAttribute()
    {
        return $this->attributes['eventtimezone'] === '' ? null : $this->attributes['eventtimezone'];
    }

    public function getNameAttribute()
    {
        return $this
            ->eventTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    public function getDescriptionAttribute()
    {
        return $this
            ->eventTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->description;
    }

    public function getSlugAttribute()
    {
        return $this
            ->eventTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    public function getFirstOnSaleEventDate()
    {
        foreach ($this->eventDates as $eventuate) {
            if ($eventuate->isOnSale()) {
                return $eventuate;
            }
        }

        return null;
    }

    public function hasAnEventDateOnSale(): bool
    {
        foreach ($this->eventDates as $eventuate) {
            if ($eventuate->isOnSale()) {
                return true;
            }
        }

        return false;
    }

    public function getTotalSalesPercentage(): float|int
    {
        if (count($this->eventDates) == 0) {
            return 0;
        }

        $eventDatesSalesPercentageSum = 0;

        foreach ($this->eventDates as $eventDate) {
            $eventDatesSalesPercentageSum += $eventDate->getTotalSalesPercentage();
        }

        return round($eventDatesSalesPercentageSum / count($this->eventDates));
    }

    public function getTotalOrderElementsQuantitySum($status = 1, $user = "all", $role = "all"): int
    {
        $sum = 0;

        foreach ($this->eventDates as $eventDate) {
            $sum += $eventDate->getOrderElementsQuantitySum($status, $user, $role);
        }

        return $sum;
    }

    public function stringifyStatus(): string
    {
        if (!$this->organizer?->user?->enabled) {
            return "Organizer is disabled";
        }

        if (!$this->published) {
            return "Event is not published";
        }

        if (!$this->hasAnEventDateOnSale()) {
            return "No events on sale";
        }

        return "On sale";
    }

    public function stringifyStatusClass(): string
    {
        if (!$this->organizer?->user?->enabled) {
            return "danger";
        }

        if (!$this->published) {
            return "warning";
        }

        if (!$this->hasAnEventDateOnSale()) {
            return "info";
        }

        return "success";
    }

    public function getTotalCheckInPercentage(): float|int
    {
        if (count($this->eventDates) == 0) {
            return 0;
        }

        $eventDatesCheckInPercentageSum = 0;

        foreach ($this->eventDates as $eventDate) {
            $eventDatesCheckInPercentageSum += $eventDate->getTotalCheckInPercentage();
        }

        return round($eventDatesCheckInPercentageSum / count($this->eventDates));
    }

    public function displaySubtitles(): string
    {
        $subtitles = '';

        foreach ($this->subtitles as $subtitle) {
            $subtitles .= $subtitle->name . ', ';
        }

        return rtrim($subtitles, ', ');
    }

    public function hasTwoOrMoreEventDatesOnSale(): bool
    {
        $count = 0;

        foreach ($this->eventDates as $eventuate) {
            if ($eventuate->isOnSale()) {
                $count++;
            }
        }

        return $count >= 2;
    }

    public
    function hasContactAndSocialMedia(): bool
    {
        return ($this->externallink || $this->phonenumber || $this->twitter
            || $this->instagram || $this->email || $this->facebook || $this->googleplus || $this->linkedin);
    }

    /**
     * @return BelongsTo
     */
    public
    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public
    function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * @return HasMany
     */
    public
    function eventTranslations(): HasMany
    {
        return $this->hasMany(EventTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public
    function eventDates(): HasMany
    {
        return $this->hasMany(EventDate::class);
    }

    /**
     * @return HasMany
     */
    public
    function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return BelongsToMany
     */
    public
    function audiences(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class, 'eventic_event_audience');
    }

    /**
     * @return BelongsToMany
     */
    public
    function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'eventic_event_language');
    }

    /**
     * @return BelongsToMany
     */
    public
    function subtitles(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'eventic_event_subtitle');
    }

    /**
     * @return HasMany
     */
    public
    function eventImages(): HasMany
    {
        return $this->hasMany(EventImage::class);
    }

    /**
     * @return BelongsToMany
     */
    public
    function favourites(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'eventic_favorites',
            'Event_id',
            'User_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public
    function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public
    function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->name)
            ->link(env('APP_URL') . '/event/' . $this->slug)
            ->category($this->category->name)
            ->summary($this->created_at)
            ->authorName(auth()->user()->username)
            ->updated($this->updated_at);
    }

    /**
     * @return BelongsToMany
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'event_coupon');
    }

    /**
     * @return BelongsToMany
     */
    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class);
    }

}
