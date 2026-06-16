<?php

namespace Database\Seeders;

use App\Models\Kost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data nama penyewa laki-laki semua
        $dataPenyewa = [
            // Lantai A (Lantai 1) - 10 kamar terisi semua
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
            
            // Lantai B (Lantai 2) - 5 kamar terisi
            'B101' => 'Irfan Hakim',
            'B102' => 'Yudi Setiawan',
            'B103' => 'Fajar Ramadhan',
            'B104' => 'Gilang Pratama',
            'B105' => 'Rizal Maulana',
            // B106 - B110 kosong
            
            // Lantai C (Lantai 3) - 8 kamar terisi
            'C101' => 'Surya Wijaya',
            'C102' => 'Bayu Saputra',
            'C103' => 'Darma Putra',
            'C104' => 'Herman Suherman',
            'C105' => 'Joko Widodo',
            'C106' => 'Kartono Susanto',
            'C107' => 'Mulyono Hadi',
            'C108' => 'Rahmat Hidayat'
            // C109 - C110 kosong
        ];

        // Data tambahan untuk lantai yang kosong
        $lantai = ['A', 'B', 'C'];
        
        // Hapus data lama
        Kost::truncate();
        
        // Buat data baru
        foreach ($lantai as $l) {
            for ($i = 101; $i <= 110; $i++) {
                $kamar = $l . $i;
                $isTerisi = isset($dataPenyewa[$kamar]);
                
                Kost::create([
                    'lantai' => $l,
                    'nomor_kamar' => $kamar,
                    'nama_penyewa' => $isTerisi ? $dataPenyewa[$kamar] : null,
                    'status' => $isTerisi ? 'terisi' : 'kosong'
                ]);
            }
        }
    }
}