<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Audience;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\Language;
use App\Models\PointsOfSale;
use App\Models\Scanner;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Events';

    protected static ?string $navigationLabel = 'Manage Events';

    public static function getNavigationLabel(): string
    {
        if (auth()->user()->hasRole('ROLE_SCANNER')) {
            return 'Events List';
        }

        if (auth()->user()->hasRole('ROLE_POINTOFSALE')) {
            return 'Events On Sale';
        }

        return static::$navigationLabel;
    }

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return !str_contains($role, 'ATTENDEE');
    }

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
                            $options[$category->id] = $category
                                ->categoryTranslations()
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

                                        Forms\Components\TextInput::make('content-es')
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
                        0 => 'Hide',
                    ])
                    ->required(),

                Forms\Components\Radio::make('enablereviews')
                    ->label('Enable reviews')
                    ->options([
                        1 => 'Enable',
                        0 => 'Disable',
                    ])
                    ->required(),

                Forms\Components\Select::make('languages')
                    ->label('Language')
                    ->multiple()
                    ->relationship('languages')
                    ->saveRelationshipsUsing(function ($record, $state) {
                        $record->languages()->detach();

                        $record->languages()->attach($state);
                        $record->save();
                    })
                    ->options(function () {
                        $languages = Language::all();
                        $options = [];

                        foreach ($languages as $language) {
                            $options[$language->id] = $language
                                ->languageTranslations()
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
                            $options[$language->id] = $language
                                ->languageTranslations()
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
                    ->relationship('audiences')
                    ->saveRelationshipsUsing(function ($record, $state) {
                        $record->audiences()->detach();

                        $record->audiences()->attach($state);
                        $record->save();
                    })
                    ->options(function () {
                        $audiences = Audience::all();
                        $options = [];

                        foreach ($audiences as $audience) {
                            $options[$audience->id] = $audience
                                ->audienceTranslations()
                                ->where('locale', App::getLocale())->first()->name;
                        }

                        return $options;
                    }),

                Forms\Components\TextInput::make('youtubeurl')
                    ->label('Youtube video url')
                    ->helperText(
                        'If you have an Youtube video that represents your activities as an event organizer, add it in the standard format: https://www.youtube.com/watch?v=FzG4uDgje3M',
                    ),

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
                    ->helperText(
                        'Choose the right image to represent your event (We recommend using at least a 1200x600px (2:1 ratio) image )',
                    )
                    ->required()
                    ->columnSpanFull()
                    ->disk('public')
                    ->formatStateUsing(fn($state) => $state ? ["events/" . $state] : null)
                    ->directory('events')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
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
                            ->formatStateUsing(fn($state) => $state ? ["events/" . $state] : null)
                            ->directory('events')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                            ->preserveFilenames('image_original_name'),
                    ]),

                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->helperText("Select the country that your event represents (ie: A movie's country of production)")
                    ->live()
                    ->options(function () {
                        $countries = Country::all();
                        $options = [];

                        foreach ($countries as $country) {
                            $options[$country->id] = $country
                                ->countryTranslations()
                                ->where('locale', App::getLocale())->first()->name;
                        }

                        return $options;
                    }),

                Forms\Components\Select::make('eventtimezone')
                    ->label('Time Zone')
                    ->required()
                    ->options(function (Forms\Get $get) {
                        $country_id = $get('country_id');

                        if ($country_id) {
                            $country = Country::find($country_id);

                            $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);

                            $options = [];

                            foreach ($timezones as $timezone) {
                                $options[$timezone] = str_replace('/', ' ', $timezone);
                            }

                            return $options;
                        }

                        return [];
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

                        Forms\Components\DateTimePicker::make('recurrent_startdate')
                            ->label('Calendar Starts On')
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('recurrent')),

                        Forms\Components\DateTimePicker::make('recurrent_enddate')
                            ->label('Calendar Ends On')
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('recurrent')),

                        Forms\Components\Radio::make('active')
                            ->label('Enable sales for this event date ?')
                            ->required()
                            ->boolean()
                            ->helperText(
                                'Enabling sales for an event date does not affect the tickets individual sale status',
                            ),

                        Forms\Components\DateTimePicker::make('startdate')
                            ->label('Starts On')
                            ->hidden(fn(Forms\Get $get) => $get('recurrent'))
                            ->required(),

                        Forms\Components\DateTimePicker::make('enddate')
                            ->label('Ends On')
                            ->hidden(fn(Forms\Get $get) => $get('recurrent'))
                            ->required(),

                        Forms\Components\Radio::make('online')
                            ->label('Is this event date online ?')
                            ->required()
                            ->live()
                            ->boolean(),

                        Forms\Components\Select::make('venue_id')
                            ->label('Venue')
                            ->hidden(fn(Forms\Get $get) => $get('online'))
                            ->options(function (Forms\Get $get) {
                                if ($get('../../country_id')) {
                                    $venues = Venue::whereCountryId($get('../../country_id'))->get();
                                } else {
                                    $venues = Venue::all();
                                }

                                $options = [];

                                foreach ($venues as $venue) {
                                    $options[$venue->id] = $venue
                                        ->venueTranslations()
                                        ->where('locale', App::getLocale())->first()->name;
                                }

                                return $options;
                            }),

                        Forms\Components\Select::make('scanners')
                            ->label('Scanners')
                            ->multiple()
                            ->relationship('scanners')
                            ->getSearchResultsUsing(
                                fn(string $query)
                                    => Scanner::whereOrganizerId(auth()->user()->organizer_id)
                                    ->where('name', 'like', "%{$query}%")
                                    ->pluck('name', 'id'),
                            )
                            ->options(
                                Scanner::whereOrganizerId(auth()->user()->organizer_id)
                                    ->pluck('name', 'id'),
                            ),

                        Forms\Components\Select::make('pointOfSales')
                            ->label('Points of sale')
                            ->multiple()
                            ->relationship('pointOfSales')
                            ->getSearchResultsUsing(
                                fn(string $query)
                                    => PointsOfSale::whereOrganizerId(auth()->user()->organizer_id)
                                    ->where('name', 'like', "%{$query}%")
                                    ->pluck('name', 'id'),
                            )
                            ->options(
                                PointsOfSale::whereOrganizerId(auth()->user()->organizer_id)
                                    ->pluck('name', 'id'),
                            ),

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
                                    ->live()
                                    ->required()
                                    ->boolean(),

                                Forms\Components\Select::make('currency_code_id')
                                    ->label('Currency')
                                    ->required()
                                    ->live()
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->options(Currency::pluck('ccy', 'id')),

                                Forms\Components\Radio::make('currency_symbol_position')
                                    ->required()
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->options([
                                        'left' => 'Left',
                                        'right' => 'Right',
                                    ]),

                                Forms\Components\Select::make('paymentGateways')
                                    ->multiple()
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->relationship(
                                        'paymentGateways',
                                        'name',
                                        function (Builder $query) {
                                            $query
                                                ->when(
                                                    auth()->user()->membership_type === 'Membership',
                                                    fn(Builder $query)
                                                        => $query
                                                        ->where(
                                                            'organizer_id',
                                                            auth()->user()->organizer_id,
                                                        ),
                                                );
                                        },
                                    )
                                    ->saveRelationshipsUsing(function ($record, $state) {
                                        $record->paymentGateways()->detach();

                                        $record->paymentGateways()->attach($state);
                                        $record->save();
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->prefix(function (Forms\Get $get) {
                                        $currencyCode = $get('currency_code_id');

                                        if ($currencyCode) {
                                            return Currency::find($currencyCode)->ccy;
                                        }

                                        return '';
                                    }),

                                Forms\Components\TextInput::make('ticket_fee')
                                    ->label('Ticket fee (Online)')
                                    ->required()
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->numeric()
                                    ->helperText(
                                        'This fee will be added to the ticket sale price which are bought online, put 0 to disable additional fees for tickets which are bought online, does not apply for free tickets, will be applied to future orders',
                                    )
                                    ->prefix(function (Forms\Get $get) {
                                        $currencyCode = $get('currency_code_id');

                                        if ($currencyCode) {
                                            return Currency::find($currencyCode)->ccy;
                                        }

                                        return '';
                                    }),

                                Forms\Components\TextInput::make('promotionalprice')
                                    ->label('Promotional price')
                                    ->hidden(fn (Forms\Get $get) => $get('free'))
                                    ->helperText(
                                        'Set a price lesser than than the original price to indicate a promotion (this price will be the SALE price)',
                                    )
                                    ->numeric()
                                    ->prefix(function (Forms\Get $get) {
                                        $currencyCode = $get('currency_code_id');

                                        if ($currencyCode) {
                                            return Currency::find($currencyCode)->ccy;
                                        }

                                        return '';
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->integer(),

                                Forms\Components\TextInput::make('ticketsperattendee')
                                    ->label('Tickets per attendee')
                                    ->helperText(
                                        'Set the number of tickets that an attendee can buy for this ticket type',
                                    )
                                    ->integer(),

                                Forms\Components\DateTimePicker::make('salesstartdate')
                                    ->label('Sale starts On'),

                                Forms\Components\DateTimePicker::make('salesenddate')
                                    ->label('Sale ends On'),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                                $data['position'] = 0;
                                $data['currency_symbol_position'] = $data['currency_symbol_position'] ?? 'left';
                                $data['ticket_fee'] = $data['ticket_fee'] ?? 0;

                                return $data;
                            }),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(fn($record) => $record->image_name ? ['events/' . $record->image_name] : null)
                    ->disk('public'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(
                        fn($record) => $record->eventTranslations()->where('locale', App::getLocale())->first()?->name,
                    )
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->whereHas(
                                'eventTranslations',
                                fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"),
                            );
                        },
                        isIndividual: true,
                    )
                    ->sortable(
                        query: function (Builder $query, string $direction) {
                            $query
                                ->whereHas('eventTranslations')
                                ->with([
                                    'eventTranslations' => function ($query) use ($direction) {
                                        $query->orderBy('name', $direction);
                                    },
                                ])
                                ->orderBy(
                                    EventTranslation::select('name')
                                        ->whereColumn('eventic_event_translation.translatable_id', 'eventic_event.id')
                                        ->orderBy('name', $direction)
                                        ->limit(1),
                                    $direction,
                                );
                        },
                    ),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                ProgressColumn::make('progress')
                    ->progress(fn($record) => $record->getTotalSalesPercentage()),

                Tables\Columns\TextColumn::make('created_at')
                    ->state(fn($record) => $record->getTotalOrderElementsQuantitySum())
                    ->label('Tickets sold'),

                Tables\Columns\TextColumn::make('published')
                    ->badge()
                    ->color(fn($record) => $record->stringifyStatusClass())
                    ->state(fn($record) => $record->stringifyStatus())
                    ->label('Status'),

                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Is Feathered')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->searchable(),

                ProgressColumn::make('updated_at')
                    ->label('Attendee')
                    ->progress(fn($record) => $record->getTotalCheckInPercentage()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->searchable()
                    ->options(function () {
                        return EventTranslation::query()
                            ->when(
                                auth()->user()->hasRole('ROLE_ORGANIZER'),
                                fn(Builder $query)
                                    => $query->whereHas(
                                    'event',
                                    fn(Builder $query) => $query->where('organizer_id', auth()->user()->organizer_id),
                                ),
                            )
                            ->pluck('name', 'translatable_id');
                    })
                    ->query(
                        fn(Builder $query, array $data)
                            => $query->when(
                            $data['value'],
                            fn(Builder $query, $value) => $query->where('id', $value),
                        ),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('statistic')
                        ->label('Statistics')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->modalHeading(
                            fn($record) => $record
                                    ->eventTranslations()
                                    ->where('locale', App::getLocale())->first()?->name . ' : Event dates',
                        )
                        ->modalSubmitActionLabel('View stats')
                        ->modalContent(
                            fn($record) => new HtmlString("<h1>" . $record->eventDates()->first()->startdate . "</h1>"),
                        )
                        ->action(
                            fn($record) => redirect()->route(
                                'filament.admin.resources.events.view-stats',
                                ['record' => $record],
                            ),
                        )
                        ->icon('heroicon-o-presentation-chart-bar'),

                    Tables\Actions\Action::make('payout-request')
                        ->label('Request payout')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->icon('fas-file-invoice-dollar')
                        ->modalHeading(
                            fn($record) => $record
                                    ->eventTranslations()
                                    ->where('locale', App::getLocale())->first()?->name . ' : Payout request',
                        )
                        ->modalContent(fn(Event $record)
                            => view(
                            'filament.resources.event-resource.payout-request',
                            ['record' => $record],
                        ))
                        ->modalSubmitActionLabel('Request payout')
                        ->visible(function () {
                            return self::canCreate();
                        }),

                    Tables\Actions\Action::make('details')
                        ->label('Details')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->requiresConfirmation()
                        ->fillForm(function (Event $event) {
                            $languages = [];
                            $audiences = [];

                            foreach ($event->languages as $language) {
                                $languages[] = $language
                                    ->languageTranslations()
                                    ->where('locale', App::getLocale())
                                    ->first()?->name;
                            }

                            foreach ($event->audiences as $audience) {
                                $audiences[] = $audience
                                    ->audienceTranslations()
                                    ->where('locale', App::getLocale())
                                    ->first()?->name;
                            }

                            return [
                                'event' => $event,
                                'image_name' => $event->image_name,
                                'title' => $event
                                    ->eventTranslations()
                                    ->where('locale', App::getLocale())->first()?->name,
                                'organizer' => $event->organizer->name,
                                'reference' => $event->reference,
                                'creation_date' => $event->created_at,
                                'update_date' => $event->updated_at,
                                'views' => $event->views,
                                'added_to_favorites_by' => $event->favourites()->count(),
                                'category' => $event->category
                                    ->categoryTranslations()
                                    ->where('locale', App::getLocale())->first()?->name,
                                'language' => implode(', ', $languages),
                                'audiences' => implode(', ', $audiences),
                                'country' => $event->country
                                    ->countryTranslations()
                                    ->where('locale', App::getLocale())->first()?->name,
                                'publicly_show_attendees' => $event->showattendees,
                                'allow_attendees_to_leave_reviews' => $event->enablereviews,
                            ];
                        })
                        ->form([
                            Forms\Components\Section::make('Images')
                                ->collapsible()
                                ->columns(2)
                                ->schema([
                                    Forms\Components\FileUpload::make('image_name')
                                        ->label('Main event image')
                                        ->columnSpanFull()
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                                        ->disk('public')
                                        ->formatStateUsing(fn($state) => $state ? ["events/" . $state] : null)
                                        ->directory('events'),

                                    Forms\Components\Repeater::make('Gallery')
                                        ->relationship('eventImages')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_name')
                                                ->label('Gallery image')
                                                ->required()
                                                ->disk('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                                                ->formatStateUsing(fn($state) => $state ? ["events/" . $state] : null)
                                                ->directory('events'),
                                        ])
                                        ->addable(false)
                                        ->deletable(false),
                                ]),

                            Forms\Components\Section::make('General Information')
                                ->columns(2)
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('title'),
                                    Forms\Components\TextInput::make('organizer'),
                                    Forms\Components\TextInput::make('reference'),
                                    Forms\Components\TextInput::make('creation_date'),
                                    Forms\Components\TextInput::make('update_date'),
                                    Forms\Components\TextInput::make('views'),
                                    Forms\Components\TextInput::make('added_to_favorites_by'),
                                    Forms\Components\TextInput::make('category'),
                                    Forms\Components\TextInput::make('language'),
                                    Forms\Components\TextInput::make('audiences'),
                                    Forms\Components\TextInput::make('country'),
                                    Forms\Components\TextInput::make('publicly_show_attendees')
                                        ->formatStateUsing(fn($state) => $state ? "Yes" : "No"),
                                    Forms\Components\TextInput::make('allow_attendees_to_leave_reviews')
                                        ->formatStateUsing(fn($state) => $state ? "Yes" : "No"),
                                ]),

                            Forms\Components\Repeater::make('Event dates')
                                ->relationship('eventDates')
                                ->columns(2)
                                ->addable(false)
                                ->deletable(false)
                                ->schema([
                                    Forms\Components\DatePicker::make('reference'),
                                    Forms\Components\TextInput::make('venue')
                                        ->formatStateUsing(
                                            fn($record) => $record->venue?->venueTranslations()
                                                ?->where('locale', App::getLocale())?->first()?->name,
                                        ),
                                ]),
                        ])
                        ->modalWidth(MaxWidth::Full)
                        ->modalHeading('')
                        ->modalCancelActionLabel('Close')
                        ->modalSubmitAction('')
                        ->icon('heroicon-o-document-text'),

                    Tables\Actions\Action::make('attendees')
                        ->label('Attendees')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->url(
                            fn($record) => route('filament.admin.resources.orders.index'),
                        )
                        ->icon('heroicon-o-user-group'),

                    Tables\Actions\Action::make('reviews')
                        ->label('Reviews')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->url(
                            fn($record) => route('filament.admin.resources.reviews.index'),
                        )
                        ->icon('heroicon-o-star'),

                    Tables\Actions\Action::make('Mark as featured')
                        ->icon('heroicon-o-eye-slash')
                        ->hidden(
                            fn($record) => $record->is_featured || auth()->user()->hasAnyRole(
                                    ['ROLE_SCANNER', 'ROLE_POINTOFSALE'],
                                ),
                        )
                        ->visible(function ($record) {
                            if (!$record->is_featured) {
                                return true;
                            }

                            $role = ucwords(
                                str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))),
                            );

                            return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
                        })
                        ->action(fn($record) => $record->update(['is_featured' => 1])),

                    Tables\Actions\Action::make('Mark as not featured')
                        ->icon('heroicon-o-eye')
                        ->hidden(
                            fn($record) => !$record->is_featured || auth()->user()->hasAnyRole(
                                    ['ROLE_SCANNER', 'ROLE_POINTOFSALE'],
                                ),
                        )
                        ->visible(function ($record) {
                            if ($record->is_featured) {
                                return true;
                            }

                            $role = ucwords(
                                str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))),
                            );

                            return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
                        })
                        ->action(fn($record) => $record->update(['is_featured' => false])),

                    Tables\Actions\Action::make('completed')
                        ->label('Completed')
                        ->icon('heroicon-o-check')
                        ->hidden(
                            fn($record) => $record->completed || auth()->user()->hasAnyRole(
                                    ['ROLE_SCANNER', 'ROLE_POINTOFSALE'],
                                ),
                        )
                        ->action(fn($record) => $record->update([
                            'completed' => 1,
                            'is_featured' => false,
                        ])),

                    Tables\Actions\Action::make('not-completed')
                        ->label('Not Completed')
                        ->icon('heroicon-o-x-mark')
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE']))
                        ->visible(fn($record) => $record->completed)
                        ->action(fn($record) => $record->update([
                            'completed' => false,
                        ])),

                    Tables\Actions\EditAction::make()
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE'])),

                    Tables\Actions\DeleteAction::make()
                        ->hidden(auth()->user()->hasAnyRole(['ROLE_SCANNER', 'ROLE_POINTOFSALE'])),
                ]),

                Tables\Actions\Action::make('check-in')
                    ->label(strtoupper('Check in attendees for this event date'))
                    ->url(fn($record) => Pages\AttendeeCheckInPage::getUrl(['record' => $record]))
                    ->visible(auth()->user()->hasRole('ROLE_SCANNER')),

                Tables\Actions\Action::make('event-date-and-ticket')
                    ->label(strtoupper('Show event dates and tickets'))
                    ->url(fn($record) => Pages\POSPage::getUrl(['record' => $record]))
                    ->visible(auth()->user()->hasRole('ROLE_POINTOFSALE')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                fn(Builder $query) => $query->where('organizer_id', auth()->user()->organizer_id),
            )
            ->when(
                auth()->user()->hasRole('ROLE_SCANNER'),
                fn(Builder $query)
                    => $query->whereHas(
                    'eventDates.scanners',
                    fn(Builder $query) => $query->where('scanner_id', auth()->user()->scanner->id),
                ),
            )
            ->when(
                auth()->user()->hasRole('ROLE_POINTOFSALE'),
                fn(Builder $query)
                    => $query->whereHas(
                    'eventDates.pointOfSales',
                    fn(Builder $query) => $query->where('pointofsale_id', auth()->user()->pointOfSale->id),
                ),
            )->orderBy('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view-stats' => Pages\ViewStatsPage::route('/{record}/stats'),
            'attendee-checkin' => Pages\AttendeeCheckInPage::route('/{record}/attendee-check-in'),
            'pos' => Pages\POSPage::route('/{record}/pos'),
        ];
    }

}
