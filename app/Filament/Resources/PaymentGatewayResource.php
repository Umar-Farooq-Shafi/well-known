<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentGatewayResource\Pages;
use App\Filament\Resources\PaymentGatewayResource\Widgets;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static ?string $navigationIcon = 'fas-money-bill';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Payments and Fees';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Select::make('gateway_name')
                    ->options([
                        'offline' => 'Cash / Check / Offline',
                        'paypal_express_checkout' => 'Paypal Express Checkout',
                        'stripe_checkout' => 'Stripe Checkout',
                        'eseva' => 'ESeva'
                    ])
                    ->live()
                    ->required(),

                Forms\Components\FileUpload::make('gateway_logo_name')
                    ->label('Image')
                    ->disk('public')
                    ->directory('payment/gateways')
                    ->columnSpanFull()
                    ->formatStateUsing(fn($state) => $state ? ['payment/gateways/' . $state] : null)
                    ->visibility('public')
                    ->required(),

                Forms\Components\Radio::make('enabled')
                    ->label('Status')
                    ->boolean()
                    ->required(),

                Forms\Components\TextInput::make('number')
                    ->label('Order of appearance')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('config.sandbox')
                    ->label('Sandbox')
                    ->boolean()
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.username')
                    ->label('Username')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.password')
                    ->label('Username')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.signature')
                    ->label('Username')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'paypal_express_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.publishable_key')
                    ->label('Stripe publishable key')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'stripe_checkout')
                    ->required(),

                Forms\Components\TextInput::make('config.secret_key')
                    ->label('Stripe secret key')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'stripe_checkout')
                    ->required(),

                Forms\Components\Radio::make('config.production')
                    ->label('Is Production')
                    ->boolean()
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'eseva')
                    ->required(),

                Forms\Components\TextInput::make('config.merchant_code')
                    ->label('Merchant code')
                    ->visible(fn (Forms\Get $get) => $get('gateway_name') === 'eseva')
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

                Tables\Columns\TextColumn::make('enabled')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Hidden' : 'Visible')
                    ->color(fn($state) => $state ? 'danger' : 'primary')
                    ->icon(fn($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge(),

                Tables\Columns\CheckboxColumn::make('membership_types')
                    ->label('Free')
                    ->beforeStateUpdated(function ($state, $record) {
                        $membershipType = $record->membership_type ?? [];

                        if ($state) {
                            $membershipType[] = 'free';
                        } elseif (array_key_exists('free', $membershipType)) {
                            unset($membershipType['free']);
                        }

                        $record->update(['membership_type' => array_unique($membershipType)]);
                    })
                    ->updateStateUsing(fn($record) => $record)
                    ->state(fn($record) => in_array('free', $record->membership_type ?? [])),

                Tables\Columns\CheckboxColumn::make('id')
                    ->label('Commission')
                    ->beforeStateUpdated(function ($state, $record) {
                        $membershipType = $record->membership_type ?? [];

                        if ($state) {
                            $membershipType[] = 'comission';
                        } elseif (array_key_exists('comission', $membershipType)) {
                            unset($membershipType['comission']);
                        }

                        $record->update(['membership_type' => array_unique($membershipType)]);
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
            Widgets\SettingPaymentWidget::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
