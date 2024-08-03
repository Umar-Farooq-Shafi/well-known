<?php

namespace App\Filament\Resources;

use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderConfirmation;
use App\Models\EventTranslation;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function canViewAny(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_SCANNER']);
    }

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

                Tables\Columns\TextColumn::make('orderElements.eventDateTicket.eventDate.event.organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElements.eventDateTicket.eventDate.event.name')
                    ->state(function ($record) {
                        $state = '';

                        foreach ($record->orderElements as $orderElement) {
                            $state .= $orderElement?->eventDateTicket?->eventDate?->event?->name ?? '';
                        }

                        return $state;
                    })
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
                            -2 => 'Failed',
                            default => $state
                        };
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            1 => 'success',
                            0 => 'gray',
                            -1 => 'warning',
                            -2 => 'danger',
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
                    ),

                Tables\Filters\SelectFilter::make('event')
                    ->searchable()
                    ->options(function () {
                        return EventTranslation::query()
                            ->when(
                                auth()->user()->hasRole('ROLE_ORGANIZER'),
                                fn(Builder $query) => $query->whereHas(
                                    'event',
                                    fn(Builder $query) => $query->where('organizer_id', auth()->user()->organizer_id)
                                )
                            )
                            ->pluck('name', 'translatable_id');
                    })
                    ->query(
                        fn(Builder $query, array $data) => $query->when(
                            $data['value'],
                            fn(Builder $query, $value) => $query->whereHas(
                                'orderElements.eventDateTicket.eventDate',
                                fn(Builder $query) => $query->where('event_id', $value)
                            )
                        )
                    ),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(OrderExporter::class)
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('print-tickets')
                        ->visible(fn($record) => $record->status === 1)
                        ->url(fn($record) => route('print-ticket', ['record' => $record->id]), true)
                        ->icon('heroicon-o-printer'),

                    Tables\Actions\ViewAction::make()
                        ->label('Detail'),

                    Tables\Actions\Action::make('resend-confirmation-email')
                        ->visible(fn($record) => $record->status === 1)
                        ->requiresConfirmation()
                        ->modalDescription('If you need to send the confirmation email to a different email address, you can change it before submitting')
                        ->form([
                            Forms\Components\TextInput::make('email')
                                ->formatStateUsing(fn($record) => $record->user?->email)
                        ])
                        ->action(function ($data, $record) {
                            Mail::to($data['email'])->send(new OrderConfirmation($record));

                            Notification::make()
                                ->title("The confirmation email has been resent to " . $data['email'])
                                ->success()
                                ->send();
                        })
                        ->icon('heroicon-o-envelope'),

                    Tables\Actions\Action::make('payment-details')
                        ->visible(fn($record) => $record->status === 1)
                        ->modalHeading(fn ($record) => "Order payment details - " . $record->reference)
                        ->modalContent(
                            fn ($record) => view('filament.resources.order-resource.payment-detail', [
                                'payment' => $record->payment,
                            ])
                        )
                        ->modalSubmitAction('')
                        ->modalCancelActionLabel('Close')
                        ->icon('heroicon-o-clipboard-document-list'),

                    Tables\Actions\Action::make('cancel')
                        ->visible(fn($record) => $record->status !== -2)
                        ->action(fn($record) => $record->update(['status' => -1]))
                        ->icon('heroicon-o-x-mark'),

                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
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
            )
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
