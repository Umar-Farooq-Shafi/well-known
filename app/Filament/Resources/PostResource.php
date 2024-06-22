<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\BlogPost;
use App\Models\BlogPostCategoryTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $label = 'Posts';

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
                        fn() => BlogPostCategoryTranslation::query()
                            ->where('locale', App::getLocale())
                            ->pluck('name', 'translatable_id')
                    ),

                Forms\Components\Section::make('Event details')
                    ->schema([
                        Forms\Components\Tabs::make('Translation')
                            ->columnSpanFull()
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('En (Default)')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-en')
                                            ->label('Name')
                                            ->required(),

                                        Forms\Components\RichEditor::make('description-en')
                                            ->label('Description')
                                            ->required(),

                                        Forms\Components\TextInput::make('tags-en')
                                            ->label('Tags')
                                            ->helperText('To help attendee find your event quickly, enter some keywords that identify your event (press Enter after each entry)')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Fr')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-fr')
                                            ->label('Nom'),

                                        Forms\Components\RichEditor::make('description-fr')
                                            ->label('Description'),

                                        Forms\Components\TextInput::make('tags-fr')
                                            ->helperText('To help attendee find your event quickly, enter some keywords that identify your event (press Enter after each entry)')
                                            ->label('Mots clés'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Es')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-es')
                                            ->label('Nombre'),

                                        Forms\Components\RichEditor::make('description-es')
                                            ->label('Descripción'),

                                        Forms\Components\TextInput::make('tags-es')
                                            ->helperText('To help attendee find your event quickly, enter some keywords that identify your event (press Enter after each entry)')
                                            ->label('Palabras clave'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Ar')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-ar')
                                            ->label('اسم'),

                                        Forms\Components\RichEditor::make('description-ar')
                                            ->label('التفاصيل'),

                                        Forms\Components\TextInput::make('tags-ar')
                                            ->helperText('To help attendee find your event quickly, enter some keywords that identify your event (press Enter after each entry)')
                                            ->label('الكلمات الرئيسية'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\FileUpload::make('image_name')
                    ->label('Main blog post image')
                    ->disk('public')
                    ->directory('blog')
                    ->columnSpanFull()
                    ->storeFileNamesIn('image_original_name')
                    ->visibility('public')
                    ->required(),

                Forms\Components\TextInput::make('readtime')
                    ->label('Read time in minutes')
                    ->columnSpanFull()
                    ->integer(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->blogPostTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Category')
                    ->formatStateUsing(fn($record) => $record->blogPostCategory->blogPostCategoryTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(function ($record) {
                        return Storage::disk('public')->url('blog/' . $record->image_name);
                    })
                    ->disk('public'),

                Tables\Columns\TextColumn::make('views')
                    ->searchable(isIndividual: true)
                    ->formatStateUsing(fn($record) => $record->views ? $record->views . " views" : "0 views")
                    ->sortable(),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Hidden' : 'Visible')
                    ->color(fn($state) => $state ? 'danger' : 'primary')
                    ->icon(fn($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
