<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\CheckInLog;
use App\Models\Room;
use App\Models\User;
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
        $token = trim($token);

        $action    = null;
        $roomToken = $token;

        // payload dari TenantDashboard: "qr_token|action" (plain text, bukan base64)
        if (str_contains($token, '|')) {
            [$roomToken, $maybeAction] = explode('|', $token, 2);

            if (in_array($maybeAction, ['masuk', 'keluar'], true)) {
                $action = $maybeAction;
            }
        }

        $room = Room::where('qr_token', $roomToken)->first();

        if (! $room) {
            $this->scanStatus  = 'error';
            $this->scannedRoom = null;

            Notification::make()
                ->title('QR tidak dikenali')
                ->danger()
                ->send();
            return;
        }

        // Cari akun penyewa yang terdaftar untuk kamar ini
        $tenant = User::where('room_id', $room->id)->first();

        if (! $tenant) {
            $this->scanStatus  = 'error';
            $this->scannedRoom = null;

            Notification::make()
                ->title('Kamar belum punya akun penyewa terdaftar')
                ->danger()
                ->send();
            return;
        }

        // Kalau action tidak ada di payload QR, tentukan otomatis (toggle dari log terakhir)
        if ($action === null) {
            $lastLog = CheckInLog::where('room_id', $room->id)
                ->where('user_id', $tenant->id)
                ->latest('scanned_at')
                ->first();

            $action = ($lastLog && $lastLog->type === 'masuk') ? 'keluar' : 'masuk';
        }

        $log = CheckInLog::create([
            'user_id'    => $tenant->id,
            'room_id'    => $room->id,
            'scanned_by' => auth()->id(),
            'type'       => $action,
            'scanned_at' => now(),
        ]);

        $this->scannedToken = $roomToken;
        $this->scannedRoom  = [
            'code'           => $room->code,
            'category'       => $room->category,
            'floor'          => $room->floor,
            'tenant_name'    => $room->tenant_name ?? '-',
            'tenant_phone'   => $room->tenant_phone ?? '-',
            'rent_price'     => number_format($room->rent_price, 0, ',', '.'),
            'occupied_since' => $room->occupied_since?->translatedFormat('d M Y') ?? '-',
            'action'         => $log->type, // 'masuk' | 'keluar'
        ];
        $this->scanStatus = 'success';

        Notification::make()
            ->title('QR Berhasil Dibaca')
            ->body(
                'Kamar ' . $room->code . ' — ' . ($room->tenant_name ?? 'Kosong')
                . ' (' . ucfirst($log->type) . ')'
            )
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