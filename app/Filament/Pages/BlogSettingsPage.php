<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class BlogSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'fas-signs-post';

    protected static string $view = 'filament.pages.blog-settings-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Blog Settings';

    protected static ?int $navigationSort = 10;

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public function mount(): void
    {
        $this->form->fill([
            'blog_posts_per_page' => Setting::where('key', 'blog_posts_per_page')->first()?->value,
            'blog_comments_enabled' => Setting::where('key', 'blog_comments_enabled')->first()?->value,
            'facebook_app_id' => Setting::where('key', 'facebook_app_id')->first()?->value,
            'disqus_subdomain' => Setting::where('key', 'disqus_subdomain')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('blog_posts_per_page')
                    ->label('Number of blog posts per page')
                    ->required()
                    ->integer(),

                Forms\Components\Radio::make('blog_comments_enabled')
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

        Setting::where('key', 'blog_posts_per_page')->update([
            'value' => $this->data['blog_posts_per_page']
        ]);

        Setting::where('key', 'blog_comments_enabled')->update([
            'value' => $this->data['blog_comments_enabled']
        ]);

        Setting::where('key', 'facebook_app_id')->update([
            'value' => $this->data['facebook_app_id']
        ]);

        Setting::where('key', 'disqus_subdomain')->update([
            'value' => $this->data['disqus_subdomain']
        ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
