<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            DB::table('barangs')->insert([
                'nama_barang' => $faker->word,
                'kode_barang' => $faker->unique()->numerify('BRG####'),
                'stok' => $faker->numberBetween(10, 100),
                'harga' => $faker->numberBetween(10000, 100000),
                'timestamp' => now(),
            ]);
        }
    }
}
