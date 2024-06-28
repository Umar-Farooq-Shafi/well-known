<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use App\Models\PageTranslation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = Page::create([]);

        PageTranslation::create([
            'translatable_id' => $record->id,
            'title' => data_get($data, 'title-en'),
            'slug' => Str::slug(data_get($data, 'title-en')),
            'content' => data_get($data, 'content-en'),
            'locale' => 'en',
        ]);

        if (data_get($data, 'title-fr')) {
            PageTranslation::create([
                'translatable_id' => $record->id,
                'title' => data_get($data, 'title-fr'),
                'slug' => Str::slug(data_get($data, 'title-fr')),
                'content' => data_get($data, 'content-fr'),
                'locale' => 'fr',
            ]);
        }

        if (data_get($data, 'title-es')) {
            PageTranslation::create([
                'translatable_id' => $record->id,
                'title' => data_get($data, 'title-es'),
                'slug' => Str::slug(data_get($data, 'title-es')),
                'content' => data_get($data, 'content-es'),
                'locale' => 'es',
            ]);
        }

        if (data_get($data, 'title-ar')) {
            PageTranslation::create([
                'translatable_id' => $record->id,
                'title' => data_get($data, 'title-ar'),
                'slug' => Str::slug(data_get($data, 'title-ar')),
                'content' => data_get($data, 'content-ar'),
                'locale' => 'fr',
            ]);
        }

        return $record;
    }
}
