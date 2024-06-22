<?php

namespace App\Filament\Resources\AmenityResource\Pages;

use App\Filament\Resources\AmenityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAmenity extends EditRecord
{
    protected static string $resource = AmenityResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->amenityTranslations as $translation) {
            if ($translation->locale === 'ar')
                $data['name-ar'] = $translation->name;

            if ($translation->locale === 'en')
                $data['name-en'] = $translation->name;

            if ($translation->locale === 'fr')
                $data['name-fr'] = $translation->name;

            if ($translation->locale === 'es')
                $data['name-es'] = $translation->name;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'icon' => $data['icon']
        ]);

        foreach ($record->amenityTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->save();
            }
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
