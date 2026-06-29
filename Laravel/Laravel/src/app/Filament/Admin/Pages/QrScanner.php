<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\Room;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class QrScanner extends Page
{
    protected string $view = 'filament.admin.pages.qr-scanner';
    protected static ?string $title = 'QR Scanner';
    protected static ?string $slug = 'qr-scanner';
    protected static ?int $navigationSort = 10;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::QrCode;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'General';
    }

    public ?string $scannedToken = null;
    public ?array $scannedRoom   = null;
    public ?string $scanStatus   = null;

    public function processScan(string $token): void
    {
        // Token langsung dari QR, tidak perlu parse URL
        $room = Room::where('qr_token', trim($token))->first();

        if (! $room) {
            $this->scanStatus  = 'error';
            $this->scannedRoom = null;

            Notification::make()
                ->title('QR tidak dikenali')
                ->danger()
                ->send();
            return;
        }

        $this->scannedToken = $token;
        $this->scannedRoom  = [
            'code'           => $room->code,
            'category'       => $room->category,
            'floor'          => $room->floor,
            'tenant_name'    => $room->tenant_name ?? '-',
            'tenant_phone'   => $room->tenant_phone ?? '-',
            'rent_price'     => number_format($room->rent_price, 0, ',', '.'),
            'occupied_since' => $room->occupied_since?->translatedFormat('d M Y') ?? '-',
        ];
        $this->scanStatus = 'success';

        Notification::make()
            ->title('QR Berhasil Dibaca')
            ->body('Kamar ' . $room->code . ' — ' . ($room->tenant_name ?? 'Kosong'))
            ->success()
            ->send();
    }

    public function resetScan(): void
    {
        $this->scannedToken = null;
        $this->scannedRoom  = null;
        $this->scanStatus   = null;
    }
}