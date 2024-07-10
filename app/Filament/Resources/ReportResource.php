<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class ReportResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'fas-filter-circle-dollar';

    protected static ?string $navigationLabel = 'Reports';

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return !str_contains($role, 'ATTENDEE');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(
                        fn($record) => $record->eventTranslations()
                            ->where('locale', App::getLocale())->first()?->name
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Event date')
                    ->formatStateUsing(
                        fn($record) => $record->eventDates()->first()->startdate
                    )
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
                fn($query) => $query->where('organizer_id', auth()->user()->organizer_id)
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
