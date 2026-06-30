<?php

namespace Database\Seeders;

use App\Models\CheckInLog;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class CheckInLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->first();
        $rooms = Room::query()->with('tenant')->get();

        foreach ($rooms as $room) {
            $tenant = $room->tenant ?? User::factory()->create(['room_id' => $room->id]);

            CheckInLog::factory()
                ->count(3)
                ->state(fn () => [
                    'user_id'    => $tenant->id,
                    'room_id'    => $room->id,
                    'scanned_by' => $admin?->id ?? $tenant->id,
                ])
                ->create();
        }
    }
}