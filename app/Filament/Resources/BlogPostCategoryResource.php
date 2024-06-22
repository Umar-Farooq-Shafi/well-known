<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostCategoryResource\Pages;
use App\Filament\Resources\BlogPostCategoryResource\RelationManagers;
use App\Models\BlogPostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class BlogPostCategoryResource extends Resource
{
    protected static ?string $model = BlogPostCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Categories';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->blogPostCategoryTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('blog_posts_count')
                    ->counts('blogPosts')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Hidden' : 'Visible')
                    ->color(fn($state) => $state ? 'danger' : 'primary')
                    ->icon(fn ($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge(),
            ])
            ->actions([
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPostCategories::route('/'),
            'create' => Pages\CreateBlogPostCategory::route('/create'),
            'edit' => Pages\EditBlogPostCategory::route('/{record}/edit'),
        ];
    }
}
