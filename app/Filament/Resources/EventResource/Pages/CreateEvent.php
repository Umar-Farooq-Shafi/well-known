<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\EventTranslation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reference'] = Str::uuid()->toString();
        $data['views'] = 0;
        $data['published'] = true;
        $data['is_featured'] = false;
        $data['organizer_id'] = auth()->user()->organizer_id;

        return $data;
    }

    public function afterCreate(): void
    {
        $langs = [
            'en',
            'es',
            'fr',
            'ar'
        ];

        foreach ($langs as $lang) {
            if ($name = data_get($this->data, "name-$lang")) {
                EventTranslation::create([
                    'translatable_id' => $this->record->id,
                    'name' => $name,
                    'description' => data_get($this->data, "content-$lang"),
                    'slug' => Str::slug($name),
                    'locale' => $lang,
                ]);
            }
        }
    }

}
