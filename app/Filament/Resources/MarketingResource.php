<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingResource\Pages;
use App\Filament\Resources\MarketingResource\RelationManagers;
use App\Models\Marketing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketingResource extends Resource
{
    protected static ?string $model = Marketing::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 14;

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
                    ->required(),

                Forms\Components\Select::make('discount')
                    ->label('Percentage Off')
                    ->placeholder('Percentage off (In %) OR Amount off')
                    ->required(),

                Forms\Components\TextInput::make('duration'),

                Forms\Components\DateTimePicker::make('expire_date')
                    ->required(),

                Forms\Components\TextInput::make('limit')
                    ->label('Redemption Limit')
                    ->placeholder('List the total number of times this coupon can be  redemption')
                    ->hint('[Note: This limit applies across customers so it\' won\'t prevent a single customer from redeeming multiple times]]'),


                Forms\Components\Select::make('organizer_id')
                    ->label('Organizer')
                    ->hidden(auth()->user()->hasRole('ROLE_ORGANIZER'))
                    ->relationship('organizer', 'name')
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

                Tables\Columns\TextColumn::make('discount')
                    ->searchable()
                    ->sortable(),
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
                fn (Builder $query): Builder => $query->where('organizer_id', auth()->user()->organizer_id)
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketings::route('/'),
            'create' => Pages\CreateMarketing::route('/create'),
            'edit' => Pages\EditMarketing::route('/{record}/edit'),
        ];
    }
}
