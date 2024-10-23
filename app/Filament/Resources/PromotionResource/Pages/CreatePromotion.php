<?php

namespace App\Filament\Resources\PromotionResource\Pages;

use App\Filament\Resources\PromotionResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreatePromotion extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = PromotionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;

            $data['start_date'] = Carbon::parse($data['start_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

            $data['end_date'] = Carbon::parse($data['end_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');
        }

        return $data;
    }

}
