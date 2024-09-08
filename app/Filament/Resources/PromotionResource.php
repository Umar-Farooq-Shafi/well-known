<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Event;
use App\Models\Promotion;
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('promotionQuantities.quantity')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('promotionQuantities.discount')
                    ->searchable()
                    ->sortable(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                fn(Builder $query): Builder => $query->where('events.organizer_id', auth()->user()->organizer_id),
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
