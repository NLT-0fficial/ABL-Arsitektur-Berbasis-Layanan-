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

    public ?string $qrAction    = null; // 'masuk' | 'keluar'
    public ?string $qrImageUrl  = null;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::Home;
    }

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->room = $this->user->room;
    }

    public function generateQr(string $action): void
    {
        if (! $this->room || ! in_array($action, ['masuk', 'keluar'], true)) {
            return;
        }

        $this->qrAction = $action;

        // payload: token|action -> di-encode supaya QR Scanner admin
        // bisa tahu kamar mana + niat aksinya
        $payload = $this->room->qr_token . '|' . $action;
        $encoded = base64_encode($payload);

        $this->qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
            . urlencode($encoded);
    }

    public function resetQr(): void
    {
        $this->qrAction   = null;
        $this->qrImageUrl = null;
    }
}