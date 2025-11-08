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
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\HouseUnitController;

// ===============================================================
// RUTE HALAMAN PUBLIK (Tidak perlu otorisasi)
// ===============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');
Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index.public');
Route::get('/get-all-projects', [HomeController::class, 'getAllProjectsForMap'])->name('projects.all.for.map');
Route::get('/get-villages/{districtCode}', [ProjectController::class, 'getVillages']);


// ===============================================================
// RUTE AREA ADMIN (MEMBUTUHKAN LOGIN)
// ===============================================================

// Grup utama untuk SEMUA user yang sudah login ('admin' DAN 'developer')
// Kita tambahkan .name('admin.') di sini untuk prefix nama rute
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute yang bisa diakses 'admin' DAN 'developer'
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Nama rute: admin.dashboard
    Route::resource('projects', HousingProjectController::class); // Nama rute: admin.projects.index, admin.projects.create, dll.
    Route::resource('projects.house-types', HouseTypeController::class)->shallow();
    Route::resource('projects.house-units', HouseUnitController::class)->shallow();
    Route::get('/gis-dashboard', [GisController::class, 'index'])->name('gis.index'); // Nama rute: admin.gis.index

    // Rute Profil (semua user login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute yang HANYA bisa diakses 'admin'
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('developers', DeveloperController::class); // Nama rute: admin.developers.index, dll.
    });

});


require __DIR__ . '/auth.php';