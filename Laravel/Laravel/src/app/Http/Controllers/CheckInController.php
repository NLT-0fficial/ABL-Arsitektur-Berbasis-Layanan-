<?php

namespace App\Http\Controllers;

use App\Models\CheckInLog;
use App\Models\Room;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    public function scan(string $token)
    {
        $room = Room::where('qr_token', $token)->firstOrFail();

        $tenant = $room->tenant;

        if (! $tenant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kamar ini belum memiliki penyewa terdaftar.',
            ], 422);
        }

        $lastLog = CheckInLog::where('user_id', $tenant->id)
            ->where('room_id', $room->id)
            ->latest('scanned_at')
            ->first();

        $type = ($lastLog && $lastLog->type === 'masuk')
            ? 'keluar'
            : 'masuk';

        try {

            $adminId = Filament::auth()->id() ?? Auth::id();

            if (! $adminId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Admin belum login.',
                ], 401);
            }

            $log = CheckInLog::create([
                'user_id'    => $tenant->id,
                'room_id'    => $room->id,
                'scanned_by' => $adminId,
                'type'       => $type,
                'scanned_at' => now(),
            ]);

        } catch (\Throwable $e) {

            Log::error('CheckIn Scan Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'ok',
            'room'   => $room->only([
                'id',
                'code',
                'tenant_name',
                'qr_token',
            ]),
            'log'    => $log->only([
                'id',
                'type',
                'scanned_at',
            ]),
        ]);
    }
}