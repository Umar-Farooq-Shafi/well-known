<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentGatewayResource\Pages;
use App\Filament\Resources\PaymentGatewayResource\Widgets;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static ?string $navigationIcon = 'fas-money-bill';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Payments and Fees';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            return "Payments Gateway Settings";
        }

        return static::$navigationLabel;
    }

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            return auth()->user()->membership_type === 'Membership';
        }

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Select::make('gateway_name')
                    ->options(function ($context, $record) {
                        $options = [
                            'offline' => 'Cash / Check / Offline',
                        ];

                        $exists = [
                            'paypal_express_checkout' => 'Paypal Express Checkout',
                            'stripe_checkout' => 'Stripe Checkout',
                            'eseva' => 'ESeva',
                        ];

                        if ($context !== 'create' && !array_key_exists($record->factory_name, $options)) {
                            $options[$record->factory_name] = $exists[$record->gateway_name];
                        }

                        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
                            foreach ($exists as $key => $value) {
                                if (
                                    !PaymentGateway::whereGatewayName($key)
                                        ->where(
                                            'organizer_id',
                                            auth()->user()->organizer_id,
                                        )
                                        ->exists()
                                ) {
                                    $options[$key] = $value;
                                }
                            }
                        } else {
                            $options = [...$options, ...$exists];
                        }

                        return $options;
                    })
                    ->live()
                    ->required(),

                Forms\Components\FileUpload::make('gateway_logo_name')
                    ->label('Image')
                    ->disk('public')
                    ->directory('payment/gateways')
                    ->visible(function (Forms\Get $get) {
                        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
                            return $get('gateway_name') === 'offline';
                        }

                        return true;
                    })
                    ->columnSpanFull()
                    ->formatStateUsing(fn($state) => $state ? ['payment/gateways/' . $state] : null)
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                    ->required(),

                Forms\Components\Radio::make('enabled')
                    ->label('Enabled/Disabled')
                    ->boolean()
                    ->inlineLabel()
                    ->required(),

                Forms\Components\TextInput::make('number')
                    ->label('Order of appearance')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('config.sandbox')
                    ->label('Sandbox')
                    ->boolean()
                    ->live()
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.username')
                    ->label('App ID')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->hidden(fn (Forms\Get $get) => $get('config.sandbox'))
                    ->required(),

                Forms\Components\TextInput::make('config.password')
                    ->label('Secret key')
                    ->password()
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.signature')
                    ->label('Client ID')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.publishable_key')
                    ->label('Stripe publishable key')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'stripe_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.secret_key')
                    ->label('Stripe secret key')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'stripe_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.secret_key')
                    ->label('ESEVA secret key')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'eseva')
                    ->required(),

                Forms\Components\Radio::make('config.production')
                    ->label('Is Production')
                    ->boolean()
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'eseva')
                    ->required(),

                Forms\Components\TextInput::make('config.merchant_code')
                    ->label('Merchant code')
                    ->visible(fn(Forms\Get $get) => $get('gateway_name') === 'eseva')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('gateway_logo_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(
                        fn($record) => $record->gateway_logo_name ? ['payment/gateways/' . $record->gateway_logo_name] : null,
                    )
                    ->disk('public'),

                Tables\Columns\TextColumn::make('number')
                    ->label('Order of appearance')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('enabled')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Enabled' : 'Disabled')
                    ->color(fn($state) => $state ? 'primary' : 'danger')
                    ->icon(fn($state) => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->badge(),

                Tables\Columns\CheckboxColumn::make('membership_types')
                    ->label('Free')
                    ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                    ->beforeStateUpdated(function ($state, $record) {
                        $membershipType = $record->membership_type ?? [];

                        if ($state) {
                            $membershipType[] = 'free';
                        } else {
                            $membershipType = array_filter($membershipType, fn($value) => $value !== 'free');
                        }

                        $record->update(['membership_type' => array_unique($membershipType)]);

                        Notification::make()
                            ->title('Saved')
                            ->success()
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    })
                    ->updateStateUsing(fn($record) => $record)
                    ->state(fn($record) => in_array('free', $record->membership_type ?? [])),

                Tables\Columns\CheckboxColumn::make('id')
                    ->label('Commission')
                    ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                    ->beforeStateUpdated(function ($state, $record) {
                        $membershipType = $record->membership_type ?? [];

                        if ($state) {
                            $membershipType[] = 'comission';
                        } else {
                            $membershipType = array_filter($membershipType, fn($value) => $value !== 'comission');
                        }

                        $record->update(['membership_type' => array_unique($membershipType)]);

                        Notification::make()
                            ->title('Saved')
                            ->success()
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    })
                    ->updateStateUsing(fn($record) => $record)
                    ->state(fn($record) => in_array('comission', $record->membership_type ?? [])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }


    public static function getWidgets(): array
    {
        return [
            Widgets\SettingPaymentWidget::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return parent::getEloquentQuery()
            ->when(
                str_contains($role, 'ORGANIZER'),
                fn($query) => $query->where('organizer_id', auth()->user()->organizer_id),
            )
            ->when(
                str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR'),
                fn(Builder $query) => $query->whereNull('organizer_id'),
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
            'currency' => Pages\Currency\CurrencyListPage::route('/currencies'),
            'currency-create' => Pages\Currency\CreateCurrencyPage::route('/currency/create'),
            'currency-edit' => Pages\Currency\EditCurrencyPage::route('/currency/{record}/edit'),
        ];
    }
}
