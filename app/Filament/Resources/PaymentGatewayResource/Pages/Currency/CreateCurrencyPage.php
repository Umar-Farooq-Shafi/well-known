<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages\Currency;

use App\Filament\Resources\PaymentGatewayResource;
use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Validation\ValidationException;

class CreateCurrencyPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = PaymentGatewayResource::class;

    protected static string $view = 'filament.resources.payment-gateway-resource.pages.create-currency-page';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('ccy')
                    ->helperText('Please refer to thefollowing list and use the Code column: https://en.wikipedia.org/wiki/ISO_4217')
                    ->required()
                    ->label('CCY'),

                Forms\Components\TextInput::make('symbol')
                    ->label('Currency symbol')
                    ->required()
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        Currency::create([
            'ccy' => $this->data['ccy'],
            'symbol' => $this->data['symbol'],
        ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
