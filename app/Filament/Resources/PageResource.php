<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'fas-copy';

    public static function form(Form $form): Form
    {
        return $form
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
                            ]),
                        Forms\Components\Tabs\Tab::make('Fr')
                            ->schema([
                                Forms\Components\TextInput::make('title-fr')
                                    ->label('Titre'),

                                Forms\Components\RichEditor::make('content-en')
                                    ->label('Contenu')
                            ]),
                        Forms\Components\Tabs\Tab::make('Es')
                            ->schema([
                                Forms\Components\TextInput::make('title-es')
                                    ->label('Título'),

                                Forms\Components\RichEditor::make('content-en')
                                    ->label('Contenido')
                            ]),
                        Forms\Components\Tabs\Tab::make('Ar')
                            ->schema([
                                Forms\Components\TextInput::make('title-ar')
                                    ->label('عنوان'),

                                Forms\Components\RichEditor::make('content-en')
                                    ->label('محتوى')
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Title')
                    ->formatStateUsing(fn($record) => $record->pageTranslations()->where('locale', App::getLocale())->first()?->title)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Slug')
                    ->formatStateUsing(fn($record) => $record->pageTranslations()->where('locale', App::getLocale())->first()?->slug)
                    ->searchable(isIndividual: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
