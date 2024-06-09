<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Layout extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.layout';

    protected static ?string $navigationGroup = 'Settings';

    public ?array $data = [
        'app_env' => 'required',
        'debug' => 'required',
    ];

    public static function getNavigationLabel(): string
    {
        return 'Layout, Parameter and SEO';
    }

    public function mount()
    {
        $this->form->fill([
            'app_env' => env('APP_ENV', 'development'),
            'debug' => env('APP_DEBUG', false),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Radio::make('app_env')
                ->helperText('Development environment is used for development purposes only')
                ->label('App Environment')
                ->required()
                ->options([
                    'production' => 'Production',
                    'development' => 'Development',
                ]),

            Forms\Components\Radio::make('debug')
                ->helperText('Enable to display stacktraces on error pages or if cache files should be dynamically rebuilt on each request')
                ->label('App Debugging')
                ->required()
                ->default(env('APP_DEBUG', false))
                ->boolean(),

            Forms\Components\TextInput::make('app_secret')
                ->label('App Secret')
                ->helperText('This is a string that should be unique to your application and it is commonly used to add more entropy to security related operations')
                ->required(),

            Forms\Components\Radio::make('maintenance_mode')
                ->label('Maintenance mode')
                ->helperText('Enable maintenance mode to display a maintenance page for all users but the users who are granted the ROLE_ADMINISTRATOR role, if you lost your session, you can edit the MAINTENANCE_MODE parameter directly in the .env file')
                ->boolean()
                ->options([
                    'enabled' => 'Enabled',
                    'disabled' => 'Disabled',
                ])
                ->required(),

            Forms\Components\Textarea::make('maintenance_message')
                ->label('Maintenance mode custom message'),

            Forms\Components\TextInput::make('date_time_format')
                ->label('Alternative date and time format')
                ->helperText('Used in some specific cases, follow this link for a list of supported characters: https://www.php.net/manual/en/datetime.format.php . Please make sure to keep the double quotes " " around the format string')
                ->required(),

            Forms\Components\TextInput::make('date_format')
                ->label('Date only format')
                ->helperText('Used in some specific cases, follow this link for a list of supported characters: https://www.php.net/manual/en/datetime.format.php . Please make sure to keep the double quotes " " around the format string')
                ->required(),

            Forms\Components\Select::make('timezone')
                ->label('Timezone')
                ->required()
                ->searchable()
                ->options(config('custom.timezones'))
                ->required(),

            Forms\Components\Select::make('default_language')
                ->label('Default language')
                ->required()
                ->options([
                    'en' => 'English',
                    'ar' => 'Arabic',
                    'fr' => 'French',
                    'es' => 'Spanish',
                ]),

            Forms\Components\CheckboxList::make('available_languages')
                ->label('Available languages')
                ->required()
                ->options([
                    'en' => 'English',
                    'ar' => 'Arabic',
                    'fr' => 'French',
                    'es' => 'Spanish',
                ]),

            Forms\Components\TextInput::make('website_name')
                ->label('Website name')
                ->required(),

            Forms\Components\TextInput::make('website_slug')
                ->label('Website slug')
                ->helperText('Enter the chosen website name with no spaces and no uppercase characters (for SEO purposes)')
                ->required(),

            Forms\Components\TextInput::make('website_url')
                ->label('Website URL')
                ->required(),

            Forms\Components\TextInput::make('website_description')
                ->label('Website description')
                ->required(),

            Forms\Components\TextInput::make('website_keywords')
                ->label('Website keywords')
                ->required(),

            Forms\Components\TextInput::make('contact_email')
                ->label('Contact email')
                ->required(),

            Forms\Components\TextInput::make('contact_phone')
                ->label('Contact phone'),

            Forms\Components\TextInput::make('contact_address')
                ->label('Contact address'),

            Forms\Components\TextInput::make('facebook_url')
                ->label('Facebook URL'),

            Forms\Components\TextInput::make('youtube_url')
                ->label('Youtube URL'),

            Forms\Components\TextInput::make('twitter_url')
                ->label('Twitter URL'),

            Forms\Components\ColorPicker::make('primary_color')
                ->label('Primary color')
                ->required(),

            Forms\Components\TextInput::make('primary_email')
                ->label('No reply email address')
                ->required()
                ->email(),

            Forms\Components\FileUpload::make('logo')
                ->label('Logo')
                ->helperText('Please choose a 200x50 image size to ensure compatibility with the website design'),

            Forms\Components\FileUpload::make('favicon')
                ->label('Favicon'),

            Forms\Components\Radio::make('show_term')
                ->label('Show the terms of service page link')
                ->boolean()
                ->required(),

            Forms\Components\Select::make('term_slug')
                ->label('Terms of service page slug')
                ->required()
                ->options([
                    'about_us' => 'About us',
                    'payment_delivery_and_return' => 'Payment, delivery and return',
                    'privacy_policy' => 'Privacy policy',
                    'sell_tickets' => 'Sell tickets',
                    'terms_of_service' => 'Terms of service',
                ]),

            Forms\Components\Radio::make('privacy_policy_page')
                ->label('Show the privacy policy page link')
                ->boolean()
                ->required(),

            Forms\Components\Select::make('privacy_policy_slug')
                ->label('Privacy policy page slug')
                ->options([
                    'about_us' => 'About us',
                    'payment_delivery_and_return' => 'Payment, delivery and return',
                    'privacy_policy' => 'Privacy policy',
                    'sell_tickets' => 'Sell tickets',
                    'terms_of_service' => 'Terms of service',
                ])
                ->required(),

            Forms\Components\Radio::make('cookies_page')
                ->label('Show the cookie policy page link')
                ->boolean()
                ->required(),

            Forms\Components\Select::make('cookies_slug')
                ->label('Cookie policy page slug')
                ->options([
                    'about_us' => 'About us',
                    'payment_delivery_and_return' => 'Payment, delivery and return',
                    'privacy_policy' => 'Privacy policy',
                    'sell_tickets' => 'Sell tickets',
                    'terms_of_service' => 'Terms of service',
                ])
        ];
    }

    public function submit()
    {
        $this->validate();

        // SAVE THE SETTINGS HERE
    }

}
