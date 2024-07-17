<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenityResource\Pages;
use App\Models\Amenity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Venues';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

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
                                    ->label('Name')
                                    ->required()
                            ]),
                        Forms\Components\Tabs\Tab::make('Fr')
                            ->schema([
                                Forms\Components\TextInput::make('name-fr')
                                    ->label('Nom')
                            ]),
                        Forms\Components\Tabs\Tab::make('Es')
                            ->schema([
                                Forms\Components\TextInput::make('name-es')
                                    ->label('Nombre')
                            ]),
                        Forms\Components\Tabs\Tab::make('Ar')
                            ->schema([
                                Forms\Components\TextInput::make('name-ar')
                                    ->label('اسم')
                            ]),
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                        IconPicker::make('icon')
                            ->required()
                            ->sets(['fontawesome-solid'])
                            ->preload()
                    ])
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
                    ->searchable()
                    ->sortable(),

                IconColumn::make('icon')
                    ->view('filament.pages.icon'),

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'edit' => Pages\EditAmenity::route('/{record}/edit'),
        ];
    }
}
