<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data statistik.
     */
    public function index()
    {
        // Ambil data statistik dari database
        $totalProjects = HousingProject::count();
        $totalDevelopers = HousingProject::distinct('developer_name')->count();
        $totalUnits = HouseType::sum('total_units');
        $totalUnitsSold = HouseType::where('status', 'Terjual')->sum('total_units');

        // Kirim data ke view
        return view('dashboard', compact(
            'totalProjects',
            'totalDevelopers',
            'totalUnits',
            'totalUnitsSold'
        ));
    }
}