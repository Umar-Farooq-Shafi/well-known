<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GoogleRecaptchaPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.google-recaptcha-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Google Recaptcha';

    protected static ?int $navigationSort = 5;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'google_recaptcha_enabled' => Setting::query()->where('key', 'google_recaptcha_enabled')->first()?->value,
            'google_recaptcha_site_key' => Setting::query()->where('key', 'google_recaptcha_site_key')->first()?->value,
            'google_recaptcha_secret_key' => Setting::query()->where('key', 'google_recaptcha_secret_key')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('google_recaptcha_enabled')
                    ->label('Enable Google Repatcha')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('google_recaptcha_site_key')
                    ->label('Site Key')
                    ->required(),

                Forms\Components\TextInput::make('google_recaptcha_secret_key')
                    ->label('Secret Key')
                    ->required()
            ]);
    }

    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'google_recaptcha_enabled')
            ->update([
                'value' => $this->data['google_recaptcha_enabled']
            ]);

        Setting::query()->where('key', 'google_recaptcha_site_key')
            ->update([
                'value' => $this->data['google_recaptcha_site_key']
            ]);

        Setting::query()->where('key', 'google_recaptcha_secret_key')
            ->update([
                'value' => $this->data['google_recaptcha_secret_key']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
