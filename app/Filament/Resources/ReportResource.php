<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\EventDate;
use App\Models\Organizer;
use Exception;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ReportResource extends Resource
{
    protected static ?string $model = EventDate::class;

    protected static ?string $navigationIcon = 'fas-filter-circle-dollar';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $label = 'Reports';

    public static function canViewAny(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_POINTOFSALE', 'ROLE_SCANNER']);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.name')
                    ->label('Name'),

                Tables\Columns\TextColumn::make('startdate')
                    ->label('Start Date')
                    ->date(),

                Tables\Columns\TextColumn::make('event.organizer.name')
                    ->label('Organizer')
                    ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER')),

                Tables\Columns\TextColumn::make('ticket_sold')
                    ->label('Tickets Sold'),

                Tables\Columns\TextColumn::make('organizer_payout_amount')
                    ->prefix('RS ')
                    ->label('Net sales'),

                Tables\Columns\TextColumn::make('total_ticket_fees')
                    ->prefix('RS ')
                    ->label('Ticket fee'),

                Tables\Columns\TextColumn::make('ticket_price_percentage_cut_sum')
                    ->prefix('RS ')
                    ->weight('bold')
                    ->label('Percentage Cut'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => $record->stringifyStatusClass())
                    ->formatStateUsing(fn($record) => $record->stringifyStatus()),
            ])
            ->filters([
                DateRangeFilter::make('startdate')
                    ->label('Event Date'),

                Tables\Filters\SelectFilter::make('organizer')
                    ->label('Organizer')
                    ->visible(auth()->user()->hasAnyRole(['ROLE_SUPER_ADMIN', 'ROLE_ADMINISTRATOR']))
                    ->searchable()
                    ->options(function () {
                        return Organizer::pluck('name', 'id');
                    })
                    ->query(
                        fn(Builder $query, array $data) => $query->when(
                            $data['value'],
                            fn(Builder $query, $value) => $query->whereHas(
                                'event',
                                fn(Builder $query) => $query->where('organizer_id', $value)
                            )
                        )
                    )
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                fn(Builder $query) => $query->whereHas(
                    'event',
                    fn(Builder $query) => $query->where('organizer_id', auth()->user()->organizer_id)
                )
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
