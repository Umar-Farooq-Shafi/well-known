<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPaymentGateway extends EditRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['gateway_logo_name'] = last(explode('/', $data['gateway_logo_name']));
        $data['factory_name'] = $data['gateway_name'];
        $data['slug'] = Str::slug($data['name']);

        if (!array_key_exists('config', $data)) {
            $data['config'] = json_encode([]);
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
