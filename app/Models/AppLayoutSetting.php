<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $logo_name
 * @property int|null $logo_size
 * @property string|null $logo_mime_type
 * @property string|null $logo_original_name
 * @property string|null $logo_dimensions (DC2Type:simple_array)
 * @property string|null $favicon_name
 * @property int|null $favicon_size
 * @property string|null $favicon_mime_type
 * @property string|null $favicon_original_name
 * @property string|null $favicon_dimensions (DC2Type:simple_array)
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $og_image_name
 * @property int|null $og_image_size
 * @property string|null $og_image_mime_type
 * @property string|null $og_image_original_name
 * @property string|null $og_image_dimensions (DC2Type:simple_array)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereFaviconDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereFaviconMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereFaviconName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereFaviconOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereFaviconSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereLogoDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereLogoMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereLogoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereLogoOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereLogoSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereOgImageDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereOgImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereOgImageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereOgImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereOgImageSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppLayoutSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
