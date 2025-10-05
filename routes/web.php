<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HousingProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\Admin\HouseTypeController;


Route::get('/', [HomeController::class, 'index']);


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard/projects', [HousingProjectController::class, 'index'])->name('admin.projects.index');
    Route::get('/dashboard/projects/create', [HousingProjectController::class, 'create'])->name('admin.projects.create');
    Route::post('/dashboard/projects', [HousingProjectController::class, 'store'])->name('admin.projects.store');
    Route::get('/dashboard/projects/{project}/edit', [HousingProjectController::class, 'edit'])->name('admin.projects.edit');
    Route::put('/dashboard/projects/{project}', [HousingProjectController::class, 'update'])->name('admin.projects.update');
    Route::delete('/dashboard/projects/{project}', [HousingProjectController::class, 'destroy'])->name('admin.projects.destroy');
    Route::get('/dashboard/projects/{project}', [HousingProjectController::class, 'show'])->name('admin.projects.show');
    Route::get('/projects/{project}', [HomeController::class, 'show'])->name('projects.show');

    Route::get('dependent-dropdown/villages', [DependentDropdownController::class, 'villages'])->name('dependent-dropdown.villages');
    // Route::resource('admin/projects/{project}/house-types', HouseTypeController::class)->shallow();
    Route::get('/get-villages', [DependentDropdownController::class, 'villages'])->name('dependent-dropdown.villages');
    Route::resource('projects.house-types', HouseTypeController::class)
        ->shallow() // ->shallow() membuat rute edit, show, update, destroy tidak perlu project_id
        ->names('admin.house-types');
    Route::get('/api/projects', [HomeController::class, 'getAllProjectsForMap'])->name('api.projects');
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

require __DIR__ . '/auth.php';
