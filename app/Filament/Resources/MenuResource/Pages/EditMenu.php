<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->menuTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
                $data['header-ar'] = $translation->header;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
                $data['header-en'] = $translation->header;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
                $data['header-fr'] = $translation->header;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
                $data['header-es'] = $translation->header;
            }
        }

        return $data;
    }

}
