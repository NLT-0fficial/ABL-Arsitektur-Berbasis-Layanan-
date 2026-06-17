<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Kost;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        Kost::query()->delete();

        // Hapus user kamar lama (selain admin)
        User::where('email', 'like', '%@gmail.com')->delete();

        foreach ($lantai as $l) {
            for ($i = 101; $i <= 110; $i++) {
                $kamar      = $l . $i;
                $isTerisi   = isset($dataPenyewa[$kamar]);
                $namaPenyewa = $isTerisi ? $dataPenyewa[$kamar] : null;

                Kost::create([
                    'lantai'       => $l,
                    'nomor_kamar'  => $kamar,
                    'nama_penyewa' => $namaPenyewa,
                    'status'       => $isTerisi ? 'terisi' : 'kosong',
                ]);

                if ($isTerisi && $namaPenyewa) {
                    $namaAwal   = strtolower(explode(' ', $namaPenyewa)[0]);
                    $angkaKamar = $i; // 101, 102, dst

                    User::create([
                        'name'              => $namaPenyewa,
                        'email'             => strtolower($kamar) . '@gmail.com',
                        'password'          => $namaAwal . $angkaKamar,
                        'email_verified_at' => now(),
                    ]);
                }
            }
        }
    }
}