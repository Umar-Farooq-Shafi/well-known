<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use App\Traits\PaymentGatewayTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPaymentGateway extends EditRecord
{
    use PaymentGatewayTrait;

    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->handlePaymentForm($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
