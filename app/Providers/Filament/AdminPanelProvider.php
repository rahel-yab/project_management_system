<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\MyAssignedTasksWidget;
use App\Filament\Widgets\MyNotificationsWidget;
use App\Filament\Widgets\ProjectStats;
use App\Filament\Widgets\RecentCommentsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Violet,
            ])
            ->maxContentWidth(MaxWidth::Full)
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(<<<'HTML'
<style>
    :root {
        --fi-color-primary-50: 245 241 255;
        --fi-color-primary-100: 233 224 255;
        --fi-color-primary-200: 214 196 255;
        --fi-color-primary-300: 190 163 247;
        --fi-color-primary-400: 164 130 237;
        --fi-color-primary-500: 140 104 220;
        --fi-color-primary-600: 121 86 199;
        --fi-color-primary-700: 103 73 172;
        --fi-color-primary-800: 85 61 141;
        --fi-color-primary-900: 68 49 113;
        --fi-color-primary-950: 43 30 72;
    }

    html,
    body,
    .fi-layout,
    .fi-main,
    .fi-main-ctn {
        background-color: #fcfaff !important;
    }

    .fi-body,
    .fi-main-ctn {
        background-image:
            radial-gradient(circle at 0% 0%, color-mix(in oklab, var(--fi-color-primary-500) 20%, transparent), transparent 42%),
            radial-gradient(circle at 100% 100%, color-mix(in oklab, var(--fi-color-primary-500) 16%, transparent), transparent 48%);
    }

    .fi-main-ctn .fi-wi,
    .fi-main-ctn .fi-section,
    .fi-main-ctn .fi-ta,
    .fi-topbar {
        background-color: color-mix(in oklab, var(--fi-color-primary-500) 6%, white) !important;
    }

    .fi-ta {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
    }

    .fi-ta-header,
    .fi-ta-header-toolbar,
    .fi-ta-content,
    .fi-ta-ctn {
            background-color: #ffffff !important;
    }

    .fi-ta table thead,
    .fi-ta table thead tr,
    .fi-ta table thead th {
            background-color: #fafafa !important;
    }

    .fi-ta table tbody tr {
            background-color: #ffffff !important;
            border-color: #f1f5f9 !important;
    }

    .fi-ta table tbody tr:nth-child(even) {
            background-color: #fdfdfd !important;
    }

    .fi-sidebar {
        backdrop-filter: blur(5px);
    }

    .fi-main {
        padding-top: .5rem;
    }

    .fi-wi,
    .fi-section,
    .fi-ta {
        border-radius: .75rem;
        border: 1px solid color-mix(in oklab, var(--fi-color-gray-400) 16%, transparent);
        transition: border-color .2s ease;
    }

    .fi-wi:hover,
    .fi-section:hover {
        border-color: color-mix(in oklab, var(--fi-color-primary-500) 24%, transparent);
    }

    .fi-ta-header-toolbar {
        border-bottom: 1px solid color-mix(in oklab, var(--fi-color-gray-400) 18%, transparent);
    }

    .fi-ta-row:hover {
            background-color: #f8fafc !important;
    }

    .fi-btn {
        border-radius: .6rem;
    }
</style>
HTML),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                ProjectStats::class,
                MyNotificationsWidget::class,
                MyAssignedTasksWidget::class,
                RecentCommentsWidget::class,
                Widgets\AccountWidget::class,
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
            ]);
    }
}
