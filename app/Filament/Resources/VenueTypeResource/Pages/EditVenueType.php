<?php

namespace App\Filament\Resources\VenueTypeResource\Pages;

use App\Filament\Resources\VenueTypeResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditVenueType extends EditRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = VenueTypeResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->venueTypeTranslations as $translation) {
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
        foreach ($record->venueTypeTranslations as $translation) {
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
