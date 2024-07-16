<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages\Currency;

use App\Filament\Resources\PaymentGatewayResource;
use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class EditCurrencyPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithFormActions;
    use InteractsWithRecord;

    protected static string $resource = PaymentGatewayResource::class;

    protected static string $view = 'filament.resources.payment-gateway-resource.pages.edit-currency-page';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->form->fill([
            'ccy' => $this->record->ccy,
            'symbol' => $this->record->symbol,
        ]);
    }

    protected function resolveRecord(int | string $key): Model
    {
        $record = Currency::find($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel($this->getModel(), [$key]);
        }

        return $record;
    }

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

        $this->record->update([
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
