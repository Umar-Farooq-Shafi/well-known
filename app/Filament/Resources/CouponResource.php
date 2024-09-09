<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['ROLE_SUPER_ADMIN', 'ROLE_ADMINISTRATOR', 'ROLE_ORGANIZER']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('code')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'percentage' => 'Percentage Discount',
                        'fixed_amount' => 'Fixed Amount Discount',
                    ])
                    ->live()
                    ->required(),

                Forms\Components\TextInput::make('discount')
                    ->label(fn (Forms\Get $get) => $get('type') === 'fixed_amount' ? 'Fixed Amount For Discount' : 'Percentage Off')
                    ->placeholder('Percentage off (In %) OR Amount off')
                    ->required(),

                Forms\Components\TextInput::make('limit')
                    ->label('Redemption Limit')
                    ->required()
                    ->placeholder('List the total number of times this coupon can be  redemption')
                    ->helperText('[Note: This limit applies across customers so it\' won\'t prevent a single customer from redeeming multiple times]]'),

                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                Forms\Components\DateTimePicker::make('expire_date')
                    ->label('End Date')
                    ->required(),

                Forms\Components\Select::make('events')
                    ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                    ->multiple()
                    ->relationship('events')
                    ->options(function () {
                        $events = Event::query()->where('completed', false)->get();

                        $options = [];

                        foreach ($events as $event) {
                            if ($event->name)
                                $options[$event->id] = $event->name;
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
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type'),

                Tables\Columns\TextColumn::make('discount')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('id')
                    ->label('Status')
                    ->state(function ($record) {
                        if ($record->start_date->greaterThan(now()) && $record->expire_date->lessThan(now())) {
                            return 'Active';
                        }

                        if ($record->start_date->lessThan(now())) {
                            return 'Inactive';
                        }

                        return 'Completed';
                    })
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                fn (Builder $query): Builder => $query->whereHas(
                    'events',
                    fn (Builder $query) => $query->where('organizer_id', auth()->user()->organizer_id)
                )
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
