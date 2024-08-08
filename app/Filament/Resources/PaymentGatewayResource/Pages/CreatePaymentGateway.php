<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use App\Traits\PaymentGatewayTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePaymentGateway extends CreateRecord
{
    use PaymentGatewayTrait;

    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->handlePaymentForm($data);
    }
}
