<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 *
 *
 * @property int $id
 * @property int|null $organizer_id
 * @property int|null $scanner_id
 * @property int|null $pointofsale_id
 * @property int|null $isorganizeronhomepageslider_id
 * @property int|null $country_id
 * @property string $username
 * @property string $username_canonical
 * @property string $email
 * @property string $email_canonical
 * @property int $enabled
 * @property string|null $salt
 * @property mixed $password
 * @property string|null $last_login
 * @property string|null $confirmation_token
 * @property string|null $password_requested_at
 * @property string $roles (DC2Type:array)
 * @property string|null $gender
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string $slug
 * @property string|null $street
 * @property string|null $street2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postalcode
 * @property string|null $phone
 * @property string|null $birthdate
 * @property string|null $avatar_name
 * @property int|null $avatar_size
 * @property string|null $avatar_mime_type
 * @property string|null $avatar_original_name
 * @property string|null $avatar_dimensions (DC2Type:simple_array)
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $facebook_id
 * @property string|null $facebook_access_token
 * @property string|null $google_id
 * @property string|null $google_access_token
 * @property string|null $api_key
 * @property string|null $facebook_profile_picture
 * @property string|null $membership_type
 * @property string|null $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartElement> $cartElements
 * @property-read int|null $cart_elements_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Organizer|null $organizer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsorganizeronhomepagesliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMembershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordRequestedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePointofsaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePostalcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereScannerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStreet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsernameCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Scanner> $scanners
 * @property-read int|null $scanners_count
 * @property-read \App\Models\PointsOfSale|null $pointOfSale
 * @property-read \App\Models\Scanner|null $scanner
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'eventic_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organizer_id',
        'scanner_id',
        'pointofsale_id',
        'isorganizeronhomepageslider_id',
        'country_id',
        'username',
        'username_canonical',
        'email',
        'email_canonical',
        'enabled',
        'salt',
        'password',
        'last_login',
        'confirmation_token',
        'password_requested_at',
        'roles',
        'gender',
        'firstname',
        'lastname',
        'slug',
        'street',
        'street2',
        'city',
        'state',
        'postalcode',
        'phone',
        'birthdate',
        'avatar_name',
        'avatar_size',
        'avatar_mime_type',
        'avatar_original_name',
        'avatar_dimensions',
        'facebook_id',
        'facebook_access_token',
        'google_id',
        'google_access_token',
        'api_key',
        'facebook_profile_picture',
        'membership_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'confirmation_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . " " . $this->lastname;
    }

    /***
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canImpersonate(): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_name) {
            return Storage::disk('public')->url("users/avatars/{$this->avatar_name}");
        }

        $name = str(Filament::getNameForDefaultAvatar($this))
            ->trim()
            ->explode(' ')
            ->map(fn(string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        return 'https://ui-avatars.com/api/?name=' . $name . '&color=FFFFFF&background=09090b';
    }

    /**
     * @return string
     */
    public function getFilamentName(): string
    {
        return "{$this->username}";
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        $roles = unserialize($this->roles);

        return in_array($role, $roles);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        $userRoles = unserialize($this->roles);

        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCrossRoleName(): string
    {
        if ($this->hasRole("ROLE_ATTENDEE")) {
            return $this->getFilamentName();
        }

        if ($this->hasRole("ROLE_ORGANIZER") && $this->organizer) {
            return $this->organizer->name;
        }

        if ($this->hasRole("ROLE_POINTOFSALE") && $this->pointOfSale) {
            return $this->pointOfSale->name;
        }

        if ($this->hasRole("ROLE_SCANNER") && $this->scanner) {
            return $this->scanner->name;
        }

        return "N/A";
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany
     */
    public function cartElements(): HasMany
    {
        return $this->hasMany(CartElement::class);
    }

    /**
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * @return HasOne
     */
    public function scanner(): HasOne
    {
        return $this->hasOne(Scanner::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointsOfSale::class, 'pointofsale_id');
    }

}
