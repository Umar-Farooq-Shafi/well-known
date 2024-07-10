<?php

namespace App\Filament\Resources;

use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    /**
     * @param Table $table
     * @return Table
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('paymentGateway.organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('eventDateTickets.name')
                    ->label('Event')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.fullName')
                    ->label('Attendee / POS')
                    ->icon('heroicon-o-user')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            1 => 'Paid',
                            0 => 'Awaiting payment',
                            -1 => 'Cancel',
                            default => $state
                        };
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            1 => 'success',
                            0 => 'warning',
                            -1 => 'danger',
                            default => 'info'
                        };
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('paymentgateway_id')
                    ->label('Payment Gateways')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->relationship(
                        'paymentGateway',
                        'name',
                    ),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Attendees')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->relationship(
                        'user',
                        'username'
                    )
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(OrderExporter::class)
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('print-tickets')
                        ->icon('heroicon-o-printer'),

                    Tables\Actions\ViewAction::make()
                        ->label('Detail'),

                    Tables\Actions\Action::make('resend-confirmation-email')
                        ->icon('heroicon-o-envelope'),

                    Tables\Actions\Action::make('payment-details')
                        ->icon('heroicon-o-clipboard-document-list'),

                    Tables\Actions\Action::make('cancel')
                        ->icon('heroicon-o-x-mark'),

                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return parent::getEloquentQuery()
            ->when(
                str_contains($role, 'ORGANIZER'),
                fn(Builder $query): Builder => $query->where('user_id', auth()->id())
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
