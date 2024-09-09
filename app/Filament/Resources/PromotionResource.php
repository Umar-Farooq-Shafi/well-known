<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Event;
use App\Models\Promotion;
use DateTimeZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['ROLE_SUPER_ADMIN', 'ROLE_ADMINISTRATOR', 'ROLE_ORGANIZER']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(function () {
                    if (auth()->user()->hasAnyRole(['ROLE_SUPER_ADMIN', 'ROLE_ADMINISTRATOR'])) {
                        return "Start Date and Time are in UTC Time";
                    }

                    return "";
                })
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->required(),

                        Forms\Components\Repeater::make('promotionQuantities')
                            ->relationship()
                            ->columns(2)
                            ->columnSpanFull()
                            ->minItems(1)
                            ->required()
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->integer()
                                    ->required(),

                                Forms\Components\TextInput::make('discount')
                                    ->integer()
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('events')
                            ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                            ->multiple()
                            ->relationship('events')
                            ->options(function () {
                                $events = Event::query()->where('completed', false)->get();

                                $options = [];

                                foreach ($events as $event) {
                                    if ($event->name) {
                                        $options[$event->id] = $event->name;
                                    }
                                }

                                return $options;
                            })
                            ->getSearchResultsUsing(function (string $query) {
                                $events = Event::query()->where('completed', false)
                                    ->whereHas(
                                        'eventTranslations',
                                        fn (Builder $builder) => $builder->where('name', 'LIKE', '%' . $query . '%')
                                    )
                                    ->get();

                                $options = [];

                                foreach ($events as $event) {
                                    if ($event->name) {
                                        $options[$event->id] = $event->name;
                                    }
                                }

                                return $options;
                            })
                            ->required(),

                        Forms\Components\Select::make('timezone')
                            ->searchable()
                            ->visible(auth()->user()->hasRole('ROLE_ORGANIZER'))
                            ->options(function () {
                                $timezones = DateTimeZone::listIdentifiers();
                                $options = [];

                                foreach ($timezones as $timezone) {
                                    $options[$timezone] = str_replace('/', ' ', $timezone);
                                }

                                return $options;
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('promotionQuantities.quantity')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('promotionQuantities.discount')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('id')
                    ->label('Status')
                    ->state(function ($record) {
                        if ($record->start_date->greaterThan(now()) && $record->end_date->lessThan(now())) {
                            return 'Running';
                        }

                        if (now()->lessThan($record->start_date)) {
                            return 'Further';
                        }

                        return 'Completed';
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($livewire) {
                            foreach ($livewire->getSelectedTableRecords() as $record) {
                                $record->promotionQuantities()->delete();
                            }
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                fn(Builder $query): Builder => $query->where('organizer_id', auth()->user()->organizer_id),
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
