<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dokter;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dokter::create([
            'nama_dokter' => 'Dr. John Doe',
            'spesialis' => 'Kesehatan',
            'hari' => 'Senin',
            'jam_awal_praktik' => '2025-05-19 08:00',
            'jam_akhir_praktik' => '2025-05-19 17:00',
        ]);
        Dokter::create([
            'nama_dokter' => 'Dr. Jane Smith',
            'spesialis' => 'Kesehatan',
            'hari' => 'Selasa',
            'jam_awal_praktik' => '2025-05-19 08:00',
            'jam_akhir_praktik' => '2025-05-19 17:00',
        ]);
        Dokter::create([
            'nama_dokter' => 'Dr. John Doe',
            'spesialis' => 'Kesehatan',
            'hari' => 'Rabu',
            'jam_awal_praktik' => '2025-05-19 08:00',
            'jam_akhir_praktik' => '2025-05-19 17:00',
        ]);
        Dokter::create([
            'nama_dokter' => 'Dr. Muhammad Enuh',
            'spesialis' => 'Kesehatan',
            'hari' => 'Kamis',
            'jam_awal_praktik' => '2025-05-19 08:00',
            'jam_akhir_praktik' => '2025-05-19 17:00',
        ]);
    }
}
