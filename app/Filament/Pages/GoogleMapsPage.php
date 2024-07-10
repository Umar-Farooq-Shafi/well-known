<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Validation\ValidationException;

class GoogleMapsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static string $view = 'filament.pages.google-maps-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Google Maps';

    protected static ?int $navigationSort = 6;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public function mount(): void
    {
        $this->form->fill([
            'google_map_api_key' => env('GOOGLE_MAPS_API_KEY')
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('google_map_api_key')
                    ->label('Google Maps Api Key')
                    ->helperText('Leave api key empty to disable google maps project wide')
                    ->integer()
                    ->required(),
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        putenv('GOOGLE_MAPS_API_KEY=' . $this->data['google_map_api_key']);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
