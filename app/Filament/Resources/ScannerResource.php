<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScannerResource\Pages;
use App\Models\Scanner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class ScannerResource extends Resource
{
    protected static ?string $model = Scanner::class;

    protected static ?string $navigationIcon = 'fas-qrcode';

    protected static ?string $navigationGroup = 'Scanner App';

    protected static ?string $navigationLabel = 'My Scanners';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ORGANIZER');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('username')
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->autocomplete('new-password')
                    ->required(fn(string $context): bool => $context === 'create'),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->same('password')
                    ->label('Confirm Password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
                    ->sortable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.created_at')
                    ->label('Creation date')
                    ->sortable()
                    ->date(),

                Tables\Columns\TextColumn::make('last_login')
                    ->label('Last login')
                    ->date(),

                Tables\Columns\TextColumn::make('user.enabled')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Enabled' : 'Disabled')
                    ->color(fn($state) => $state ? 'primary' : 'danger')
                    ->icon(fn($state) => $state ? 'fas-user-plus' : 'fas-user-times')
                    ->badge(),
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
            ->where('organizer_id', auth()->user()->organizer_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScanners::route('/'),
            'create' => Pages\CreateScanner::route('/create'),
            'edit' => Pages\EditScanner::route('/{record}/edit'),
        ];
    }
}
