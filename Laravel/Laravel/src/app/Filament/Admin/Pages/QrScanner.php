<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\CheckInLog;
use App\Models\Room;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;

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

    public ?array $scannedRoom = null;

    public ?string $scanStatus = null;

    public function processScan(?string $token = null): void
    {
        Log::info('PROCESS SCAN TERPANGGIL', [
            'token' => $token,
        ]);

        // ===== DEBUG PENTING =====
        if (blank($token)) {

            Log::warning('PROCESS SCAN DIPANGGIL TANPA TOKEN');

            return;
        }

        $token = trim($token);

        $decoded = base64_decode($token, true);

        $action = null;
        $roomToken = $token;

        if ($decoded !== false && str_contains($decoded, '|')) {
            $roomToken = $decoded;
        }

        if (str_contains($roomToken, '|')) {

            [$roomToken, $maybeAction] = explode('|', $roomToken, 2);

            if (in_array($maybeAction, ['masuk', 'keluar'], true)) {
                $action = $maybeAction;
            }
        }

        Log::info('TOKEN HASIL PARSING', [
            'room_token' => $roomToken,
            'action' => $action,
        ]);

        $room = Room::where('qr_token', $roomToken)->first();

        if (! $room) {

            Log::warning('ROOM TIDAK DITEMUKAN', [
                'room_token' => $roomToken,
            ]);

            $this->scanStatus = 'error';
            $this->scannedRoom = null;

            Notification::make()
                ->title('QR tidak dikenali')
                ->danger()
                ->send();

            return;
        }

        $tenant = User::where('room_id', $room->id)->first();

        if (! $tenant) {

            Log::warning('TENANT TIDAK DITEMUKAN', [
                'room_id' => $room->id,
            ]);

            $this->scanStatus = 'error';
            $this->scannedRoom = null;

            Notification::make()
                ->title('Kamar belum punya akun penyewa terdaftar')
                ->danger()
                ->send();

            return;
        }

        if ($action === null) {

            $lastLog = CheckInLog::where('room_id', $room->id)
                ->where('user_id', $tenant->id)
                ->latest('scanned_at')
                ->first();

            $action = ($lastLog && $lastLog->type === 'masuk')
                ? 'keluar'
                : 'masuk';
        }

        try {

            $adminId = Filament::auth()->id();

            Log::info('QR SCAN DEBUG', [
                'tenant_id' => $tenant->id,
                'room_id'   => $room->id,
                'auth_id'   => $adminId,
                'action'    => $action,
            ]);

            if (! $adminId) {
                throw new \Exception(
                    'Admin belum login atau guard Filament tidak terdeteksi.'
                );
            }

            $log = CheckInLog::create([
                'user_id'    => $tenant->id,
                'room_id'    => $room->id,
                'scanned_by' => $adminId,
                'type'       => $action,
                'scanned_at' => now(),
            ]);

            Log::info('CHECK IN LOG BERHASIL DISIMPAN', [
                'log_id'  => $log->id,
                'user_id' => $tenant->id,
                'room_id' => $room->id,
                'type'    => $action,
            ]);

        } catch (\Throwable $e) {

            Log::error('QR SCAN ERROR', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            Notification::make()
                ->title('Gagal menyimpan log scan')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->scannedToken = $roomToken;

        $this->scannedRoom = [
            'code'           => $room->code,
            'category'       => $room->category,
            'floor'          => $room->floor,
            'tenant_name'    => $room->tenant_name ?? '-',
            'tenant_phone'   => $room->tenant_phone ?? '-',
            'rent_price'     => number_format($room->rent_price, 0, ',', '.'),
            'occupied_since' => $room->occupied_since?->translatedFormat('d M Y') ?? '-',
            'action'         => $log->type,
        ];

        $this->scanStatus = 'success';

        Notification::make()
            ->title('QR Berhasil Dibaca')
            ->body(
                'Kamar '
                . $room->code
                . ' — '
                . ($room->tenant_name ?? 'Kosong')
                . ' ('
                . ucfirst($log->type)
                . ')'
            )
            ->success()
            ->send();
    }

    public function resetScan(): void
    {
        $this->scannedToken = null;
        $this->scannedRoom = null;
        $this->scanStatus = null;
    }
}