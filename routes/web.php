<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\HousingProjectController;
use App\Http\Controllers\Admin\HouseTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\Public\ChartController;

// Rute Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');
Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index');
Route::get('/get-all-projects', [HomeController::class, 'getAllProjectsForMap'])->name('projects.all.for.map');

// Rute untuk dropdown dinamis
Route::get('/get-villages', [DependentDropdownController::class, 'villages'])->name('dependent-dropdown.villages');

// ===============================================================
// PERBAIKAN DI SINI
// ===============================================================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute dashboard yang mengarah ke daftar proyek
    Route::get('/dashboard', [HousingProjectController::class, 'index'])->name('dashboard');

    // Rute untuk mengelola proyek perumahan dan tipe rumah
    Route::resource('projects', HousingProjectController::class);
    Route::resource('projects.house-types', HouseTypeController::class)->shallow();

    // Rute untuk profil SEKARANG ADA DI DALAM GRUP ADMIN
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';