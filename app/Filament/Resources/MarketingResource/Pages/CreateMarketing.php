<?php

namespace App\Filament\Resources\MarketingResource\Pages;

use App\Filament\Resources\MarketingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketing extends CreateRecord
{
    protected static string $resource = MarketingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;
        }

        return $data;
    }
}
