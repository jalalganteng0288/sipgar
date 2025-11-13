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
use App\Http\Controllers\AdminHomeController; // 🆕 Tambahan controller baru

// ===============================================================
// RUTE HALAMAN PUBLIK (Tidak perlu otorisasi)
// ===============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');

// Pastikan route ini ada di routes/web.php
Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index.public');
Route::get('/get-all-projects', [HomeController::class, 'getAllProjectsForMap'])->name('projects.all.for.map');

// --- PERBAIKAN: Arahkan ke Controller yang benar ---
Route::get('/get-villages/{districtCode}', [HousingProjectController::class, 'getVillages'])->name('get.villages');
// --------------------------------------------------


// ===============================================================
// RUTE ADMIN HOME (bisa dipakai kalau mau dashboard utama terpisah)
// ===============================================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard.home'); // 🆕 tambahan route admin utama
});


// ===============================================================
// RUTE AREA ADMIN (MEMBUTUHKAN LOGIN)
// ===============================================================
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
