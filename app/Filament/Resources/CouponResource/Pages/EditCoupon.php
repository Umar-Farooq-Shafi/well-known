<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditCoupon extends EditRecord
{
    protected static string $resource = CouponResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['start_date'] = Carbon::parse($data['start_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

            $data['expire_date'] = Carbon::parse($data['expire_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
