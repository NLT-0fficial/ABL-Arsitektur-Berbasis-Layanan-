<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Buat role dasar (super_admin, tenant)
        $this->call([
            RoleSeeder::class,
        ]);

        // 2. Generate ulang semua permission Filament Shield (Resource, Page, Widget)
        //    --no-interaction wajib, supaya tidak nyangkut nunggu prompt CLI
        //    karena dipanggil secara programatis (bukan lewat terminal langsung).
        $this->command->info('Generating Filament Shield permissions...');
        Artisan::call('shield:generate', [
            '--all'           => true,
            '--panel'         => 'admin',
            '--no-interaction' => true,
        ]);
        $this->command->info(Artisan::output());

        // 3. Safety net: pastikan super_admin benar-benar punya SEMUA permission,
        //    tidak bergantung sepenuhnya ke behavior internal Shield.
        //    Perlu karena super_admin.define_via_gate = false di config/filament-shield.php,
        //    jadi role ini tidak otomatis bypass — harus punya permission eksplisit.
        $superAdmin = Role::findByName('super_admin', 'web');
        $superAdmin->syncPermissions(Permission::all());

        // 4. Buat akun admin & assign role super_admin
        $admin = User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
        ]);
        $admin->assignRole('super_admin');

        // 5. Seed data kamar + akun penyewa
        $this->call([
            RoomSeeder::class,
            // CheckInLogSeeder::class, // dinonaktifkan, data harus dari scan QR asli
        ]);
    }
}