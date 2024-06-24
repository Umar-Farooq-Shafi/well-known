<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('User Information')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('roles')
                            ->formatStateUsing(function ($state) {
                                return ucwords(str_replace('ROLE_', '', implode(', ', unserialize($state))));
                            }),

                        Infolists\Components\TextEntry::make('enabled')
                            ->label('Status')
                            ->formatStateUsing(function ($state): string {
                                return match ($state) {
                                    1 => 'Enabled',
                                    0 => 'Disabled',
                                    default => $state
                                };
                            })
                            ->icon(fn($state) => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                            ->badge()
                            ->color(function ($state) {
                                return match ($state) {
                                    1 => 'success',
                                    0 => 'danger',
                                    default => 'info'
                                };
                            }),

                        Infolists\Components\TextEntry::make('firstname'),
                        Infolists\Components\TextEntry::make('lastname'),
                        Infolists\Components\TextEntry::make('username'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Registration date'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated date'),

                        Infolists\Components\TextEntry::make('last_login')
                    ])
            ]);
    }
}
