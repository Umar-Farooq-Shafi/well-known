<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticlesResource\Pages;
use App\Models\HelpCenterArticle;
use App\Models\HelpCenterCategoryTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class ArticlesResource extends Resource
{
    protected static ?string $model = HelpCenterArticle::class;

    protected static ?string $navigationIcon = 'fas-gear';

    protected static ?string $navigationGroup = 'Help Center';

    protected static ?string $navigationLabel = 'Articles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->helperText('Make sure to select right category to let the users find it quickly')
                    ->searchable()
                    ->required()
                    ->options(
                        fn() => HelpCenterCategoryTranslation::query()
                            ->where('locale', App::getLocale())
                            ->pluck('name', 'translatable_id')
                    ),

                Forms\Components\Section::make('Article details')
                    ->schema([
                        Forms\Components\Tabs::make('Translation')
                            ->columnSpanFull()
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('En (Default)')
                                    ->schema([
                                        Forms\Components\TextInput::make('title-en')
                                            ->label('Title')
                                            ->required(),

                                        Forms\Components\RichEditor::make('content-en')
                                            ->label('Content')
                                            ->required(),

                                        Forms\Components\TextInput::make('tags-en')
                                            ->label('Tags')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Fr')
                                    ->schema([
                                        Forms\Components\TextInput::make('title-fr')
                                            ->label('Titre'),

                                        Forms\Components\RichEditor::make('content-fr')
                                            ->label('Contenu'),

                                        Forms\Components\TextInput::make('tags-fr')
                                            ->label('Mots clés'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Es')
                                    ->schema([
                                        Forms\Components\TextInput::make('title-es')
                                            ->label('Nombre'),

                                        Forms\Components\RichEditor::make('content-es')
                                            ->label('Contenido'),

                                        Forms\Components\TextInput::make('tags-es')
                                            ->label('Palabras clave'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Ar')
                                    ->schema([
                                        Forms\Components\TextInput::make('title-ar')
                                            ->label('عنوان'),

                                        Forms\Components\RichEditor::make('content-ar')
                                            ->label('محتوى'),

                                        Forms\Components\TextInput::make('tags-ar')
                                            ->label('الكلمات الرئيسية'),
                                    ]),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Title')
                    ->formatStateUsing(fn($record) => $record->helpCenterTranslations()->where('locale', App::getLocale())->first()?->title)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($record) => $record->helpCenterCategory->helpCenterCategoryTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->label('Category')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('views')
                    ->searchable(isIndividual: true)
                    ->formatStateUsing(fn($record) => $record->views ? $record->views . " views" : "0 views")
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Hidden' : 'Visible')
                    ->color(fn($state) => $state ? 'danger' : 'primary')
                    ->icon(fn($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge(),

                Tables\Columns\TextColumn::make('featured')
                    ->formatStateUsing(fn($state) => $state ? 'Featured' : '')
                    ->color(fn($state) => $state ? 'success' : '')
                    ->icon(fn($state) => $state ? 'heroicon-o-star' : '')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Mark as featured')
                        ->icon('heroicon-o-star')
                        ->hidden(fn($record) => $record->featured)
                        ->action(fn ($record) => $record->update(['featured' => 1])),

                    Tables\Actions\Action::make('Mark as not featured')
                        ->icon('heroicon-o-tag')
                        ->visible(fn($record) => $record->featured)
                        ->action(fn ($record) => $record->update(['featured' => 0])),

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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticles::route('/create'),
            'edit' => Pages\EditArticles::route('/{record}/edit'),
        ];
    }
}
