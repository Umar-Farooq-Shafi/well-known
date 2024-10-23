<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateCoupon extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = CouponResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;

            $data['start_date'] = Carbon::parse($data['start_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

            $data['expire_date'] = Carbon::parse($data['expire_date'], $data['timezone'])
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');
        }

        return $data;
    }
}
