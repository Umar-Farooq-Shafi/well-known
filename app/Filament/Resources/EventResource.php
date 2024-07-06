<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Audience;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Event;
use App\Models\Language;
use App\Models\PointsOfSale;
use App\Models\Scanner;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Events';

    protected static ?string $navigationLabel = 'Manage Events';

    public static function canCreate(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ORGANIZER');
    }

    public static function canEdit(Model $record): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ORGANIZER');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->options(function () {
                        $categories = Category::all();
                        $options = [];

                        foreach ($categories as $category) {
                            $options[$category->id] = $category->categoryTranslations()
                                ->where('locale', App::getLocale())->first()?->name;
                        }

                        return $options;
                    }),

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

                                        Forms\Components\RichEditor::make('content-en')
                                            ->label('Content'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Fr')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-fr')
                                            ->label('Nom'),

                                        Forms\Components\RichEditor::make('content-fr')
                                            ->label('Contenu'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Es')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-es')
                                            ->label('Nombre'),

                                        Forms\Components\TextInput::make('tags-es')
                                            ->label('Palabras clave'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Ar')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-ar')
                                            ->label('عنوان'),

                                        Forms\Components\RichEditor::make('content-ar')
                                            ->label('محتوى'),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Radio::make('showattendees')
                    ->label('Attendees')
                    ->label('Show the attendees number and list in the event page')
                    ->options([
                        1 => 'Show',
                        0 => 'Hide'
                    ])
                    ->required(),

                Forms\Components\Radio::make('enablereviews')
                    ->label('Enable reviews')
                    ->options([
                        1 => 'Enable',
                        0 => 'Disable'
                    ])
                    ->required(),

                Forms\Components\Select::make('languages')
                    ->label('Language')
                    ->multiple()
                    ->relationship('languages')
                    ->options(function () {
                        $languages = Language::all();
                        $options = [];

                        foreach ($languages as $language) {
                            $options[$language->id] = $language->languageTranslations()
                                ->where('locale', App::getLocale())->first()?->name;
                        }

                        return $options;
                    }),

                Forms\Components\Select::make('subtitles')
                    ->label('Subtitle')
                    ->multiple()
                    ->relationship('subtitles')
                    ->options(function () {
                        $languages = Language::all();
                        $options = [];

                        foreach ($languages as $language) {
                            $options[$language->id] = $language->languageTranslations()
                                ->where('locale', App::getLocale())->first()?->name;
                        }

                        return $options;
                    }),

                Forms\Components\Select::make('year')
                    ->options(array_reverse(range(1900, 2026)))
                    ->helperText('If your event is a movie for example, select the year of release'),

                Forms\Components\CheckboxList::make('audiences')
                    ->helperText('Select the audience types that are targeted in your event')
                    ->columnSpanFull()
                    ->options(function () {
                        $audiences = Audience::all();
                        $options = [];

                        foreach ($audiences as $audience) {
                            $options[$audience->id] = $audience->audienceTranslations()
                                ->where('locale', App::getLocale())->first()->name;
                        }

                        return $options;
                    }),

                Forms\Components\TextInput::make('youtubeurl')
                    ->label('Youtube video url')
                    ->helperText('If you have an Youtube video that represents your activities as an event organizer, add it in the standard format: https://www.youtube.com/watch?v=FzG4uDgje3M'),

                Forms\Components\TextInput::make('externallink')
                    ->label('External link')
                    ->helperText('If your event has a dedicated website, enter its url here'),

                Forms\Components\TextInput::make('phonenumber')
                    ->label('Contact phone number')
                    ->helperText('Enter the phone number to be called for inquiries'),

                Forms\Components\TextInput::make('email')
                    ->label('Contact email address')
                    ->helperText('Enter the email address to be reached for inquiries'),

                Forms\Components\TextInput::make('twitter'),

                Forms\Components\TextInput::make('instagram'),

                Forms\Components\TextInput::make('facebook'),

                Forms\Components\TextInput::make('googleplus'),

                Forms\Components\TextInput::make('linkedin'),

                Forms\Components\TextInput::make('artists'),

                Forms\Components\TextInput::make('tags'),

                Forms\Components\FileUpload::make('image_name')
                    ->label('Main event image')
                    ->helperText('Choose the right image to represent your event (We recommend using at least a 1200x600px (2:1 ratio) image )')
                    ->required()
                    ->columnSpanFull()
                    ->disk('public')
                    ->formatStateUsing(fn ($state) => $state ? ["events/" . $state] : null)
                    ->directory('events')
                    ->preserveFilenames('image_original_name'),

                Forms\Components\Repeater::make('eventImages')
                    ->label('Images gallery')
                    ->relationship('eventImages')
                    ->columnSpanFull()
                    ->helperText('Add other images that represent your event to be displayed as a gallery')
                    ->schema([
                        Forms\Components\FileUpload::make('image_name')
                            ->label('Gallery image')
                            ->required()
                            ->columnSpanFull()
                            ->disk('public')
                            ->formatStateUsing(fn ($state) => $state ? ["events/" . $state] : null)
                            ->directory('events')
                            ->preserveFilenames('image_original_name'),
                    ]),

                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->helperText("Select the country that your event represents (ie: A movie's country of production)")
                    ->options(function () {
                        $countries = Country::all();
                        $options = [];

                        foreach ($countries as $country) {
                            $options[$country->id] = $country->countryTranslations()
                                ->where('locale', App::getLocale())->first()->name;
                        }

                        return $options;
                    }),

                Forms\Components\Repeater::make('eventDates')
                    ->label('Event dates')
                    ->required()
                    ->relationship('eventDates')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Radio::make('recurrent')
                            ->label('Is this event recurring?')
                            ->boolean()
                            ->live()
                            ->required(),

                        Forms\Components\DatePicker::make('recurrent_startdate')
                            ->label('Calendar Starts On')
                            ->required()
                            ->native(false)
                            ->visible(fn (Forms\Get $get) => $get('recurrent')),

                         Forms\Components\DatePicker::make('recurrent_enddate')
                             ->label('Calendar Ends On')
                             ->required()
                             ->native(false)
                             ->visible(fn (Forms\Get $get) => $get('recurrent')),

                        Forms\Components\Radio::make('active')
                            ->label('Enable sales for this event date ?')
                            ->required()
                            ->boolean()
                            ->helperText('Enabling sales for an event date does not affect the tickets individual sale status'),

                        Forms\Components\Radio::make('online')
                            ->label('Is this event date online ?')
                            ->required()
                            ->boolean(),

                        Forms\Components\Select::make('venue_id')
                            ->label('Venue')
                            ->options(function () {
                                $venues = Venue::all();
                                $options = [];

                                foreach ($venues as $venue) {
                                    $options[$venue->id] = $venue->venueTranslations()
                                        ->where('locale', App::getLocale())->first()->name;
                                }

                                return $options;
                            }),

                        Forms\Components\Select::make('scanners')
                            ->label('Scanners')
                            ->multiple()
                            ->relationship('scanners')
                            ->options(Scanner::pluck('name', 'id')),

                        Forms\Components\Select::make('pointOfSales')
                            ->label('Points of sale')
                            ->multiple()
                            ->relationship('pointOfSales')
                            ->options(PointsOfSale::pluck('name', 'id')),

                        Forms\Components\Repeater::make('eventDateTickets')
                            ->label('Event tickets')
                            ->required()
                            ->relationship('eventDateTickets')
                            ->schema([
                                Forms\Components\Radio::make('active')
                                    ->label('Enable sales for this ticket ?')
                                    ->boolean()
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Ticket name')
                                    ->helperText('Early bird, General admission, VIP...')
                                    ->required(),

                                Forms\Components\Textarea::make('description')
                                    ->label('Ticket description')
                                    ->helperText('Tell your attendees more about this ticket type'),

                                Forms\Components\Radio::make('free')
                                    ->label('Is this ticket free ?')
                                    ->required()
                                    ->boolean(),

                                Forms\Components\Select::make('currency_code_id')
                                    ->label('Currency')
                                    ->required()
                                    ->options(Currency::pluck('ccy', 'id')),

                                Forms\Components\Radio::make('currency_symbol_position')
                                    ->required()
                                    ->options([
                                        'left' => 'Left',
                                        'right' => 'Right',
                                    ]),

                                Forms\Components\TextInput::make('ticket_fee')
                                    ->label('Ticket fee (Online)')
                                    ->required()
                                    ->integer()
                                    ->helperText('This fee will be added to the ticket sale price which are bought online, put 0 to disable additional fees for tickets which are bought online, does not apply for free tickets, will be applied to future orders')
                                    ->prefix('RS'),

                                Forms\Components\TextInput::make('price')
                                    ->integer()
                                    ->prefix('RS'),

                                Forms\Components\TextInput::make('promotionalprice')
                                    ->label('Promotional price')
                                    ->helperText('Set a price lesser than than the original price to indicate a promotion (this price will be the SALE price)')
                                    ->integer()
                                    ->prefix('RS'),

                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->integer(),

                                Forms\Components\TextInput::make('ticketsperattendee')
                                    ->label('Tickets per attendee')
                                    ->helperText('Set the number of tickets that an attendee can buy for this ticket type')
                                    ->integer(),

                                Forms\Components\DatePicker::make('salesstartdate')
                                    ->label('Sale starts On')
                                    ->native(false),

                                Forms\Components\DatePicker::make('salesenddate')
                                    ->label('Sale ends On')
                                    ->native(false),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->eventTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Is Feathered')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->searchable(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
