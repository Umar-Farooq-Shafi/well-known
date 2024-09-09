<?php

namespace App\Filament\Resources\PromotionResource\Pages;

use App\Filament\Resources\PromotionResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePromotion extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = PromotionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;
        }

        return $data;
    }

}
