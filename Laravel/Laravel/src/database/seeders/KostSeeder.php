<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Kost;
use App\Models\KostQrToken;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- TAMBAHKAN INI

final class KostSeeder extends Seeder
{
    public function run(): void
    {
        $dataPenyewa = [
            'A101' => 'Budi Santoso',
            'A102' => 'Ahmad Fauzi',
            'A103' => 'Rudi Hartono',
            'A104' => 'Agus Salim',
            'A105' => 'Hendra Gunawan',
            'A106' => 'Doni Saputra',
            'A107' => 'Andi Wijaya',
            'A108' => 'Rizky Febrian',
            'A109' => 'Eko Prasetyo',
            'A110' => 'Dedi Irawan',

            'B101' => 'Irfan Hakim',
            'B102' => 'Yudi Setiawan',
            'B103' => 'Fajar Ramadhan',
            'B104' => 'Gilang Pratama',
            'B105' => 'Rizal Maulana',

            'C101' => 'Surya Wijaya',
            'C102' => 'Bayu Saputra',
            'C103' => 'Darma Putra',
            'C104' => 'Herman Suherman',
            'C105' => 'Joko Widodo',
            'C106' => 'Kartono Susanto',
            'C107' => 'Mulyono Hadi',
            'C108' => 'Rahmat Hidayat',
        ];

        $lantai = ['A', 'B', 'C'];

        // Buat role penyewa jika belum ada
        Role::firstOrCreate(['name' => 'penyewa']); // <-- TAMBAHKAN INI

        // Hapus data lama
        Kost::query()->delete();
        KostQrToken::query()->delete();
        
        // Hapus user penyewa lama (selain admin)
        User::where('email', 'like', '%@gmail.com')->delete();

        foreach ($lantai as $l) {
            for ($i = 101; $i <= 110; $i++) {
                $kamar       = $l . $i;
                $isTerisi    = isset($dataPenyewa[$kamar]);
                $namaPenyewa = $isTerisi ? $dataPenyewa[$kamar] : null;
                $userId      = null;

                // Buat user kalau kamar terisi
                if ($isTerisi && $namaPenyewa) {
                    $namaAwal   = strtolower(explode(' ', $namaPenyewa)[0]);
                    $angkaKamar = $i;

                    $user = User::create([
                        'name'              => $namaPenyewa,
                        'email'             => strtolower($kamar) . '@gmail.com',
                        'password'          => bcrypt($namaAwal . $angkaKamar),
                        'email_verified_at' => now(),
                    ]);

                    // Assign role penyewa ke user
                    $user->assignRole('penyewa'); // <-- TAMBAHKAN INI

                    $userId = $user->id;
                }

                // Buat data kost
                Kost::create([
                    'user_id'      => $userId,
                    'lantai'       => $l,
                    'nomor_kamar'  => $kamar,
                    'nama_penyewa' => $namaPenyewa,
                    'status'       => $isTerisi ? 'terisi' : 'kosong',
                ]);
            }
        }

        // Generate token permanen untuk setiap kamar terisi
        Kost::terisi()->each(function (Kost $kost) {
            KostQrToken::getOrCreateFor($kost->id);
        });
    }
}