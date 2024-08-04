<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OrdersTableOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_POINTOFSALE', 'ROLE_SCANNER', 'ROLE_ORGANIZER']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElements.eventDateTicket.eventDate.event.organizer.name'),

                Tables\Columns\TextColumn::make('orderElements.eventDateTicket.eventDate.event.name')
                    ->state(function ($record) {
                        $state = '';

                        foreach ($record->orderElements as $orderElement) {
                            $state .= $orderElement?->eventDateTicket?->eventDate?->event?->name ?? '';
                        }

                        return $state;
                    })
                    ->label('Event'),

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
            ]);
    }
}
