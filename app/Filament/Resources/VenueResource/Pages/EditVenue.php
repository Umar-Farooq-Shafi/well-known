<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use App\Models\VenueTranslation;
use App\Traits\DuplicateNameValidationTrait;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditVenue extends EditRecord
{
    use DuplicateNameValidationTrait;
    use FilamentNavigationTrait;

    protected static string $resource = VenueResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->venueTranslations as $translation) {
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;
        } else {
            $data['organizer_id'] = null;
        }

        return$data;
    }

    /**
     * @throws Halt
     */
    public function beforeCreate(): void
    {
        $this->checkName();
    }

    public function afterSave()
    {
        $data = $this->data;

        foreach ($this->record->venueTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->description = $data['content-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->description = $data['content-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->description = $data['content-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->description = $data['content-es'];
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
