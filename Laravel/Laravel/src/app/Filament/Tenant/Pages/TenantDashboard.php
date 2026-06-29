<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Models\Room;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class TenantDashboard extends Page
{
    protected string $view = 'filament.tenant.pages.tenant-dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?string $slug = '/';

    public User $user;
    public ?Room $room = null;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::Home;
    }

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->room = $this->user->room;
    }
}
