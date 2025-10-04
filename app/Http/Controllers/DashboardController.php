<?php

namespace App\Http\Controllers;

use App\Models\HousingProject; // <-- Import model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total data di tabel housing_projects
        $totalProjects = HousingProject::count();

        // Kirim data hitungan ke view 'dashboard'
        return view('dashboard', ['totalProjects' => $totalProjects]);
    }
}