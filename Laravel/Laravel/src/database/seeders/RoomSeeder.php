<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    protected array $occupiedCountPerCategory = [
        'A' => 10,
        'B' => 5,
        'C' => 8,
    ];

    protected array $rentPricePerCategory = [
        'A' => 700000,
        'B' => 800000,
        'C' => 900000,
    ];

    protected int $roomsPerCategory = 10;

    public function run(): void
    {
        foreach (Room::CATEGORIES as $category) {
            $occupiedCount = $this->occupiedCountPerCategory[$category] ?? 0;
            $rentPrice     = $this->rentPricePerCategory[$category] ?? 0;

            for ($i = 1; $i <= $this->roomsPerCategory; $i++) {
                $code       = sprintf('%s-1%02d', $category, $i);
                $isOccupied = $i <= $occupiedCount;

                $room = Room::factory()->category($category);
                $room = $isOccupied ? $room->occupied() : $room->vacant();

                $created = $room->create([
                    'code'       => $code,
                    'floor'      => (int) ceil($i / 4),
                    'rent_price' => $rentPrice,
                ]);

                // Auto-buat akun User untuk setiap penyewa
                if ($isOccupied && $created->tenant_name) {
                    User::factory()
                        ->tenant($created->tenant_name, $created->id)
                        ->create();
                }
            }
        }

        $this->command?->info('Berhasil membuat 30 kamar + akun penyewa (23 kamar terisi).');
    }
}