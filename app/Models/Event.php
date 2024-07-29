<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $published
 * @property int $enablereviews
 * @property int $showattendees
 * @property int $is_featured
 * @property string $eventtimezone
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventDate> $eventDates
 * @property-read int|null $event_dates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventTranslation> $eventTranslations
 * @property-read int|null $event_translations_count
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereArtists($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEnablereviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEventtimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereExternallink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereGoogleplus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsonhomepagesliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePhonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereShowattendees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereYoutubeurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audience> $audiences
 * @property-read int|null $audiences_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languages
 * @property-read int|null $languages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventImage> $eventImages
 * @property-read int|null $event_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $subtitles
 * @property-read int|null $subtitles_count
 * @property int $completed
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favourites
 * @property-read int|null $favourites_count
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCompleted($value)
 * @property-read mixed $name
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
        'completed'
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

    public function getNameAttribute()
    {
        return $this->eventTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->name;
    }

    public function getSlugAttribute()
    {
        return $this->eventTranslations()
            ->where('locale', app()->getLocale())
            ->first()?->slug;
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * @return HasMany
     */
    public function eventTranslations(): HasMany
    {
        return $this->hasMany(EventTranslation::class, 'translatable_id');
    }

    /**
     * @return HasMany
     */
    public function eventDates(): HasMany
    {
        return $this->hasMany(EventDate::class);
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return BelongsToMany
     */
    public function audiences(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class, 'eventic_event_audience');
    }

    /**
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'eventic_event_language');
    }

    /**
     * @return BelongsToMany
     */
    public function subtitles(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'eventic_event_subtitle');
    }

    /**
     * @return HasMany
     */
    public function eventImages(): HasMany
    {
        return $this->hasMany(EventImage::class);
    }

    /**
     * @return BelongsToMany
     */
    public function favourites(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'eventic_favorites',
            'Event_id',
            'User_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function toFeedItem(): FeedItem
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
}
