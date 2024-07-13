<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use app\Filament\Pages\DashboardEvents;
use App\Http\Middleware\SuperAdminMiddleware;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    // https://filamentphp.com/docs/3.x/panels/configuration
    // https://filamentphp.com/docs/3.x/panels/themes
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('/app')
            ->spa()
            ->unsavedChangesAlerts()
            ->brandName('ibento')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->viteTheme('resources/css/filament/app/theme.css')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
//                Pages\Dashboard::class,
                DashboardEvents::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->topNavigation()
            ->userMenuItems([
                MenuItem::make()->label('Edit profile')
                    ->url(fn(): string => EditProfile::getUrl()),
            ])
            ->navigationItems([
                NavigationItem::make('Rules')
                    ->url('/rules', shouldOpenInNewTab: true)
                    ->icon('heroicon-m-scale')
                    ->sort(3),
            ])
            // https://filamentphp.com/docs/3.x/panels/users
            ->profile(EditProfile::class);
//            ->emailVerification();

//            ->userMenuItems([
//                MenuItem::make()
//                    ->label('Settings')
//                    ->url('profile')
//                    ->icon('heroicon-o-cog-6-tooth'),
//                // ...
//            ]);
//            ->sidebarCollapsibleOnDesktop();
    }
}
