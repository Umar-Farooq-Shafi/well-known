<?php

namespace App\Providers;

use App\Listeners\UserLoginAt;
use App\Models\Setting;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(PanelsRenderHook::BODY_END, static fn() => '<x-impersonate::banner/>');

        if (Schema::hasTable('eventic_settings')) {
            $key = Setting::query()->where('key', 'google_recaptcha_site_key')->first()?->value;
            $secret = Setting::query()->where('key', 'google_recaptcha_secret_key')->first()?->value;

            if (empty(env('NOCAPTCHA_SECRET'))) {
                file_put_contents(app()->environmentFilePath(), str_replace(
                    'NOCAPTCHA_SECRET=' . env('NOCAPTCHA_SECRET'),
                    'NOCAPTCHA_SECRET=' . $secret,
                    file_get_contents(app()->environmentFilePath())
                ));
            }

            if (empty(env('NOCAPTCHA_SITEKEY'))) {
                file_put_contents(app()->environmentFilePath(), str_replace(
                    'NOCAPTCHA_SITEKEY=' . env('NOCAPTCHA_SITEKEY'),
                    'NOCAPTCHA_SITEKEY=' . $key,
                    file_get_contents(app()->environmentFilePath())
                ));
            }
        }

        Event::listen(
            Login::class,
            UserLoginAt::class
        );

    }

}
