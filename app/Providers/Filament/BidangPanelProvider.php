<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
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
use App\Http\Middleware\RoleMiddleware;

class BidangPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('bidang')
            ->path('/bidang')
            ->domain(env('FILAMENT_DOMAIN'))
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Bidang/Resources'), for: 'App\\Filament\\Bidang\\Resources')
            ->discoverPages(in: app_path('Filament/Bidang/Pages'), for: 'App\\Filament\\Bidang\\Pages')
            ->discoverWidgets(in: app_path('Filament/Bidang/Widgets'), for: 'App\\Filament\\Bidang\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Bidang\Widgets\SopPerStatusBidangChart::class,
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
                RoleMiddleware::class . ':bidang',
            ])
            ->brandName('SI-SOP Bidang')
            ->favicon(asset('images/favicon.ico'))
            ->navigationGroups([
                'Manajemen SOP',
                'Manajemen Survei',
                'Notifikasi & Komunikasi',
                'Pengaturan',
            ]);
    }
}
