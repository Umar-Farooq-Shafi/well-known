<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenityResource\Pages;
use App\Filament\Resources\AmenityResource\RelationManagers;
use App\Models\Amenity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Venues';

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
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->amenityTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('venues_count')
                    ->counts('venues')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            1 => 'Hidden',
                            0 => 'Visible',
                            default => $state
                        };
                    })
                    ->icon(fn($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            1 => 'danger',
                            0 => 'success',
                            default => 'info'
                        };
                    })
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
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }
}
