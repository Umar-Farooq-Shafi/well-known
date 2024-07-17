<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ViewUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('roles')
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($state) {
                                return ucwords(str_replace('ROLE_', '', implode(', ', unserialize($state))));
                            }),

                        Forms\Components\TextInput::make('enabled')
                            ->label('Status')
                            ->readOnly()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($state): string {
                                return match ($state) {
                                    1 => 'Enabled',
                                    0 => 'Disabled',
                                    default => $state
                                };
                            })
                            ->prefixIcon(fn($state) => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                            ->prefixIconColor(function ($state) {
                                return match ($state) {
                                    1 => 'success',
                                    0 => 'danger',
                                    default => 'info'
                                };
                            }),

                        Forms\Components\TextInput::make('firstname')
                            ->dehydrated(false)
                            ->readOnly(),

                        Forms\Components\TextInput::make('lastname')
                            ->dehydrated(false)
                            ->readOnly(),

                        Forms\Components\TextInput::make('username')
                            ->dehydrated(false)
                            ->readOnly(),

                        Forms\Components\TextInput::make('email')
                            ->dehydrated(false)
                            ->readOnly(),

                        Forms\Components\TextInput::make('created_at')
                            ->readOnly()
                            ->dehydrated(false)
                            ->label('Registration date'),

                        Forms\Components\TextInput::make('updated_at')
                            ->readOnly()
                            ->dehydrated(false)
                            ->label('Updated date'),

                        Forms\Components\TextInput::make('last_login')
                            ->dehydrated(false)
                            ->readOnly(),

                        Forms\Components\Select::make('membership_type')
                            ->visible(function ($record) {
                                $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize($record->roles))));

                                return str_contains($role, 'ORGANIZER');
                            })
                            ->options([
                                'Comission based' => 'Comission based',
                                'Membership' => 'Membership',
                                'free' => 'Free'
                            ])
                    ])
            ]);
    }

}
