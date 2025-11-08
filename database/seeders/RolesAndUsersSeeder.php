<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini
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

        // create permissions (Ini bisa dikosongkan jika Anda belum menggunakannya)
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);

        
        // --- PERBAIKAN: Gunakan firstOrCreate agar tidak duplikat ---
        
        // Buat role 'admin' jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $adminRole->givePermissionTo('edit articles'); // Contoh jika pakai permission

        // Buat role 'developer' jika belum ada
        $developerRole = Role::firstOrCreate(['name' => 'developer']);

        
        // --- PERBAIKAN: Buat user default jika belum ada ---

        // Buat Admin User jika emailnya belum terdaftar
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Set password default
            ]
        );
        $adminUser->assignRole($adminRole); // Tetapkan role admin

        // Buat Developer User jika emailnya belum terdaftar
        $developerUser = User::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Developer User',
                'password' => Hash::make('password'), // Set password default
            ]
        );
        $developerUser->assignRole($developerRole); // Tetapkan role developer
    }
}