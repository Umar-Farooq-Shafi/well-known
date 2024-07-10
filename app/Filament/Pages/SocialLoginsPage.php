<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SocialLoginsPage extends Page
{
    protected static ?string $navigationIcon = 'fas-shapes';

    protected static string $view = 'filament.pages.social-logins-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Social Login';

    protected static ?int $navigationSort = 7;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public function mount(): void
    {
        $this->form->fill([
            'social_login_facebook_enabled' => Setting::query()->where('key', 'social_login_facebook_enabled')->first()?->value,
            'social_login_facebook_id' => Setting::query()->where('key', 'social_login_facebook_id')->first()?->value,
            'social_login_facebook_secret' => Setting::query()->where('key', 'social_login_facebook_secret')->first()?->value,
            'social_login_google_enabled' => Setting::query()->where('key', 'social_login_google_enabled')->first()?->value,
            'social_login_google_id' => Setting::query()->where('key', 'social_login_google_id')->first()?->value,
            'social_login_google_secret' => Setting::query()->where('key', 'social_login_google_secret')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('social_login_facebook_enabled')
                    ->label('Enable Facebook Social Login')
                    ->required()
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ]),

                Forms\Components\TextInput::make('social_login_facebook_id')
                    ->label('Facebook Id'),

                Forms\Components\TextInput::make('social_login_facebook_secret')
                    ->label('Facebook Secret'),

                Forms\Components\Radio::make('social_login_google_enabled')
                    ->label('Enable Google Social Login')
                    ->required()
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ]),

                Forms\Components\TextInput::make('social_login_google_id')
                    ->label('Google Id'),

                Forms\Components\TextInput::make('social_login_google_secret')
                    ->label('Google Secret'),
            ]);
    }

    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'social_login_facebook_enabled')
            ->update([
                'value' => $this->data['social_login_facebook_enabled']
            ]);

        Setting::query()->where('key', 'social_login_facebook_id')
            ->update([
                'value' => $this->data['social_login_facebook_id']
            ]);

        Setting::query()->where('key', 'social_login_facebook_secret')
            ->update([
                'value' => $this->data['social_login_facebook_secret']
            ]);

        Setting::query()->where('key', 'social_login_google_enabled')
            ->update([
                'value' => $this->data['social_login_google_enabled']
            ]);

        Setting::query()->where('key', 'social_login_google_id')
            ->update([
                'value' => $this->data['social_login_google_id']
            ]);

        Setting::query()->where('key', 'social_login_google_secret')
            ->update([
                'value' => $this->data['social_login_google_secret']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
