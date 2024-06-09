<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLayoutSetting extends Model
{
    use HasFactory;

    protected $table = 'eventic_app_layout_setting';

    public const CREATED_AT = null;

    protected $fillable = [
        'logo_name',
        'logo_size',
        'logo_mime_type',
        'logo_original_name',
        'logo_dimensions',
        'favicon_name',
        'favicon_size',
        'favicon_mime_type',
        'favicon_original_name',
        'favicon_dimensions',
        'og_image_name',
        'og_image_size',
        'og_image_mime_type',
        'og_image_original_name',
        'og_image_dimensions'
    ];
}
