<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============ SEED USER ============
        // Hapus user admin lama jika ada
        User::where('email', 'admin@admin.com')->delete();

        // Buat user admin dengan password admin123
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'email_verified_at' => now(),
        ]);

        // ============ SEED KOST ============
        $this->call(KostSeeder::class);
    }
}