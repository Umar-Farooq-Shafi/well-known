<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\AmenityTranslation;
use App\Models\CountryTranslation;
use App\Models\Venue;
use App\Models\VenueTypeTranslation;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Venues';

    protected static ?string $navigationLabel = 'Manage Venues';

    public static function canViewAny(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_SCANNER', 'ROLE_POINTOFSALE']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Translation')
                    ->schema([
                        Forms\Components\Tabs::make('Translation')
                            ->columnSpanFull()
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('En (Default)')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-en')
                                            ->label('Name')
                                            ->required(),

                                        Forms\Components\RichEditor::make('content-en')
                                            ->label('Description')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Fr')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-fr')
                                            ->label('Nom'),

                                        Forms\Components\RichEditor::make('content-fr')
                                            ->label('Description'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Es')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-es')
                                            ->label('Nombre'),

                                        Forms\Components\RichEditor::make('content-es')
                                            ->label('Descripción'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Ar')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-ar')
                                            ->label('اسم'),

                                        Forms\Components\RichEditor::make('content-ar')
                                            ->label('التفاصيل'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Select::make('type_id')
                    ->label('Type')
                    ->options(
                        fn() => VenueTypeTranslation::whereLocale(\app()->getLocale())
                            ->pluck('name', 'translatable_id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('amenities')
                    ->relationship(
                        'amenities',
                        'name',
                    )
                    ->saveRelationshipsUsing(
                        static function (Select $component, Model $record, $state) {
                            $relationship = $component->getRelationship();

                            if (! $relationship instanceof BelongsToMany) {
                                $relationship->associate($state);
                                $record->wasRecentlyCreated && $record->save();

                                return;
                            }

                            $pivotData = $component->getPivotData();
                            $relationship->detach();

                            if ($pivotData === []) {
                                $relationship->sync($state ?? []);

                                return;
                            }

                            $relationship->syncWithPivotValues($state ?? [], $pivotData);
                        }
                    )
                    ->getSearchResultsUsing(
                        fn ($search) => AmenityTranslation::whereLocale(App::getLocale())
                            ->where('name', 'like', "%{$search}%")
                            ->limit(5)
                            ->pluck('name', 'translatable_id')
                    )
                    ->multiple()
                    ->options(
                        fn() => AmenityTranslation::whereLocale(App::getLocale())
                            ->limit(20)
                            ->pluck('name', 'translatable_id')
                    )
                    ->searchable(),

                Forms\Components\TextInput::make('seatedguests')
                    ->label('Seated guests number')
                    ->integer(),

                Forms\Components\TextInput::make('standingguests')
                    ->label('Standing guests number')
                    ->integer(),

                Forms\Components\TextInput::make('neighborhoods'),

                Forms\Components\Textarea::make('pricing'),

                Forms\Components\Textarea::make('availibility'),

                Forms\Components\Textarea::make('foodbeverage'),

                Forms\Components\Radio::make('quoteform')
                    ->label('Show the quote form on the venue page')
                    ->required()
                    ->boolean(),

                Forms\Components\Textarea::make('contactemail')
                    ->helperText('This email address will be used to receive the quote requests, make sure to mention it if you want to show the quote form'),

                Forms\Components\TextInput::make('street')
                    ->label('Street address')
                    ->required(),

                Forms\Components\TextInput::make('street2')
                    ->label('Street address 2'),

                Forms\Components\TextInput::make('city')
                    ->label('City')
                    ->required(),

                Forms\Components\TextInput::make('postalcode')
                    ->label('Zip / Postal code')
                    ->required(),

                Forms\Components\TextInput::make('state')->required(),

                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->options(
                        fn ($state) => CountryTranslation::whereLocale(App::getLocale())
                            ->limit(20)
                            ->when(
                                $state && is_numeric($state),
                                fn ($query) => $query->where('translatable_id', $state)
                            )
                            ->pluck('name', 'translatable_id')
                    )
                    ->getSearchResultsUsing(
                        fn ($search) => CountryTranslation::whereLocale(App::getLocale())
                            ->where('name', 'like', "%{$search}%")
                            ->limit(5)
                            ->pluck('name', 'translatable_id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Radio::make('showmap')
                    ->label('Show the map along with the address on the venue page and event page')
                    ->required()
                    ->boolean(),

                Forms\Components\Repeater::make('images')
                    ->relationship('venueImages')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\FileUpload::make('image_name')
                            ->image()
                            ->disk('public')
                            ->formatStateUsing(fn ($state) => $state ? ["venues/" . $state] : null)
                            ->directory('venues')
                            ->required()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                            ->storeFileNamesIn('image_original_name')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->venueTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\ImageColumn::make('venueImages.image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->circular()
                    ->ring(5)
                    ->stacked()
                    ->getStateUsing(function ($record) {
                        $images = [];

                        foreach ($record->venueImages as $image) {
                            $images[] = Storage::disk('public')->url('venues/' . $image->image_name);
                        }

                        return $images;
                    })
                    ->disk('public'),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
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
                    }),

                Tables\Columns\TextColumn::make('listedondirectory')
                    ->label('Status')
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            true => 'Listed on the directory',
                            false => 'Not listed on the directory',
                            default => $state
                        };
                    })
                    ->icon(fn($state) => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            false => 'danger',
                            true => 'success',
                            default => 'info'
                        };
                    })
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('listedondirectory')
                    ->label('Listed on the directory')
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

                    Tables\Actions\Action::make('List on the public directory')
                        ->icon('fas-square-plus')
                        ->hidden(fn($record) => $record->listedondirectory)
                        ->action(fn($record) => $record->update(['listedondirectory' => true])),

                    Tables\Actions\Action::make('Hide from the public directory')
                        ->icon('fas-minus-square')
                        ->visible(fn($record) => $record->listedondirectory)
                        ->action(fn($record) => $record->update(['listedondirectory' => false])),

                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return parent::getEloquentQuery()
            ->when(
                str_contains($role, 'ORGANIZER'),
                fn ($query) => $query->where('organizer_id', auth()->user()->organizer_id)
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
