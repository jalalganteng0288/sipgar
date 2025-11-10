<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Developer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat role 'admin' jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Buat role 'developer' jika belum ada
        $developerRole = Role::firstOrCreate(['name' => 'developer']);

        
        // --- ADMIN BARU ---
        // Buat Admin User (Sesuai permintaan Anda)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sipgar.com'], // <-- BERUBAH
            [
                'name' => 'Admin Sipgar',
                'password' => Hash::make('admin123'), // <-- PASSWORD BARU
            ]
        );
        $adminUser->assignRole($adminRole); // Tetapkan role admin

        
        // --- DEVELOPER BARU ---
        // Buat Developer User (Sesuai permintaan Anda)
        $developerUser = User::firstOrCreate(
            ['email' => 'developer@sipgar.com'], // <-- BERUBAH
            [
                'name' => 'Developer Sipgar',
                'password' => Hash::make('dev123'), // <-- PASSWORD BARU (BERBEDA)
            ]
        );
        $developerUser->assignRole($developerRole); // Tetapkan role developer

        
        // --- KODE PENTING: Tetap buatkan profil perusahaan untuk developer ---
        Developer::firstOrCreate(
            ['user_id' => $developerUser->id], // Cari berdasarkan user_id
            [
                'company_name' => 'PT. Developer Default',
                'nib' => '1234567890', 
                'address' => 'Alamat Default Developer'
            ]
        );
    }
}