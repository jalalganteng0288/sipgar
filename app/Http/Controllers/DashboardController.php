<?php

namespace App\Http\Controllers;

// app/Http/Controllers/DashboardController.php
use App\Models\Developer;
use App\Models\HouseUnit;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // PENTING: Jika pengguna bukan Admin, tampilkan Dashboard yang berbeda atau batasi datanya
        if ($request->user()->hasRole('Admin')) {
            $totalProjects = HousingProject::count();
            $totalDevelopers = Developer::count();
            $totalHouseUnits = HouseUnit::count();
        } else {
            // Logika untuk role lain, misalnya 'Developer'
            // Contoh: hanya tampilkan proyek milik developer itu
            $totalProjects = 0; // atau HousingProject::where('developer_id', $request->user()->developer_id)->count();
            $totalDevelopers = 0;
            $totalHouseUnits = 0;
        }

        return view('dashboard', compact('totalProjects', 'totalDevelopers', 'totalHouseUnits'));
    }
}