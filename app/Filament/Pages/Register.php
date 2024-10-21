<?php

namespace App\Filament\Pages;

use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;
use App\Models\Setting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Exception;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as AuthRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class Register extends AuthRegister
{
    protected static string $view = 'filament.pages.auth.register';

    /**
     * @throws Exception
     */
    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $userExists = $this->getUserModel()::where('guest', false)->where('email', $data['email'])->exists();

        if ($userExists) {
            Notification::make()
                ->title(__('User already exists'))
                ->body(__('A user with this email address is already registered. Please log in or use a different email.'))
                ->danger()
                ->send();

            return null;
        }

        $user = DB::transaction(fn () => $this->getUserModel()::create([
            ...$data,
            'username_canonical' => $data['username'],
            'email_canonical' => $data['email'] . '-guest',
            'enabled' => 1,
            'roles' => 'a:1:{i:0;s:13:"' . $data['roles'] . '";}',
            'slug' => Str::slug($data['username']),
        ]));

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getRoleFormComponent(),
                        $this->getOrganizerComponent(),
                        $this->getFirstNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getPhoneComponent(),
                        $this->getUsernameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRecaptchaFormComponent()
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->maxLength(255)
            ->required();
    }

    protected function getUsernameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('username')
            ->required()
            ->maxLength(255);
    }

    protected function getRoleFormComponent(): Forms\Components\ToggleButtons
    {
        return Forms\Components\ToggleButtons::make('roles')
            ->label('')
            ->icons(function ($state) {
                if ($state === 'ROLE_ATTENDEE') {
                    return [
                        'ROLE_ATTENDEE' => 'heroicon-o-check',
                    ];
                }

                return [
                    'ROLE_ORGANIZER' => 'heroicon-o-check',
                ];
            })
            ->live()
            ->extraAttributes([
                'class' => 'w-full'
            ])
            ->grouped()
            ->columnSpanFull()
            ->default('ROLE_ATTENDEE')
            ->options([
                'ROLE_ATTENDEE' => 'Attendee',
                'ROLE_ORGANIZER' => 'Organizer',
            ]);
    }

    protected function getOrganizerComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('organizer_name')
            ->required()
            ->visible(fn(Forms\Get $get) => $get('roles') === 'ROLE_ORGANIZER')
            ->maxLength(255);
    }

    protected function getFirstNameFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('firstname')
            ->required()
            ->maxLength(255);
    }

    protected function getLastNameFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('lastname')
            ->required()
            ->maxLength(255);
    }

    protected function getPhoneComponent(): PhoneInput
    {
        return PhoneInput::make('phone')
            ->required();
    }

    protected function getRecaptchaFormComponent(): GRecaptcha
    {
        $enable = Setting::query()->where('key', 'google_recaptcha_enabled')->first()?->value;

        return GRecaptcha::make('captcha')
            ->visible($enable);
    }

}
