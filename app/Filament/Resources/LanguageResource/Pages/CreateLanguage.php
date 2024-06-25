<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use App\Models\LanguageTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $language = Language::create([
            'hidden' => 0,
            'code' => $data['code']
        ]);

        foreach (Arr::except($data, ['code']) as $name => $value) {
            if ($value !== null) {
                LanguageTranslation::create([
                    'translatable_id' => $language->id,
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

        return $language;
    }
}
