<?php

namespace App\Filament\Pages;

use App\Enums\Setting as ESetting;
use App\Models\AppLayoutSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Layout extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.layout';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public static function getNavigationLabel(): string
    {
        return 'Layout, Parameter and SEO';
    }

    public function mount()
    {
        $layout = AppLayoutSetting::query()->first();

        $this->form->fill([
            'app_env' => env('APP_ENV', 'development'),
            'debug' => env('APP_DEBUG', false),
            'app_secret' => env('APP_KEY', 'secret'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'date_time_format' => "eee dd MMM y, h:mm a z",
            'date_format' => 'd/m/Y, g:i A T',
            'timezone' => config('app.timezone'),
            'default_language' => App::getLocale(),
            'available_languages' => ['en'],
            'website_name' => env('APP_NAME', 'Laravel'),
            'website_url' => env('APP_URL'),
            'web_description_en' => Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_EN)->first()?->value,
            'web_description_fr' => Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_FR)->first()?->value,
            'web_description_es' => Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_ES)->first()?->value,
            'web_description_ar' => Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_AR)->first()?->value,
            'web_keywords_en' => Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_EN)->first()?->value,
            'web_keywords_fr' => Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_FR)->first()?->value,
            'web_keywords_es' => Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_ES)->first()?->value,
            'web_keywords_ar' => Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_AR)->first()?->value,
            'contact_email' => Setting::query()->where('key', ESetting::CONTACT_EMAIL)->first()?->value,
            'contact_phone' => Setting::query()->where('key', ESetting::CONTACT_PHONE)->first()?->value,
            'facebook_url' => Setting::query()->where('key', ESetting::FACEBOOK_URL)->first()?->value,
            'youtube_url' => Setting::query()->where('key', ESetting::YOUTUBE_URL)->first()?->value,
            'twitter_url' => Setting::query()->where('key', ESetting::TWITTER_URL)->first()?->value,
            'primary_color' => Setting::query()->where('key', ESetting::PRIMARY_COLOR)->first()?->value,
            'primary_email' => Setting::query()->where('key', ESetting::NO_REPLY_EMAIL)->first()?->value,
            'logo' => $layout?->logo_name,
            'logo_original_name' => $layout?->logo_original_name,
            'favicon_name' => $layout?->favicon_name,
            'favicon_original_name' => $layout?->favicon_original_name,
            'show_terms_of_service_page' => Setting::query()->where('key', ESetting::SHOW_TERMS_OF_SERVICE_PAGE)->first()?->value,
            'terms_of_service_page_content' => Setting::query()->where('key', ESetting::TERMS_OF_SERVICE_PAGE_CONTENT)->first()?->value,
            'show_privacy_policy_page' => Setting::query()->where('key', ESetting::SHOW_PRIVACY_POLICY_PAGE)->first()?->value,
            'privacy_policy_page_content' => Setting::query()->where('key', ESetting::PRIVACY_POLICY_PAGE_CONTENT)->first()?->value,
            'show_cookie_policy_page' => Setting::query()->where('key', ESetting::SHOW_COOKIE_POLICY_PAGE)->first()?->value,
            'cookie_policy_page_content' => Setting::query()->where('key', ESetting::COOKIE_POLICY_PAGE_CONTENT)->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Radio::make('app_env')
                ->helperText('Development environment is used for development purposes only')
                ->label('App Environment')
                ->required()
                ->options([
                    'local' => 'Local',
                    'production' => 'Production',
                    'development' => 'Development',
                ]),

            Forms\Components\Radio::make('debug')
                ->helperText('Enable to display stacktraces on error pages or if cache files should be dynamically rebuilt on each request')
                ->label('App Debugging')
                ->required()
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
                    'Enabled',
                    'Disabled',
                ])
                ->required(),

            Forms\Components\TextInput::make('date_time_format')
                ->label('Alternative date and time format')
                ->helperText('Used in some specific cases, follow this link for a list of supported characters: https://www.php.net/manual/en/datetime.format.php')
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
                ->url()
                ->rule('url')
                ->required(),

            Forms\Components\Tabs::make('Website description')
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Website description')
                        ->schema([
                            Forms\Components\TextInput::make('web_description_en')
                                ->required()
                        ]),
                    Forms\Components\Tabs\Tab::make('Description du site Web')
                        ->schema([
                            Forms\Components\TextInput::make('web_description_fr')
                        ]),
                    Forms\Components\Tabs\Tab::make('Descripcion del Sitio Web')
                        ->schema([
                            Forms\Components\TextInput::make('web_description_es')
                        ]),
                    Forms\Components\Tabs\Tab::make('وصف الموقع')
                        ->schema([
                            Forms\Components\TextInput::make('web_description_ar')
                        ]),
                ]),

            Forms\Components\Tabs::make('Website description')
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Website keywords')
                        ->schema([
                            Forms\Components\TextInput::make('web_keywords_en')
                                ->required()
                        ]),
                    Forms\Components\Tabs\Tab::make('Mots-clés du site Web')
                        ->schema([
                            Forms\Components\TextInput::make('website_keywords_fr')
                        ]),
                    Forms\Components\Tabs\Tab::make('Palabras clave del sitio web')
                        ->schema([
                            Forms\Components\TextInput::make('website_keywords_es')
                        ]),
                    Forms\Components\Tabs\Tab::make('الكلمات الرئيسية لموقع الويب')
                        ->schema([
                            Forms\Components\TextInput::make('website_keywords_ar')
                        ]),
                ]),

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
                ->disk('public')
                ->directory('layout')
                ->required()
                ->formatStateUsing(fn ($state) => $state ? ['layout/' . $state] : null)
                ->visibility('public')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                ->storeFileNamesIn('logo_original_name')
                ->helperText('Please choose a 200x50 image size to ensure compatibility with the website design'),

            Forms\Components\FileUpload::make('favicon_name')
                ->disk('public')
                ->formatStateUsing(fn ($state) => $state ? ['layout/' . $state] : null)
                ->directory('layout')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                ->storeFileNamesIn('favicon_original_name')
                ->label('Favicon'),

            Forms\Components\Radio::make('show_terms_of_service_page')
                ->label('Show the terms of service page link')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No'
                ])
                ->required(),

            Forms\Components\Select::make('terms_of_service_page_content')
                ->label('Terms of service page slug')
                ->required()
                ->options([
                    'about_us_page_content' => 'About us',
                    'payment_delivery_and_return_page_content' => 'Payment, delivery and return',
                    'privacy_policy_page_content' => 'Privacy policy',
                    'sell_tickets_page_content' => 'Sell tickets',
                    'terms_of_service_page_content' => 'Terms of service',
                ]),

            Forms\Components\Radio::make('show_privacy_policy_page')
                ->label('Show the privacy policy page link')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No'
                ])
                ->required(),

            Forms\Components\Select::make('privacy_policy_page_content')
                ->label('Privacy policy page slug')
                ->options([
                    'about_us_page_content' => 'About us',
                    'payment_delivery_and_return_page_content' => 'Payment, delivery and return',
                    'privacy_policy_page_content' => 'Privacy policy',
                    'sell_tickets_page_content' => 'Sell tickets',
                    'terms_of_service_page_content' => 'Terms of service',
                ])
                ->required(),

            Forms\Components\Radio::make('show_cookie_policy_page')
                ->label('Show the cookie policy page link')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No'
                ])
                ->required(),

            Forms\Components\Select::make('cookie_policy_page_content')
                ->label('Cookie policy page slug')
                ->options([
                    'about_us_page_content' => 'About us',
                    'payment_delivery_and_return_page_content' => 'Payment, delivery and return',
                    'privacy_policy_page_content' => 'Privacy policy',
                    'sell_tickets_page_content' => 'Sell tickets',
                    'terms_of_service_page_content' => 'Terms of service',
                ])
        ];
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();
        $logo = head($this->data['logo']);
        $originalName = $this->data['logo_original_name'];

        if ($logo instanceof TemporaryUploadedFile) {
            $img = Str::ulid() . '.' . $logo->getClientOriginalExtension();

            $logo->storeAs('layout', $img, 'public');

            $originalName = $logo->getClientOriginalName();
        } else {
            $img = last(explode('/', $logo));
        }

        $layout = AppLayoutSetting::query()->first();

        $size = Storage::disk('public')->size("layout/" . $img);
        $mimetype = File::mimeType(Storage::disk('public')->path("layout/" . $img));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path("layout/" . $img));

        $layout->update([
            'logo_name' => $img,
            'logo_size' => $size,
            'logo_mime_type' => $mimetype,
            'logo_original_name' => $originalName,
            'logo_dimensions' => $image->width() . "," . $image->height()
        ]);

        if (count($this->data['favicon_name'])) {
            $logo = head($this->data['favicon_name']);
            $originalName = $this->data['favicon_original_name'];

            if ($logo instanceof TemporaryUploadedFile) {
                $img = Str::ulid() . '.' . $logo->getClientOriginalExtension();

                $logo->storeAs('layout', $img, 'public');

                $originalName = $logo->getClientOriginalName();
            } else {
                $img = last(explode('/', $logo));
            }

            $size = Storage::disk('public')->size("layout/" . $img);
            $mimetype = File::mimeType(Storage::disk('public')->path("layout/" . $img));

            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path("layout/" . $img));

            $layout->update([
                'favicon_name' => $img,
                'favicon_size' => $size,
                'favicon_mime_type' => $mimetype,
                'favicon_original_name' => $originalName,
                'favicon_dimensions' => $image->width() . "," . $image->height()
            ]);
        }

        $envPath = base_path('.env');

        $envContent = file_get_contents($envPath);

        $escapedWebsiteName = '"' . addslashes($this->data['website_name']) . '"';

        $envContent = preg_replace('/^APP_URL=.*/m', 'APP_URL=' . $this->data['website_url'], $envContent);
        $envContent = preg_replace('/^APP_NAME=.*/m', 'APP_NAME=' . $escapedWebsiteName, $envContent);
        $envContent = preg_replace('/^APP_ENV=.*/m', 'APP_ENV=' . $this->data['app_env'], $envContent);
        $envContent = preg_replace('/^APP_DEBUG=.*/m', 'APP_DEBUG=' . $this->data['debug'], $envContent);
        $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $this->data['app_secret'], $envContent);

        file_put_contents($envPath, $envContent);

        Setting::query()->where('key', ESetting::APP_ENVIRONMENT)->update(['value' => $this->data['app_env']]);

        Setting::query()->where('key', ESetting::MAINTENANCE_MODE)->update(['value' => $this->data['maintenance_mode']]);

        if ($this->data['maintenance_mode']) {
            Artisan::call('down');
        } else {
            Artisan::call('up');
        }

        Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_EN)->update(['value' => $this->data['web_description_en']]);
        Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_FR)->update(['value' => $this->data['web_description_fr']]);
        Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_ES)->update(['value' => $this->data['web_description_es']]);
        Setting::query()->where('key', ESetting::WEBSITE_DESCRIPTION_AR)->update(['value' => $this->data['web_description_ar']]);
        Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_EN)->update(['value' => $this->data['web_keywords_en']]);
        Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_FR)->update(['value' => $this->data['web_keywords_fr']]);
        Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_ES)->update(['value' => $this->data['web_keywords_es']]);
        Setting::query()->where('key', ESetting::WEBSITE_KEYWORDS_AR)->update(['value' => $this->data['web_keywords_ar']]);
        Setting::query()->where('key', ESetting::CONTACT_EMAIL)->update(['value' => $this->data['contact_email']]);
        Setting::query()->where('key', ESetting::CONTACT_PHONE)->update(['value' => $this->data['contact_phone']]);
        Setting::query()->where('key', ESetting::FACEBOOK_URL)->update(['value' => $this->data['facebook_url']]);
        Setting::query()->where('key', ESetting::YOUTUBE_URL)->update(['value' => $this->data['youtube_url']]);
        Setting::query()->where('key', ESetting::TWITTER_URL)->update(['value' => $this->data['twitter_url']]);
        Setting::query()->where('key', ESetting::PRIMARY_COLOR)->update(['value' => $this->data['primary_color']]);
        Setting::query()->where('key', ESetting::NO_REPLY_EMAIL)->update(['value' => $this->data['primary_email']]);
        Setting::query()->where('key', ESetting::SHOW_TERMS_OF_SERVICE_PAGE)->update(['value' => $this->data['show_terms_of_service_page']]);
        Setting::query()->where('key', ESetting::TERMS_OF_SERVICE_PAGE_CONTENT)->update(['value' => $this->data['terms_of_service_page_content']]);
        Setting::query()->where('key', ESetting::SHOW_PRIVACY_POLICY_PAGE)->update(['value' => $this->data['show_privacy_policy_page']]);
        Setting::query()->where('key', ESetting::PRIVACY_POLICY_PAGE_CONTENT)->update(['value' => $this->data['privacy_policy_page_content']]);
        Setting::query()->where('key', ESetting::SHOW_COOKIE_POLICY_PAGE)->update(['value' => $this->data['show_cookie_policy_page']]);
        Setting::query()->where('key', ESetting::COOKIE_POLICY_PAGE_CONTENT)->update(['value' => $this->data['cookie_policy_page_content']]);

        Carbon::setToStringFormat($this->data['date_time_format']);
        \app()->setLocale($this->data['default_language']);

        date_default_timezone_set($this->data['timezone']);

//        [
//            'date_time_format' => "eee dd MMM y, h:mm a z",
//            'date_format' => 'd/m/Y, g:i A T',
//        ]

        Artisan::call('optimize:clear');
        Artisan::call('optimize');

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
