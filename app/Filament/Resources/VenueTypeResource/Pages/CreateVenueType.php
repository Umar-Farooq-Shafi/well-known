<?php

namespace App\Filament\Resources\VenueTypeResource\Pages;

use App\Filament\Resources\VenueTypeResource;
use App\Models\Venue;
use App\Models\VenueTranslation;
use App\Models\VenueType;
use App\Models\VenueTypeTranslation;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateVenueType extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = VenueTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $venue = VenueType::create([
            'hidden' => 0
        ]);

        foreach ($data as $name => $value) {
            VenueTypeTranslation::create([
                'translatable_id' => $venue->id,
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

        return $venue;
    }

}
