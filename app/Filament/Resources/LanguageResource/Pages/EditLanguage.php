<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->languageTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
            }
        }

        return $data;
    }

    public function afterSave(): void
    {
        foreach ($this->record->audienceTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $this->data['name-ar'];
                $translation->slug = Str::slug($this->data['name-ar'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $this->data['name-en'];
                $translation->slug = Str::slug($this->data['name-en'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $this->data['name-fr'];
                $translation->slug = Str::slug($this->data['name-fr'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $this->data['name-es'];
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
