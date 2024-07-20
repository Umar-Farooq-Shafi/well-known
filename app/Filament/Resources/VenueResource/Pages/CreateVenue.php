<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use App\Models\VenueTranslation;
use App\Traits\DuplicateNameValidationTrait;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class CreateVenue extends CreateRecord
{
    use DuplicateNameValidationTrait;

    protected static string $resource = VenueResource::class;

    /**
     * @throws Halt
     */
    public function beforeCreate(): void
    {
        $this->checkName();
    }

    public function afterCreate(): void
    {
        $data = $this->data;

        VenueTranslation::create([
            'translatable_id' => $this->record->id,
            'name' => data_get($data, 'name-en'),
            'slug' => Str::slug(data_get($data, 'name-en')),
            'description' => data_get($data, 'content-en'),
            'locale' => 'en',
        ]);

        if ($nameFR = data_get($data, 'name-fr')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => $nameFR,
                'slug' => Str::slug($nameFR),
                'description' => data_get($data, 'content-fr'),
                'locale' => 'fr',
            ]);
        }

        if ($nameES = data_get($data, 'name-es')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => $nameES,
                'slug' => Str::slug($nameES),
                'description' => data_get($data, 'content-es'),
                'locale' => 'es',
            ]);
        }

        if ($nameAR = data_get($data, 'name-ar')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => $nameAR,
                'slug' => Str::slug($nameAR),
                'description' => data_get($data, 'content-ar'),
                'locale' => 'fr',
            ]);
        }
    }
}
