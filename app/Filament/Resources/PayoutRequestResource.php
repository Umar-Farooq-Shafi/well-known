<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayoutRequestResource\Pages;
use App\Filament\Resources\PayoutRequestResource\RelationManagers;
use App\Models\PayoutRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayoutRequestResource extends Resource
{
    protected static ?string $model = PayoutRequest::class;

    protected static ?string $navigationIcon = 'fas-code-pull-request';

    protected static ?string $navigationGroup = 'Payouts';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return !str_contains($role, 'ATTENDEE');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayoutRequests::route('/'),
            'create' => Pages\CreatePayoutRequest::route('/create'),
            'edit' => Pages\EditPayoutRequest::route('/{record}/edit'),
        ];
    }
}
