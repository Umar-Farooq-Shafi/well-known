<?php

namespace App\Traits;

use App\Models\VenueTranslation;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

trait DuplicateNameValidationTrait
{
    /**
     * @throws Halt
     */
    protected function checkName(): void
    {
        $names[] = data_get($this->data, 'name-en');

        if ($nameFR = data_get($this->data, 'name-fr')) {
            $names[] = $nameFR;
        }

        if ($nameES = data_get($this->data, 'name-es')) {
            $names[] = $nameES;
        }

        if ($nameAR = data_get($this->data, 'name-ar')) {
            $names[] = $nameAR;
        }

        if (VenueTranslation::query()->whereIn('name', $names)->exists()) {
            Notification::make()
                ->title('Duplicate Venue')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
