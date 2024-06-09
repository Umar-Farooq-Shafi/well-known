<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
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

    /***
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getFilamentName(): string
    {
        return "{$this->username}";
    }

}
