<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->eventTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
                $data['content-ar'] = $translation->description;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
                $data['content-en'] = $translation->description;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
                $data['content-fr'] = $translation->description;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
                $data['content-es'] = $translation->description;
            }
        }

        return $data;
    }

    public function afterSave(): void
    {
        foreach ($this->record->eventTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $this->data['name-ar'];
                $translation->description = $this->data['content-ar'];
                $translation->slug = Str::slug($this->data['name-ar'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $this->data['name-en'];
                $translation->description = $this->data['content-en'];
                $translation->slug = Str::slug($this->data['name-en'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $this->data['name-fr'];
                $translation->description = $this->data['content-fr'];
                $translation->slug = Str::slug($this->data['name-fr'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $this->data['name-es'];
                $translation->description = $this->data['content-es'];
                $translation->slug = Str::slug($this->data['name-es'] ?? '');
                $translation->save();
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
