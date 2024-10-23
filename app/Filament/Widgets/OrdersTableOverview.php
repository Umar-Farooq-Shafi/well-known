<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
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
            ->defaultSort('created_at', 'desc')
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
                    ->formatStateUsing(function ($state) {
                        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
                            $country = auth()->user()->organizer->country;

                            $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);

                            return Carbon::make($state)->timezone($timezone[0]);
                        }

                        return $state;
                    })
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($record) => $record->stringifyStatus())
                    ->badge()
                    ->color(fn($record) => $record->getStatusClass()),

                Tables\Columns\IconColumn::make('deleted_at')
                    ->label('Is Deleted')
                    ->icon(fn($state) => $state ? 'heroicon-o-check' : '')
                    ->alignCenter()
                    ->color(fn($state) => $state ? 'danger' : 'info')
            ]);
    }
}
