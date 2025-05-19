<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mobil;
class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mobil::create([
            'nomor_polisi' => 'B 1234 ABC',
            'type_kendaraan' => 'Sedan',
        ]);
        Mobil::create([
            'nomor_polisi' => 'D 5678 DEF',
            'type_kendaraan' => 'SUV',
        ]);
        Mobil::create([
            'nomor_polisi' => 'F 9101 GHI',
            'type_kendaraan' => 'Hatchback',
        ]);
        Mobil::create([
            'nomor_polisi' => 'L 1122 JKL',
            'type_kendaraan' => 'Pickup',
        ]);
        Mobil::create([
            'nomor_polisi' => 'B 3344 MNO',
            'type_kendaraan' => 'Minibus',
        ]);
        Mobil::create([
            'nomor_polisi' => 'AB 5566 PQR',
            'type_kendaraan' => 'MPV',
        ]);
    }
}
