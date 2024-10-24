<?php

namespace App\Filament\Resources\PromotionResource\Pages;

use App\Filament\Resources\PromotionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditPromotion extends EditRecord
{
    protected static string $resource = PromotionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['start_date'] = Carbon::parse($data['start_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

            $data['end_date'] = Carbon::parse($data['end_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $timezone = data_get($data, 'timezone', 'UTC');

        if (isset($data['start_date'])) {
            $data['start_date'] = Carbon::parse($data['start_date'])
                ->setTimezone($timezone)
                ->format('Y-m-d H:i:s');
        }

        if (isset($data['expire_date'])) {
            $data['expire_date'] = Carbon::parse($data['expire_date'])
                ->setTimezone($timezone)
                ->format('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    $record->promotionQuantities()->delete();
                }),
        ];
    }
}
