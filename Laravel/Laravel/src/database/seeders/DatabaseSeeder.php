<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
        ]);
        $admin->assignRole('super_admin');

        $this->call([
            RoomSeeder::class,
            RoleSeeder::class,
        // CheckInLogSeeder::class, // dinonaktifkan, data harus dari scan QR asli
        ]);
    }
}