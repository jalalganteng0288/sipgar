<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Laravolt Indonesia
        $this->call([
            \Laravolt\Indonesia\Seeds\ProvincesSeeder::class,
            \Laravolt\Indonesia\Seeds\CitiesSeeder::class,
            \Laravolt\Indonesia\Seeds\DistrictsSeeder::class,
            \Laravolt\Indonesia\Seeds\VillagesSeeder::class,
        ]);

        // ===============================================
        // PASTIKAN BARIS DI BAWAH INI ADA
        // DAN TIDAK ADA TANDA KOMENTAR (//) DI DEPANNYA
        // ===============================================
        $this->call(RolesAndUsersSeeder::class);
    }
}