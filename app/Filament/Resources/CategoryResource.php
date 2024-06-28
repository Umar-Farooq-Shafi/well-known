<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 2;

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

                IconPicker::make('icon')
                    ->required()
                    ->sets(['fontawesome-solid'])
                    ->preload(),

                Forms\Components\Select::make('featuredorder')
                    ->label('Featured Order')
                    ->helperText('Set the display order for the featured categories')
                    ->options(range(1, 15))
                    ->required(),

                Forms\Components\FileUpload::make('image_name')
                    ->label('Logo')
                    ->required()
                    ->disk('public')
                    ->directory('categories')
                    ->formatStateUsing(fn ($state) => $state ? ['categories/' . $state] : null)
                    ->visibility('public')
                    ->storeFileNamesIn('image_original_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->categoryTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->sortable(),

                IconColumn::make('icon')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(function ($record) {
                        return Storage::disk('public')->url('categories/' . $record->image_name);
                    })
                    ->disk('public'),

                Tables\Columns\TextColumn::make('featured')
                    ->label('Is Feathered')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->searchable(),

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
                        ->hidden(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => true])),

                    Tables\Actions\Action::make('Show')
                        ->icon('heroicon-o-eye')
                        ->visible(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => false])),

                    Tables\Actions\Action::make('Mark as featured')
                        ->icon('heroicon-o-star')
                        ->hidden(fn($record) => $record->featured)
                        ->action(fn($record) => $record->update(['featured' => true])),

                    Tables\Actions\Action::make('Mark as not featured')
                        ->icon('heroicon-o-tag')
                        ->visible(fn($record) => $record->featured)
                        ->action(fn($record) => $record->update(['featured' => false])),

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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
