<?php

namespace App\Filament\Resources\AmenityResource\Pages;

use App\Filament\Resources\AmenityResource;
use App\Models\Amenity;
use App\Models\AmenityTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateAmenity extends CreateRecord
{
    protected static string $resource = AmenityResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $amenity = Amenity::create([
            'hidden' => 0,
            'icon' => $data['icon']
        ]);

        foreach ($data as $name => $value) {
            if ($value && $name !== 'icon') {
                AmenityTranslation::create([
                    'translatable_id' => $amenity->id,
                    'name' => $value,
                    'slug' => Str::slug($value),
                    'locale' => match ($name) {
                        'name-es' => 'es',
                        'name-fr' => 'fr',
                        'name-ar' => 'ar',
                        default => 'en'
                    }
                ]);
            }
        }

        return $amenity;
    }

}
