<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueTypeResource\Pages;
use App\Models\VenueType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class VenueTypeResource extends Resource
{
    protected static ?string $model = VenueType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Venues';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Translation')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('En (Default)')
                            ->schema([
                                Forms\Components\TextInput::make('name-en')
                                    ->required()
                            ]),
                        Forms\Components\Tabs\Tab::make('Fr')
                            ->schema([
                                Forms\Components\TextInput::make('name-fr')
                                    ->required()
                            ]),
                        Forms\Components\Tabs\Tab::make('Es')
                            ->schema([
                                Forms\Components\TextInput::make('name-es')
                                    ->required()
                            ]),
                        Forms\Components\Tabs\Tab::make('Ar')
                            ->schema([
                                Forms\Components\TextInput::make('name-ar')
                                    ->required()
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->venueTypeTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('venues_count')
                    ->counts('venues')
                    ->searchable()
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Hide')
                        ->icon('heroicon-o-eye-slash')
                        ->hidden(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => true])),

                    Tables\Actions\Action::make('Show')
                        ->icon('heroicon-o-eye')
                        ->visible(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => false])),

                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListVenueTypes::route('/'),
            'create' => Pages\CreateVenueType::route('/create'),
            'edit' => Pages\EditVenueType::route('/{record}/edit'),
        ];
    }
}
