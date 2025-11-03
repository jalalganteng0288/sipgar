<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\HousingProjectController;
use App\Http\Controllers\Admin\HouseTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\Public\ChartController;
use App\Http\Controllers\Admin\GisController;

// Pastikan Anda telah membuat controller ini di langkah sebelumnya (php artisan make:controller Admin/HouseUnitController)
use App\Http\Controllers\Admin\HouseUnitController; // <-- BARU: Controller untuk Unit Rumah/Kavling

// ===============================================================
// RUTE HALAMAN PUBLIK (Tidak perlu otorisasi)
// ===============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');
Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index.public');
Route::get('/get-all-projects', [HomeController::class, 'getAllProjectsForMap'])->name('projects.all.for.map');

// Rute untuk dropdown dinamis (misalnya: Desa bergantung pada Kecamatan)
Route::get('/get-villages/{district_code}', [DependentDropdownController::class, 'villages'])->name('dependent-dropdown.villages');


// ===============================================================
// RUTE AREA ADMIN (MEMBUTUHKAN LOGIN & ROLE)
// ===============================================================
Route::middleware(['auth', 'verified', 'role:disperkim_admin|developer'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute dashboard yang mengarah ke daftar proyek
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Proyek Perumahan (CRUD)
    Route::resource('projects', HousingProjectController::class);
    
    // Manajemen Tipe Rumah (Shallow Resource: projects/{project}/house-types)
    Route::resource('projects.house-types', HouseTypeController::class)->shallow();

    // Manajemen Unit/Kavling Rumah (Shallow Resource: projects/{project}/house-units)
    // Unit ini yang akan dilacak status stoknya (Tersedia/Terjual) seperti SiKumbang
    Route::resource('projects.house-units', HouseUnitController::class)->shallow(); // <-- BARU

    // GIS Dashboard (Peta)
    Route::get('/gis-dashboard', [GisController::class, 'index'])->name('gis.dashboard');

    // Rute untuk Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
