<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Admin\HousingProjectController;
use App\Http\Controllers\Admin\HouseTypeController;
use App\Http\Controllers\Admin\DashboardController; // <--- Controller ini digunakan
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\Public\ChartController;
use App\Http\Controllers\Admin\GisController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\HouseUnitController;
// use App\Http\Controllers\AdminHomeController; // <--- DIHAPUS: Controller yang bermasalah
use App\Http\Controllers\DeveloperProfileController;
use App\Http\Controllers\Admin\ReportController;

// ===============================================================
// 🌐 RUTE HALAMAN PUBLIK (Tidak perlu otorisasi)
// ===============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');

Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index.public');
Route::get('/get-all-projects', [HomeController::class, 'getAllProjectsForMap'])->name('projects.all.for.map');

// Dependent Dropdown
Route::get('/get-villages/{districtCode}', [HousingProjectController::class, 'getVillages'])->name('get.villages');


// ===============================================================
// 🔑 RUTE ADMIN HOME (Tidak lagi menggunakan AdminHomeController)
// ===============================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // DIHAPUS: Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard.home');
    // Rute dashboard utama akan diatur di grup middleware di bawah.
});


// ===============================================================
// 🔒 RUTE AREA ADMIN (MEMBUTUHKAN LOGIN)
// ===============================================================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // --- Rute yang bisa diakses 'admin' DAN 'developer' ---
    // Dashboard kini dikelola di sini.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // <--- Dashboard tunggal

    Route::resource('projects', HousingProjectController::class);
    Route::resource('projects.house-types', HouseTypeController::class)->shallow();
    Route::resource('projects.house-units', HouseUnitController::class)->shallow();
    Route::get('/gis-dashboard', [GisController::class, 'index'])->name('gis.index');

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/developer', [DeveloperProfileController::class, 'update'])
        ->name('profile.developer.update');

    // --- Rute yang HANYA bisa diakses 'admin' ---
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('developers', DeveloperController::class);
        

        Route::patch('developers/{developer}/toggle-role', [DeveloperController::class, 'toggleRole'])
            ->name('developers.toggleRole');

        Route::get('report/export-units', [ReportController::class, 'exportUnitData'])
            ->name('report.exportUnits');

        Route::patch('/projects/{project}/approve', [HousingProjectController::class, 'approve'])->name('projects.approve');
        Route::put('projects/{project}/reject', [HousingProjectController::class, 'reject'])->name('projects.reject');
    });
});

require __DIR__ . '/auth.php';
