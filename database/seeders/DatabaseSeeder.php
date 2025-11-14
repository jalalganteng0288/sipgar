<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
// --- TAMBAHKAN INI ---
use Laravolt\Indonesia\Seeds\DatabaseSeeder as IndonesiaDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder yang sudah kita perbaiki
        $this->call([
            // --- TAMBAHKAN INI ---
            // Baris ini akan mengisi tabel provinces, cities, districts, dan villages
            IndonesiaDatabaseSeeder::class,
            
            // Ini seeder Anda yang sudah ada
            RolesAndUsersSeeder::class,
        ]);
    }
}