<?php

namespace Database\Seeders;

use App\Models\Room;
use Database\Factories\RoomFactory;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Konfigurasi jumlah kamar terisi per kategori.
     * Kategori A: 10 dari 10 kamar terisi (penuh)
     * Kategori B: 5 dari 10 kamar terisi
     * Kategori C: 8 dari 10 kamar terisi
     */
    protected array $occupiedCountPerCategory = [
        'A' => 10,
        'B' => 5,
        'C' => 8,
    ];

    /**
     * Harga sewa per bulan, fixed berdasarkan kategori kamar.
     */
    protected array $rentPricePerCategory = [
        'A' => 700000,
        'B' => 800000,
        'C' => 900000,
    ];

    /**
     * Jumlah kamar per kategori.
     */
    protected int $roomsPerCategory = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Room::CATEGORIES as $category) {
            $occupiedCount = $this->occupiedCountPerCategory[$category] ?? 0;
            $rentPrice = $this->rentPricePerCategory[$category] ?? 0;

            for ($i = 1; $i <= $this->roomsPerCategory; $i++) {
                // Kode kamar: A-101, A-102, ..., A-110 (begitu pula B dan C)
                $code = sprintf('%s-1%02d', $category, $i);

                $isOccupied = $i <= $occupiedCount;

                $room = Room::factory()->category($category);
                $room = $isOccupied ? $room->occupied() : $room->vacant();

                $room->create([
                    'code' => $code,
                    'floor' => (int) ceil($i / 4),
                    'rent_price' => $rentPrice,
                ]);
            }
        }

        $this->command?->info('Berhasil membuat 30 kamar kost (10 kategori A, 10 kategori B, 10 kategori C).');
    }
}