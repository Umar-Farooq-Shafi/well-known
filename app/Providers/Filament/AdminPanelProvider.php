<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Login;
use App\Filament\Pages\Profile;
use App\Filament\Pages\Register;
use App\Models\AppLayoutSetting;
use App\Filament\Widgets;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Sitemap\SitemapGenerator;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration(Register::class)
            ->colors([
                'primary' => Color::Sky,
            ])
            ->profile(Profile::class)
            ->passwordReset()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\EventsOverview::class,
                Widgets\EventsTableOverview::class,
                Widgets\OrdersOverview::class,
                Widgets\TicketsOverview::class,
                Widgets\OrdersTableOverview::class,
                Widgets\UsersOverview::class,
                Widgets\UsersTableOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->brandLogo(function () {
                $layout = AppLayoutSetting::query()->first();

                return $layout->logo_name ? Storage::disk('public')->url('layout/' . $layout->logo_name) : null;
            })
            ->navigationGroups([
                'Settings',
                'Events',
                'Venues',
                'Blog'
            ])
            ->navigationItems([
                NavigationItem::make('Sitemap')
                    ->group('Settings')
                    ->sort(13)
                    ->visible(function (): bool {
                        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

                        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
                    })
                    ->url(function () {
                        $filename = 'sitemap.xml';
                        $path = public_path($filename);

                        if (file_exists($path)) {
                            return asset($filename);
                        }

                        SitemapGenerator::create(env('APP_URL'))->writeToFile($path);

                        return asset($filename);
                    }, true)
                    ->icon('fas-sitemap')
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
