<?php

namespace App\Filament\Resources\PaymentGatewayResource\Widgets;

use App\Models\Currency;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class SettingPaymentWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.payment-gateway-resource.widgets.setting-payment-widget';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'currency_ccy' => Setting::query()->where('key', 'currency_ccy')->first()?->value,
            'currency_position' => Setting::query()->where('key', 'currency_position')->first()?->value,
            'ticket_fee_add' => Setting::query()->where('key', 'ticket_fee_add')->first()?->value,
            'ticket_fee_online' => Setting::query()->where('key', 'ticket_fee_online')->first()?->value,
            'online_ticket_price_percentage_cut' => Setting::query()->where('key', 'online_ticket_price_percentage_cut')->first()?->value,
            'organizer_payout_paypal_enabled' => Setting::query()->where('key', 'organizer_payout_paypal_enabled')->first()?->value,
            'organizer_payout_stripe_enabled' => Setting::query()->where('key', 'organizer_payout_stripe_enabled')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('currency_ccy')
                    ->label('Currency')
                    ->options(Currency::query()->pluck('ccy', 'ccy'))
                    ->searchable()
                    ->live()
                    ->required(),

                Forms\Components\Radio::make('currency_position')
                    ->label('Currency symbol position')
                    ->inline()
                    ->options([
                        'left' => 'Left',
                        'right' => 'Right',
                    ])
                    ->required(),

                Forms\Components\Radio::make('ticket_fee_add')
                    ->label('Ticket fee (Online)- Membership Organiser will set their own fixed fee')
                    ->inlineLabel()
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed'
                    ])
                    ->required(),

                Forms\Components\TextInput::make('ticket_fee_online')
                    ->label('by')
                    ->helperText('This fee will be added to the ticket sale price which are bought online, put 0 to disable additional fees for tickets which are bought online, does not apply for free tickets, will be applied to future orders')
                    ->prefix(fn (Forms\Get $get) => $get('currency_ccy'))
                    ->integer()
                    ->required(),

                Forms\Components\TextInput::make('online_ticket_price_percentage_cut')
                    ->label('Ticket price percentage cut (Online)')
                    ->helperText('This percentage will be deducted from each ticket sold online, upon organizer payout request, this percentage will be taken from each ticket sold online, will be applied to future orders')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('organizer_payout_paypal_enabled')
                    ->label('Allow Paypal as a payout method for the organizers to receive their revenue')
                    ->inlineLabel()
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('organizer_payout_stripe_enabled')
                    ->label('Allow Stripe as a payout method for the organizers to receive their revenue')
                    ->inlineLabel()
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $this->validate();

        Setting::query()->where('key', 'currency_ccy')->update([
            'value' => $this->data['currency_ccy'],
        ]);

        Setting::query()->where('key', 'currency_position')->update([
            'value' => $this->data['currency_position'],
        ]);

        Setting::query()->where('key', 'ticket_fee_add')->update([
            'value' => $this->data['ticket_fee_add'],
        ]);

        Setting::query()->where('key', 'ticket_fee_online')->update([
            'value' => $this->data['ticket_fee_online'],
        ]);

        Setting::query()->where('key', 'online_ticket_price_percentage_cut')->update([
            'value' => $this->data['online_ticket_price_percentage_cut'],
        ]);

        Setting::query()->where('key', 'organizer_payout_paypal_enabled')->update([
            'value' => $this->data['organizer_payout_paypal_enabled'],
        ]);

        Setting::query()->where('key', 'organizer_payout_stripe_enabled')->update([
            'value' => $this->data['organizer_payout_stripe_enabled'],
        ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
