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
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticles::route('/create'),
            'edit' => Pages\EditArticles::route('/{record}/edit'),
        ];
    }
}
