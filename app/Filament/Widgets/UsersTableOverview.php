<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UsersTableOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
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
            ]);
    }
}
