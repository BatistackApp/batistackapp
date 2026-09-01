<?php

namespace App\Providers\Filament;

use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Islamv\FilamentSettingsPlugin\FilamentSettingsPlugin;
use MKWebDesign\FilamentWatchdog\FilamentWatchdogPlugin;
use Prodstarter\FilamentNotificationCenter\FilamentNotificationCenterPlugin;
use Prodstarter\FilamentNotificationCenter\NotificationCenterCategory;
use ToneGabes\Filament\Icons\Enums\Phosphor;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->plugins([
                FilamentSettingsPlugin::make(),
                FilamentWatchdogPlugin::make(),
                FilamentNotificationCenterPlugin::make()
                    ->categories(self::dataCategoriesNotification())
                    ->defaultCategory('general')
                    ->emptyStateUsing(fn (string $categoryId): array => [
                        'heading' => "Nothing here yet",
                        'description' => "You're all caught up in {$categoryId}.",
                    ]),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    private static function dataCategoriesNotification(): array
    {
        return [
            NotificationCenterCategory::make('tiers')
                ->label('Tiers')
                ->icon(Phosphor::Users)
                ->color(Color::Teal)
                ->order(1),

            NotificationCenterCategory::make('chantiers')
                ->label('Chantiers')
                ->icon(Phosphor::HardHat)
                ->color(Color::Orange)
                ->order(2),

            NotificationCenterCategory::make('articles')
                ->label('Articles')
                ->icon(Phosphor::BoxArrowUp)
                ->color(Color::Amber)
                ->order(3),

            NotificationCenterCategory::make('commerces')
                ->label('Commerces')
                ->icon(Phosphor::ShoppingBag)
                ->color(Color::Blue)
                ->order(4),

            NotificationCenterCategory::make('rh')
                ->label('RH')
                ->icon(Phosphor::UserSquare)
                ->color(Color::Indigo)
                ->order(5),

            NotificationCenterCategory::make('flottes')
                ->label('Flottes')
                ->icon(Phosphor::Truck)
                ->color(Color::Gray)
                ->order(6),

            NotificationCenterCategory::make('ateliers')
                ->label('Ateliers')
                ->icon(Phosphor::Factory)
                ->color(Color::Amber)
                ->order(7),
        ];
    }
}
