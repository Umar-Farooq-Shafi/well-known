<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Validation\ValidationException;

class NewsletterPage extends Page
{
    protected static ?string $navigationIcon = 'fas-newspaper';

    protected static string $view = 'filament.pages.newsletter-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Newsletter';

    protected static ?int $navigationSort = 12;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public function mount(): void
    {
        $this->form->fill([
            'newsletter_enabled' => Setting::where('key', 'newsletter_enabled')->first()?->value,
            'mailchimp_api_key' => Setting::where('key', 'mailchimp_api_key')->first()?->value,
            'mailchimp_list_id' => Setting::where('key', 'mailchimp_list_id')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('newsletter_enabled')
                    ->label('Enable newsletter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->helperText('SSL must be activated on your hosting server in order to use Mailchimp')
                    ->required(),

                Forms\Components\TextInput::make('mailchimp_api_key')
                    ->label('Mailchimp app id')
                    ->helperText('Go to the documentation to get help about getting an api key'),

                Forms\Components\TextInput::make('mailchimp_list_id')
                    ->label('Mailchimp list id')
                    ->helperText('Go to the documentation to get help about getting a list id')
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        Setting::where('key', 'newsletter_enabled')->update(['value' => $this->data['newsletter_enabled']]);
        Setting::where('key', 'mailchimp_api_key')->update(['value' => $this->data['mailchimp_api_key']]);
        Setting::where('key', 'mailchimp_list_id')->update(['value' => $this->data['mailchimp_list_id']]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
