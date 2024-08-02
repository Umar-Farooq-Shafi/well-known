<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowingResource\Pages;
use App\Models\Organizer;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FollowingResource extends Resource
{
    protected static ?string $model = Organizer::class;

    protected static ?string $navigationIcon = 'fas-folder';

    protected static ?string $label = 'Following';

    protected static ?string $navigationLabel = 'Following';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ATTENDEE');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('More Detail')
                    ->url(fn ($record) => route('organizer-profile', ['slug' => $record->slug])),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas(
                'followings',
                fn (Builder $query): Builder => $query->where('User_id', auth()->id())
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFollowings::route('/'),
        ];
    }
}
