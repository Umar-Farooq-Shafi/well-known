<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = CouponResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;
        }

        return $data;
    }
}
