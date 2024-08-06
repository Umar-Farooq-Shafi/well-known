<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use App\Filament\Resources\PaymentGatewayResource\Widgets;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentGateways extends ListRecords
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\SettingPaymentWidget::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('manage-currency')
                ->label('MANAGE CURRENCIES')
                ->icon('fas-coins')
                ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                ->url(PaymentGatewayResource\Pages\Currency\CurrencyListPage::getUrl()),

            Actions\CreateAction::make()
                ->icon('fas-plus')
        ];
    }
}
