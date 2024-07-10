<?php

namespace App\Filament\Pages;

use App\Models\Organizer;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Validation\ValidationException;

class ScannerSettingPage extends Page
{
    protected static ?string $navigationIcon = 'fas-gears';

    protected static string $view = 'filament.pages.scanner-setting-page';

    protected static ?string $navigationGroup = 'Scanner App';

    protected static ?string $navigationLabel = 'Scanner App Settings';

    protected static ?int $navigationSort = 2;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ORGANIZER');
    }

    public function mount()
    {
        $organizer = Organizer::findOrFail(auth()->user()->organizer_id);

        $this->form->fill([
            'show_event_date_stats_on_scanner_app' => $organizer->show_event_date_stats_on_scanner_app,
            'allow_tap_to_check_in_on_scanner_app' => $organizer->allow_tap_to_check_in_on_scanner_app
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('show_event_date_stats_on_scanner_app')
                    ->label('Show event date stats on the scanner app')
                    ->required()
                    ->helperText('The event date stats (sales and attendance) will be visible on the scanner app')
                    ->boolean(),

                Forms\Components\Radio::make('allow_tap_to_check_in_on_scanner_app')
                    ->label('Allow tap to check in on the scanner app')
                    ->required()
                    ->helperText('Besides the qr code scanning feature, the scanner account will be able to check in the attendees using a list and a button')
                    ->boolean(),
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        $organizer = Organizer::findOrFail(auth()->user()->organizer_id);

        $organizer->update([
            'show_event_date_stats_on_scanner_app' => $this->data['show_event_date_stats_on_scanner_app'],
            'allow_tap_to_check_in_on_scanner_app' => $this->data['allow_tap_to_check_in_on_scanner_app']
        ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
