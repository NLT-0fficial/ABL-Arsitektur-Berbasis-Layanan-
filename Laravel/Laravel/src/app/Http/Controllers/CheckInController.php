<?php

namespace App\Http\Controllers;

use App\Models\CheckInLog;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function scan(string $token)
    {
        $room = Room::where('qr_token', $token)->firstOrFail();

        $tenant = $room->tenant; // pakai relasi tenant() (HasOne) yang ada di Room.php

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

        $type = ($lastLog && $lastLog->type === 'masuk') ? 'keluar' : 'masuk';

        $log = CheckInLog::create([
            'user_id'    => $tenant->id,
            'room_id'    => $room->id,
            'scanned_by' => Auth::id(),
            'type'       => $type,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'status' => 'ok',
            'room'   => $room->only(['id', 'code', 'tenant_name', 'qr_token']),
            'log'    => $log->only(['id', 'type', 'scanned_at']),
        ]);
    }
}