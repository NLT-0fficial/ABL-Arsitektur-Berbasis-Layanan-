<?php

namespace App\Http\Controllers;

use App\Models\Room;

class CheckInController extends Controller
{
    public function scan(string $token)
    {
        $room = Room::where('qr_token', $token)->firstOrFail();

        return response()->json([
            'status' => 'ok',
            'room'   => $room->only(['id', 'code', 'tenant_name', 'qr_token']),
        ]);
    }
}