<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;

class CheckoutPage extends Page
{
    protected static ?string $navigationIcon = 'fas-list-check';

    protected static string $view = 'filament.pages.checkout-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Checkout';

    protected static ?int $navigationSort = 3;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'checkout_timeleft' => Setting::query()->where('key', 'checkout_timeleft')->first()?->value,
            'show_tickets_left_on_cart_modal' => Setting::query()->where('key', 'show_tickets_left_on_cart_modal')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('checkout_timeleft')
                    ->label('Timeleft')
                    ->helperText('Number of seconds before the reserved tickets are released if the order is still awaiting payment')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('show_tickets_left_on_cart_modal')
                    ->label('Show tickets left count on cart modal')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required()
            ]);
    }

    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'checkout_timeleft')
            ->update([
                'value' => $this->data['checkout_timeleft']
            ]);

        Setting::query()->where('key', 'show_tickets_left_on_cart_modal')
            ->update([
                'value' => $this->data['show_tickets_left_on_cart_modal']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
