<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages\Currency;

use App\Filament\Resources\PaymentGatewayResource;
use App\Models\Currency;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;

class CurrencyListPage extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = PaymentGatewayResource::class;

    protected static string $view = 'filament.resources.payment-gateway-resource.pages.currency-list-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(Currency::query())
            ->columns([
                Tables\Columns\TextColumn::make('ccy')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => PaymentGatewayResource\Pages\Currency\EditCurrencyPage::getUrl(['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add A New Currency')
                ->icon('fas-plus')
                ->url(PaymentGatewayResource\Pages\Currency\CreateCurrencyPage::getUrl())
        ];
    }

}
