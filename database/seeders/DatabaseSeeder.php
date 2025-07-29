<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456789',
            'role' => 'admin_gudang',
        ]);
        User::factory()->create([
            'name' => 'owner',
            'email' => 'owner@gmail.com',
            'password' => '123456789',
            'role' => 'owner',
        ]);
    }
}
