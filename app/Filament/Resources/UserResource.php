<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
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
                Tables\Filters\TernaryFilter::make('enabled')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
