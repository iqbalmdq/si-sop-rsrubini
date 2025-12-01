<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RoleMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class DirekturPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('direktur')
            ->path('/direktur')
            ->domain(env('FILAMENT_DOMAIN'))
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Direktur/Resources'), for: 'App\\Filament\\Direktur\\Resources')
            ->discoverPages(in: app_path('Filament/Direktur/Pages'), for: 'App\\Filament\\Direktur\\Pages')
            ->discoverWidgets(in: app_path('Filament/Direktur/Widgets'), for: 'App\\Filament\\Direktur\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Direktur\Widgets\SurveyStatsWidget::class,
                \App\Filament\Direktur\Widgets\SurveyResponseChart::class,
                \App\Filament\Direktur\Widgets\SurveyByBidangChart::class,
                \App\Filament\Bidang\Widgets\NotifikasiTerbaruWidget::class,
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
                RoleMiddleware::class.':direktur',
            ])
            ->brandName('SI-DOK Direktur')
            ->favicon(asset('images/favicon.ico'))
            ->navigationGroups([
                'Dasbor & Analisis',
                'Manajemen Survei',
                'Manajemen Akun',
                'Notifikasi',
            ]);
    }
}
