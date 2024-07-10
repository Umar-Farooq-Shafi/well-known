<?php

namespace App\Filament\Resources;

use App\Filament\Actions\Impersonate;
use App\Filament\Resources\UserResource\Pages;
use App\Models\CountryTranslation;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('roles')
                    ->formatStateUsing(function ($state) {
                        return ucwords(str_replace('ROLE_', '', implode(', ', unserialize($state))));
                    }),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Name')
                    ->icon('heroicon-o-user')
                    ->formatStateUsing(fn ($record) => $record->firstname . " " . $record->lastname)
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(isIndividual: true)
                    ->label('Username/Email')
                    ->description(fn ($record) => $record->email),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Registration Date'),
                Tables\Columns\TextColumn::make('last_login')
                    ->date()
                    ->label('Last Login'),
                Tables\Columns\IconColumn::make('enabled')
                    ->boolean()
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('enabled'),

                Tables\Filters\SelectFilter::make('country_id')
                    ->options(fn () => CountryTranslation::all()
                        ->where('locale', App::getLocale())
                        ->pluck('name', 'id'))
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('organizer-profile')
                        ->label('Organizer Profile')
                        ->visible(function ($record) {
                            $roles = ucwords(str_replace('ROLE_', '', implode(', ', unserialize($record->roles))));

                            return str_contains($roles, 'ADMINISTRATOR');
                        })
                        ->icon('fas-id-card'),

                    Tables\Actions\Action::make('empty-cart')
                        ->label('Empty Cart')
                        ->hidden(function ($record) {
                            $roles = ucwords(str_replace('ROLE_', '', implode(', ', unserialize($record->roles))));

                            return str_contains($roles, 'ADMINISTRATOR');
                        })
                        ->badge(fn ($record) => count($record->cartElements))
                        ->icon('heroicon-o-shopping-cart'),

                    Impersonate::make('Impersonate')->redirectTo(route('filament.admin.pages.dashboard')),

                    Tables\Actions\Action::make('Enable')
                        ->icon('heroicon-o-eye-slash')
                        ->hidden(fn($record) => $record->enabled)
                        ->action(fn($record) => $record->update(['enabled' => true])),

                    Tables\Actions\Action::make('Disable')
                        ->icon('fas-user-alt-slash')
                        ->visible(fn($record) => $record->enabled)
                        ->action(fn($record) => $record->update(['enabled' => false])),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
