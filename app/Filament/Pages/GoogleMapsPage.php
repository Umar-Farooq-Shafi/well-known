<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;

class GoogleMapsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static string $view = 'filament.pages.google-maps-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Google Maps';

    protected static ?int $navigationSort = 6;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([]);
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

    public function submit(): void
    {
        $this->validate();

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
