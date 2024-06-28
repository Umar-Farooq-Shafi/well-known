<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AudienceResource\Pages;
use App\Models\Audience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class AudienceResource extends Resource
{
    protected static ?string $model = Audience::class;

    protected static ?string $navigationIcon = 'fas-people-line';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 5;

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

                Forms\Components\FileUpload::make('image_name')
                    ->label('Logo')
                    ->disk('public')
                    ->directory('audiences')
                    ->formatStateUsing(fn ($state) => $state ? ['audiences/' . $state] : null)
                    ->visibility('public')
                    ->required()
                    ->storeFileNamesIn('image_original_name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->audienceTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events'),

                Tables\Columns\ImageColumn::make('image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->getStateUsing(fn ($record) => $record->image_name ? ['audiences/' . $record->image_name] : null)
                    ->disk('public'),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Hidden' : 'Visible')
                    ->color(fn($state) => $state ? 'danger' : 'primary')
                    ->icon(fn ($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Hide')
                        ->icon('heroicon-o-eye-slash')
                        ->hidden(fn ($record) => $record->hidden)
                        ->action(fn ($record) => $record->update(['hidden' => true])),

                    Tables\Actions\Action::make('Show')
                        ->icon('heroicon-o-eye')
                        ->visible(fn ($record) => $record->hidden)
                        ->action(fn ($record) => $record->update(['hidden' => false])),

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
            'index' => Pages\ListAudiences::route('/'),
            'create' => Pages\CreateAudience::route('/create'),
            'edit' => Pages\EditAudience::route('/{record}/edit'),
        ];
    }
}
