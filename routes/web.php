<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HousingProjectController;
use App\Http\Controllers\Admin\HouseTypeController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ChartController;
use App\Http\Controllers\DependentDropdownController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');
Route::get('/grafik-perumahan', [ChartController::class, 'index'])->name('charts.index');
Route::get('/get-villages', [DependentDropdownController::class, 'villages'])->name('dependent-dropdown.villages');
Route::get('/api/projects-location', [HomeController::class, 'getAllProjectsForMap'])->name('api.projects-location');


/*
|--------------------------------------------------------------------------
| Rute Admin (Hanya bisa diakses setelah login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HousingProjectController::class, 'index'])->name('dashboard');
    Route::resource('projects', HousingProjectController::class);
    Route::resource('projects.house-types', HouseTypeController::class)->shallow();

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Rute Otentikasi (Login, Register, dll.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';