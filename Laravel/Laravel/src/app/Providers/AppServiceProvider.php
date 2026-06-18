<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\ActivityPolicy;
use Filament\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;

final class AppServiceProvider extends ServiceProvider
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
        // ============ POLICIES ============
        Gate::policy(Activity::class, ActivityPolicy::class);

        // ============ FILAMENT UI ============
        Page::formActionsAlignment(Alignment::Right);
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);
        Page::$reportValidationErrorUsing = function (ValidationException $exception): void {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        // ============================================================
        // REDIRECT PENYEWA SETELAH LOGIN
        // ============================================================
        // DIPINDAHKAN KE ROUTE DAN MIDDLEWARE (bukan di sini)
        // Lihat routes/web.php dan app/Http/Middleware/RedirectIfPenyewa.php
        // ============================================================
    }
}