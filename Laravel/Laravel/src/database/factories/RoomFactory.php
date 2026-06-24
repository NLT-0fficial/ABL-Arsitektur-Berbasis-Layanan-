<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    /**
     * Nama-nama laki-laki yang dipakai sebagai data dummy penghuni kost pria.
     */
    public const MALE_NAMES = [
        'Ahmad Fauzi',
        'Budi Santoso',
        'Candra Wijaya',
        'Dedi Kurniawan',
        'Eko Prasetyo',
        'Fajar Hidayat',
        'Gilang Ramadhan',
        'Hendra Saputra',
        'Irfan Maulana',
        'Joko Susanto',
        'Krisna Aditya',
        'Lukman Hakim',
        'Muhammad Rizki',
        'Nanda Pratama',
        'Oki Setiawan',
        'Putra Nugraha',
        'Rendi Firmansyah',
        'Surya Darmawan',
        'Taufik Hidayat',
        'Wahyu Setiabudi',
        'Yusuf Anggara',
        'Zaki Ramadhan',
        'Agus Salim',
        'Bayu Permana',
        'Dimas Anggoro',
    ];

    /**
     * Default state: kamar kosong tanpa penghuni.
     */
    public function definition(): array
    {
        return [
            'code' => 'X-000',
            'category' => $this->faker->randomElement(Room::CATEGORIES),
            'floor' => $this->faker->numberBetween(1, 3),
            'rent_price' => 0,
            'is_occupied' => false,
            'tenant_name' => null,
            'tenant_phone' => null,
            'occupied_since' => null,
            'notes' => null,
        ];
    }

    /**
     * State: tandai kamar sebagai terisi penghuni (nama laki-laki).
     */
    public function occupied(): static
    {
        return $this->state(function () {
            return [
                'is_occupied' => true,
                'tenant_name' => $this->faker->unique()->randomElement(self::MALE_NAMES),
                'tenant_phone' => '08' . $this->faker->numerify('##########'),
                'occupied_since' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ];
        });
    }

    /**
     * State: tandai kamar sebagai kosong (tanpa penghuni).
     */
    public function vacant(): static
    {
        return $this->state([
            'is_occupied' => false,
            'tenant_name' => null,
            'tenant_phone' => null,
            'occupied_since' => null,
        ]);
    }

    /**
     * State: set kategori kamar secara spesifik (A/B/C).
     */
    public function category(string $category): static
    {
        return $this->state([
            'category' => $category,
        ]);
    }
}