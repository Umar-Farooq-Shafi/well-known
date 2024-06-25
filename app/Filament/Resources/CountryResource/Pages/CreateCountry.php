<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Models\Country;
use App\Models\CountryTranslation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $country = Country::create([
            'hidden' => 0,
            'code' => $data['code']
        ]);

        foreach (Arr::except($data, ['code']) as $name => $value) {
            if ($value !== null) {
                CountryTranslation::create([
                    'translatable_id' => $country->id,
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

        return $country;
    }
}
