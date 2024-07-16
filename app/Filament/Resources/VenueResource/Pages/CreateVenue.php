<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use App\Models\VenueTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class CreateVenue extends CreateRecord
{
    protected static string $resource = VenueResource::class;

    public function afterCreate()
    {
        $data = $this->data;

        VenueTranslation::create([
            'translatable_id' => $this->record->id,
            'name' => data_get($data, 'name-en'),
            'slug' => Str::slug(data_get($data, 'name-en')),
            'description' => data_get($data, 'content-en'),
            'locale' => 'en',
        ]);

        if (data_get($data, 'name-fr')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => data_get($data, 'name-fr'),
                'slug' => Str::slug(data_get($data, 'name-fr')),
                'description' => data_get($data, 'content-fr'),
                'locale' => 'fr',
            ]);
        }

        if (data_get($data, 'name-es')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => data_get($data, 'name-es'),
                'slug' => Str::slug(data_get($data, 'name-es')),
                'description' => data_get($data, 'content-es'),
                'locale' => 'es',
            ]);
        }

        if (data_get($data, 'name-ar')) {
            VenueTranslation::create([
                'translatable_id' => $this->record->id,
                'name' => data_get($data, 'name-ar'),
                'slug' => Str::slug(data_get($data, 'name-ar')),
                'description' => data_get($data, 'content-ar'),
                'locale' => 'fr',
            ]);
        }
    }
}
