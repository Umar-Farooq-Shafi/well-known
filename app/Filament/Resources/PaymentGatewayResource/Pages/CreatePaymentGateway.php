<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePaymentGateway extends CreateRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['gateway_logo_name'] = last(explode('/', $data['gateway_logo_name']));
        $data['factory_name'] = $data['gateway_name'];
        $data['slug'] = Str::slug($data['name']);

        if (!array_key_exists('config', $data)) {
            $data['config'] = json_encode([]);
        }

        return $data;
    }
}
