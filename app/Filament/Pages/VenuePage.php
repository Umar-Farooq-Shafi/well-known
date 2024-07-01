<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class VenuePage extends Page
{
    protected static ?string $navigationIcon = 'fas-map-marked';

    protected static string $view = 'filament.pages.venue-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Venue page';

    protected static ?int $navigationSort = 9;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'venue_comments_enabled' => Setting::query()->where('key', 'venue_comments_enabled')->first()?->value,
            'facebook_app_id' => Setting::query()->where('key', 'facebook_app_id')->first()?->value,
            'disqus_subdomain' => Setting::query()->where('key', 'disqus_subdomain')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('venue_comments_enabled')
                    ->label('Enable comments')
                    ->inlineLabel()
                    ->options([
                        'no' => 'No',
                        'facebook_comments' => 'Facebook comments',
                        'disqus_comments' => 'Disqus comments'
                    ])
                    ->required(),

                Forms\Components\TextInput::make('facebook_app_id')
                    ->label('Facebook App ID')
                    ->helperText('Go to the documentation to get help about getting an app ID'),

                Forms\Components\TextInput::make('disqus_subdomain')
                    ->label('Disqus Subdomain')
                    ->helperText('Go to the documentation to get help about setting up Disqus'),
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'venue_comments_enabled')
            ->update([
                'value' => $this->data['venue_comments_enabled']
            ]);

        Setting::query()->where('key', 'facebook_app_id')
            ->update([
                'value' => $this->data['facebook_app_id']
            ]);

        Setting::query()->where('key', 'disqus_subdomain')
            ->update([
                'disqus_subdomain' => $this->data['facebook_app_id']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
