<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make('Dashboard')
                    ->url('/dashboard')
                    ->icon('heroicon-o-home'),
                NavigationItem::make('Events')
                    ->url('/events')
                    ->icon('heroicon-o-home'),

            ]);
        });
    }
}
